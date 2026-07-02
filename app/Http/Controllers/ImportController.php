<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pertandingan;
use App\Models\BracketPeserta;
use App\Models\Arena;
use App\Models\Player;
use App\Models\Contingent;
use App\Models\UnitPemasalanSeni; // BARU: Impor model baru
use Illuminate\Support\Facades\DB;
use App\Imports\BracketImport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Collection;

class ImportController extends Controller
{
    public function showForm()
    {
        return view('superadmin.import_form');
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx'
        ]);

        $rows = Excel::toCollection(new BracketImport, $request->file('file'))->first()->slice(1);

        if ($rows->isEmpty()) {
            return redirect()->back()->with('error', 'File CSV yang diunggah kosong atau tidak valid.');
        }

        try {
            DB::transaction(function () use ($rows) {
                $unitCounter = (BracketPeserta::max('unit_id') ?? 0) + 1;
                $processedKelasIds = [];
                $pertandinganData = collect();

                // =================================================================
                // LOOP 1: Validasi dan kumpulkan semua data mentah ke dalam koleksi
                // =================================================================
                foreach ($rows as $rowIndex => $row) {
                    $partaiIdCsv = trim($row[0]);
                    if (empty($partaiIdCsv)) continue;

                    // MODIFIKASI: Penyesuaian urutan kolom sesuai template baru
                    $arenaNama = trim($row[1]);
                    $nextPartaiId = trim($row[2] ?? null);
                    $jumlahPeserta = (int)trim($row[3]); // BARU: Ambil jumlah peserta

                    $kelasId = null;
                    $unitIds = []; // BARU: Array untuk menampung semua unit_id untuk partai ini

                    // BARU: Loop dinamis untuk memproses semua peserta
                    for ($i = 0; $i < $jumlahPeserta; $i++) {
                        // Kolom nama dimulai dari indeks 4, kontingen dari 5. Setiap peserta butuh 2 kolom.
                        $namaIndex = 4 + ($i * 2);
                        $kontingenIndex = 5 + ($i * 2);

                        $namaUnit = trim($row[$namaIndex] ?? '');
                        $kontingenUnit = trim($row[$kontingenIndex] ?? '');
                        
                        // Proses hanya jika ada nama pemain
                        if (!empty($namaUnit) && $namaUnit != '#N/A') {
                            $pemain = $this->findPlayerOrFail($namaUnit, $kontingenUnit, $rowIndex);
                            
                            // Ambil kelas pertandingan, prioritaskan dari pemain pertama
                            if (is_null($kelasId)) {
                                $kelasId = $pemain->kelas_pertandingan_id;
                                $processedKelasIds[$kelasId] = true;
                            }
                            
                            BracketPeserta::create(['kelas_pertandingan_id' => $kelasId, 'player_id' => $pemain->id, 'unit_id' => $unitCounter]);
                            $unitIds[] = $unitCounter; // Tambahkan unit_id ke array
                            $unitCounter++;
                        }
                    }
                    
                    // Simpan data yang sudah diproses ke koleksi sementara
                    $pertandinganData->push([
                        'partai_id_csv' => $partaiIdCsv,
                        'kelas_pertandingan_id' => $kelasId,
                        'arena_nama' => $arenaNama,
                        'unit_ids' => $unitIds, // MODIFIKASI: Simpan array unit_id
                        'next_partai_id' => $nextPartaiId,
                        'jumlah_peserta' => $jumlahPeserta, // BARU: Simpan juga jumlah pesertanya
                    ]);
                }
                
                if (!empty($processedKelasIds)) {
                    Pertandingan::whereIn('kelas_pertandingan_id', array_keys($processedKelasIds))->delete();
                }

                // =================================================================
                // LOOP 2: Buat SEMUA record Pertandingan (tanpa next_match_id)
                // =================================================================
                $partaiMapping = [];
                foreach ($pertandinganData as $data) {
                    $arena = !empty($data['arena_nama']) ? Arena::firstOrCreate(['arena_name' => $data['arena_nama']]) : null;
                    
                    if (is_null($data['kelas_pertandingan_id'])) {
                        $sumber = $pertandinganData->firstWhere('next_partai_id', $data['partai_id_csv']);
                        if ($sumber) {
                            $data['kelas_pertandingan_id'] = $sumber['kelas_pertandingan_id'];
                        } else {
                            throw new Exception("Tidak bisa menentukan Kelas Pertandingan untuk Partai CSV #{$data['partai_id_csv']}.");
                        }
                    }

                    // MODIFIKASI: Logika kondisional untuk menyimpan unit
                    $unit1 = null;
                    $unit2 = null;

                    // Jika 2 peserta atau kurang (standar), masukkan ke kolom utama
                    if ($data['jumlah_peserta'] <= 2) {
                        $unit1 = $data['unit_ids'][0] ?? null;
                        $unit2 = $data['unit_ids'][1] ?? null;
                    }

                    $pertandinganBaru = Pertandingan::create([
                        'kelas_pertandingan_id' => $data['kelas_pertandingan_id'],
                        'arena_id' => $arena?->id,
                        'unit1_id' => $unit1,       // Diisi atau NULL berdasarkan kondisi
                        'unit2_id' => $unit2,       // Diisi atau NULL berdasarkan kondisi
                        'next_match_id' => null,
                        'status' => 'menunggu_peserta',
                        'round_number' => 1,
                        'match_number' => $data['partai_id_csv'],
                        'current_round' => 1,
                    ]);
                    
                    // BARU: Jika peserta lebih dari 2, masukkan ke tabel unit_pemasalan_seni
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
                // LOOP 3: Hubungkan next_match_id (TIDAK PERLU DIUBAH)
                // =================================================================
                foreach($pertandinganData as $data){
                    if(!empty($data['next_partai_id'])){
                        $sumber_db_id = $partaiMapping[$data['partai_id_csv']] ?? null;
                        $tujuan_db_id = $partaiMapping[$data['next_partai_id']] ?? null;

                        if ($sumber_db_id && $tujuan_db_id) {
                            Pertandingan::where('id', $sumber_db_id)->update(['next_match_id' => $tujuan_db_id]);
                        }
                    }
                }
            });
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Impor Gagal: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Bracket berhasil diimpor dari file!');
    }

    private function findPlayerOrFail(string $namaPemain, string $kontingenNama, int $rowIndex): Player
    {
        $kontingen = Contingent::where('name', $kontingenNama)->first();
        if (!$kontingen) {
            throw new Exception("Kontingen '{$kontingenNama}' (baris " . ($rowIndex + 2) . ") tidak ditemukan di database.");
        }
        
        $player = Player::where('name', $namaPemain)
                        ->where('contingent_id', $kontingen->id)
                        ->first();

        if (!$player) {
            throw new Exception("Pemain '{$namaPemain}' dari '{$kontingenNama}' (baris " . ($rowIndex + 2) . ") tidak ditemukan.");
        }
        if (is_null($player->kelas_pertandingan_id)) {
            throw new Exception("Pemain '{$namaPemain}' (baris " . ($rowIndex + 2) . ") belum terdaftar di kelas pertandingan.");
        }
        return $player;
    }
}