<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use App\Models\Pertandingan;
use App\Models\BracketPeserta;
use App\Models\KelasPertandingan;
use App\Models\KategoriPertandingan;
use App\Models\JenisPertandingan;
use App\Models\Kelas;
use App\Models\Arena;
use App\Models\Player;
use App\Models\Contingent;
use App\Models\UnitPemasalanSeni; // PASTIKAN MODEL INI DI-IMPORT
use Exception;

class BracketImport implements ToCollection
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        $dataRows = $rows->slice(1);

        if ($dataRows->isEmpty()) {
            throw new Exception("File yang diunggah kosong atau tidak valid.");
        }

        DB::transaction(function () use ($dataRows) {
            
            $unitCounter = (BracketPeserta::max('unit_id') ?? 0) + 1;
            $pertandinganData = collect(); // Koleksi sementara untuk data terstruktur
            $processedKelasIds = [];
            
            // =================================================================
            // LOOP 1: Validasi, buat entitas dasar, dan kumpulkan data
            // =================================================================
            foreach ($dataRows as $rowIndex => $row) {
                $partaiIdCsv = trim($row[0]);
                if (empty($partaiIdCsv)) continue;

                $kategoriNama = trim($row[1]);
                $jenisNama = trim($row[2]);
                $kelasNama = trim($row[3]);
                $gender = trim($row[4]);
                $jumlahPeserta = (int)trim($row[5] ?? 0); // Ambil jumlah peserta
                $arenaNama = trim($row[count($row) - 1]); // Asumsi Arena selalu di kolom terakhir

                if ($jumlahPeserta == 0) {
                    throw new Exception("Jumlah peserta untuk Partai #{$partaiIdCsv} (baris ".($rowIndex+2).") adalah 0 atau kosong.");
                }

                // CARI ATAU BUAT KELAS PERTANDINGAN
                $kategori = KategoriPertandingan::firstOrCreate(['nama_kategori' => $kategoriNama]);
                $jenis = JenisPertandingan::firstOrCreate(['nama_jenis' => $jenisNama]);
                $kelasModel = Kelas::firstOrCreate(['nama_kelas' => $kelasNama]);
                $kelasPertandingan = KelasPertandingan::firstOrCreate([
                    'kategori_pertandingan_id' => $kategori->id,
                    'jenis_pertandingan_id' => $jenis->id,
                    'kelas_id' => $kelasModel->id,
                    'gender' => $gender,
                ]);
                $processedKelasIds[$kelasPertandingan->id] = true;

                // PROSES PESERTA
                $unitIds = [];
                for ($i = 0; $i < $jumlahPeserta; $i++) {
                    $namaIndex = 6 + ($i * 2);
                    $kontingenIndex = 7 + ($i * 2);

                    $namaUnit = trim($row[$namaIndex] ?? '');
                    $kontingenUnit = trim($row[$kontingenIndex] ?? '');

                    // Hanya proses jika ada nama dan bukan placeholder "Pemenang Partai..."
                    if (!empty($namaUnit) && !str_contains(strtolower($namaUnit), 'pemenang') && $namaUnit != '#N/A') {
                        $pemainIds = $this->findOrCreatePlayers($namaUnit, $kontingenUnit, $kelasPertandingan->id);
                        
                        // Semua pemain dalam satu sel (beregu) masuk ke satu unit_id yang sama
                        foreach ($pemainIds as $playerId) {
                            BracketPeserta::create(['kelas_pertandingan_id' => $kelasPertandingan->id, 'player_id' => $playerId, 'unit_id' => $unitCounter]);
                        }
                        $unitIds[] = $unitCounter;
                        $unitCounter++;
                    }
                }

                // SIMPAN KE KOLEKSI SEMENTARA
                $pertandinganData->push([
                    'partai_id_csv' => $partaiIdCsv,
                    'kelas_pertandingan_id' => $kelasPertandingan->id,
                    'arena_nama' => $arenaNama,
                    'jumlah_peserta' => $jumlahPeserta,
                    'unit_ids' => $unitIds,
                ]);
            }
            
            // Hapus data pertandingan lama HANYA untuk kelas yang diproses
            if (!empty($processedKelasIds)) {
                Pertandingan::whereIn('kelas_pertandingan_id', array_keys($processedKelasIds))->delete();
            }

            // =================================================================
            // LOOP 2: Buat SEMUA record Pertandingan
            // =================================================================
            $partaiMapping = []; // [partai_id_csv => id_db_baru]
            foreach ($pertandinganData as $data) {
                $arena = !empty($data['arena_nama']) && $data['arena_nama'] != '#N/A' ? Arena::firstOrCreate(['arena_name' => $data['arena_nama']]) : null;
                
                $unit1 = null;
                $unit2 = null;
                // Logika kondisional: jika 2 peserta, masukkan ke kolom utama
                if ($data['jumlah_peserta'] <= 2) {
                    $unit1 = $data['unit_ids'][0] ?? null;
                    $unit2 = $data['unit_ids'][1] ?? null;
                }

                $pertandinganBaru = Pertandingan::create([
                    'kelas_pertandingan_id' => $data['kelas_pertandingan_id'],
                    'arena_id' => $arena?->id,
                    'unit1_id' => $unit1,
                    'unit2_id' => $unit2,
                    'next_match_id' => null, // Dibuat KOSONG dulu
                    'status' => 'menunggu_peserta',
                    'match_number' => $data['partai_id_csv'],
                ]);

                // Logika kondisional: jika > 2 peserta, masukkan ke tabel relasi
                if ($data['jumlah_peserta'] > 2) {
                    foreach ($data['unit_ids'] as $unitId) {
                        UnitPemasalanSeni::create([
                            'pertandingan_id' => $pertandinganBaru->id,
                            'unit_id' => $unitId
                        ]);
                    }
                }
                $partaiMapping[$data['partai_id_csv']] = $pertandinganBaru->id;
            }

            // =================================================================
            // LOOP 3: Hubungkan struktur bracket (next_match_id)
            // =================================================================
            foreach ($dataRows as $row) {
                $partaiTujuanIdCsv = trim($row[0]);
                $jumlahPeserta = (int)trim($row[5] ?? 0);

                for ($i = 0; $i < $jumlahPeserta; $i++) {
                    $namaIndex = 6 + ($i * 2);
                    $namaUnit = trim($row[$namaIndex] ?? '');

                    if (str_contains(strtolower($namaUnit), 'pemenang partai')) {
                        preg_match('/(\d+)/', $namaUnit, $matches);
                        if (isset($matches[1])) {
                            $partaiSumberIdCsv = $matches[1];
                            
                            $tujuan_db_id = $partaiMapping[$partaiTujuanIdCsv] ?? null;
                            $sumber_db_id = $partaiMapping[$partaiSumberIdCsv] ?? null;

                            if ($sumber_db_id && $tujuan_db_id) {
                                Pertandingan::where('id', $sumber_db_id)->update(['next_match_id' => $tujuan_db_id]);
                            }
                        }
                    }
                }
            }
        });
    }

    /**
     * Helper untuk mencari atau membuat Player & Contingent.
     * Mengembalikan array berisi ID pemain.
     */
    private function findOrCreatePlayers(string $namaString, string $kontingenString, int $kelasPertandinganId): array
    {
        if (empty($kontingenString) || $kontingenString == '#N/A') {
            throw new Exception("Kontingen tidak boleh kosong untuk '{$namaString}'.");
        }
        $kontingen = Contingent::firstOrCreate(['name' => $kontingenString]);
        
        $namaPemainArray = array_map('trim', explode(',', $namaString));
        $playerIds = [];

        foreach ($namaPemainArray as $namaPemain) {
            $player = Player::firstOrCreate(
                ['name' => $namaPemain, 'contingent_id' => $kontingen->id],
                [
                    'kelas_pertandingan_id' => $kelasPertandinganId,
                    'status' => 2,
                    'email' => strtolower(str_replace(' ', '.', $namaPemain)) . '.' . rand(100,999) . '@example.com',
                    'password' => bcrypt('password'),
                    // Tambahkan field default lain yang dibutuhkan oleh tabel 'players' Anda
                ]
            );

            // Update kelas pertandingan jika pemain sudah ada tapi kelasnya null
            if(is_null($player->kelas_pertandingan_id)){
                $player->update(['kelas_pertandingan_id' => $kelasPertandinganId]);
            }
            
            $playerIds[] = $player->id;
        }
        
        return $playerIds;
    }
}