<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KelasPertandinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Persiapan: Kosongkan tabel dan ambil data master
        Schema::disableForeignKeyConstraints();
        DB::table('kelas_pertandingan')->truncate();
        Schema::enableForeignKeyConstraints();

        $kategoriIds = DB::table('kategori_pertandingan')->pluck('id', 'nama_kategori');
        $jenisIds = DB::table('jenis_pertandingan')->pluck('id', 'nama_jenis');
        
        $semuaKelas = DB::table('kelas')
            ->join('rentang_usia', 'kelas.rentang_usia_id', '=', 'rentang_usia.id')
            ->select('kelas.*', 'rentang_usia.rentang_usia as nama_rentang_usia')
            ->get();
            
        $eventId = 1; // Asumsi Event ID adalah 1

        $dataUntukInsert = [];

        // -----------------------------------------------------------------
        // KATEGORI TANDING PEMASALAN
        // -----------------------------------------------------------------
        $tandingPemasalanUsia = ['Usia Dini 1 (5-8 Tahun)', 'Usia Dini 2 (8-11 Tahun)', 'Pra Remaja (11-14 Tahun)'];
        foreach ($semuaKelas as $kelas) {
            if (in_array($kelas->nama_rentang_usia, $tandingPemasalanUsia) && str_contains(strtolower($kelas->nama_kelas), 'kelas')) {
                $dataUntukInsert[] = [ 'event_id' => $eventId, 'kelas_id' => $kelas->id, 'kategori_pertandingan_id' => $kategoriIds['Pemasalan'], 'jenis_pertandingan_id' => $jenisIds['Tanding'], 'gender' => 'Laki-laki', 'harga' => 200000 ];
                $dataUntukInsert[] = [ 'event_id' => $eventId, 'kelas_id' => $kelas->id, 'kategori_pertandingan_id' => $kategoriIds['Pemasalan'], 'jenis_pertandingan_id' => $jenisIds['Tanding'], 'gender' => 'Perempuan', 'harga' => 200000 ];
            }
        }
        
        // -----------------------------------------------------------------
        // KATEGORI TANDING PRESTASI
        // -----------------------------------------------------------------
        $tandingPrestasiUsia = ['Remaja (13-17 Tahun)', 'Dewasa (17-23 Tahun)'];
        foreach ($semuaKelas as $kelas) {
            if (in_array($kelas->nama_rentang_usia, $tandingPrestasiUsia) && str_contains(strtolower($kelas->nama_kelas), 'kelas')) {
                $dataUntukInsert[] = [ 'event_id' => $eventId, 'kelas_id' => $kelas->id, 'kategori_pertandingan_id' => $kategoriIds['Prestasi'], 'jenis_pertandingan_id' => $jenisIds['Tanding'], 'gender' => 'Laki-laki', 'harga' => 175000 ];
                $dataUntukInsert[] = [ 'event_id' => $eventId, 'kelas_id' => $kelas->id, 'kategori_pertandingan_id' => $kategoriIds['Prestasi'], 'jenis_pertandingan_id' => $jenisIds['Tanding'], 'gender' => 'Perempuan', 'harga' => 175000 ];
            }
        }

        // -----------------------------------------------------------------
        // KATEGORI SENI & JURUS PEMASALAN
        // -----------------------------------------------------------------
        $pemasalanRules = [
            'Tunggal Tangan Kosong' => ['jenis' => 'Seni', 'harga' => 200000],
            'Tunggal Bersenjata' => ['jenis' => 'Seni', 'harga' => 200000],
            'Tunggal Bebas Kosongan' => ['jenis' => 'Seni', 'harga' => 200000],
            'Tunggal Bebas Bersenjata' => ['jenis' => 'Seni', 'harga' => 200000],
            'Ganda Tangan Kosong' => ['jenis' => 'Seni', 'harga' => 400000],
            'Ganda Bersenjata' => ['jenis' => 'Seni', 'harga' => 400000],
            'Beregu Jurus 1 - 6' => ['jenis' => 'Jurus Baku', 'harga' => 600000],
            'Berpasangan Jurus Paket SD A - SD B' => ['jenis' => 'Jurus Baku', 'harga' => 400000],
            'Berkelompok Jurus Paket TK' => ['jenis' => 'Jurus Baku', 'harga' => 600000],
        ];
        $pemasalanUsia = ['Usia Dini 1 (5-8 Tahun)', 'Usia Dini 2 (8-11 Tahun)', 'Pra Remaja (11-14 Tahun)'];
        foreach($semuaKelas as $kelas) {
            if (in_array($kelas->nama_rentang_usia, $pemasalanUsia) && isset($pemasalanRules[$kelas->nama_kelas])) {
                $rule = $pemasalanRules[$kelas->nama_kelas];
                // =======================================================
                // PERBAIKAN: Buat entri terpisah untuk Laki-laki dan Perempuan
                // =======================================================
                $dataUntukInsert[] = [ 'event_id' => $eventId, 'kelas_id' => $kelas->id, 'kategori_pertandingan_id' => $kategoriIds['Pemasalan'], 'jenis_pertandingan_id' => $jenisIds[$rule['jenis']], 'gender' => 'Laki-laki', 'harga' => $rule['harga'] ];
                $dataUntukInsert[] = [ 'event_id' => $eventId, 'kelas_id' => $kelas->id, 'kategori_pertandingan_id' => $kategoriIds['Pemasalan'], 'jenis_pertandingan_id' => $jenisIds[$rule['jenis']], 'gender' => 'Perempuan', 'harga' => $rule['harga'] ];
            }
        }
        
        // -----------------------------------------------------------------
        // KATEGORI SENI & JURUS PRESTASI
        // -----------------------------------------------------------------
        $prestasiRules = [
            'Tunggal' => ['jenis' => 'Seni', 'harga' => 175000],
            'Tunggal Bebas' => ['jenis' => 'Seni', 'harga' => 175000],
            'Ganda' => ['jenis' => 'Seni', 'harga' => 350000],
            'Beregu' => ['jenis' => 'Seni', 'harga' => 525000],
            'Perorangan Jurus Paket SMA' => ['jenis' => 'Jurus Baku', 'harga' => 175000],
            'Berpasangan Jurus Paket SMP' => ['jenis' => 'Jurus Baku', 'harga' => 350000],
            'Berkelompok Jurus Paket TK' => ['jenis' => 'Jurus Baku', 'harga' => 525000],
        ];
        $prestasiUsia = ['Remaja (13-17 Tahun)', 'Dewasa (17-23 Tahun)'];
        foreach($semuaKelas as $kelas) {
            if (in_array($kelas->nama_rentang_usia, $prestasiUsia) && isset($prestasiRules[$kelas->nama_kelas])) {
                $rule = $prestasiRules[$kelas->nama_kelas];
                // =======================================================
                // PERBAIKAN: Buat entri terpisah untuk Laki-laki dan Perempuan
                // =======================================================
                $dataUntukInsert[] = [ 'event_id' => $eventId, 'kelas_id' => $kelas->id, 'kategori_pertandingan_id' => $kategoriIds['Prestasi'], 'jenis_pertandingan_id' => $jenisIds[$rule['jenis']], 'gender' => 'Laki-laki', 'harga' => $rule['harga'] ];
                $dataUntukInsert[] = [ 'event_id' => $eventId, 'kelas_id' => $kelas->id, 'kategori_pertandingan_id' => $kategoriIds['Prestasi'], 'jenis_pertandingan_id' => $jenisIds[$rule['jenis']], 'gender' => 'Perempuan', 'harga' => $rule['harga'] ];
            }
        }

        // Tambahkan timestamp ke semua data yang akan dimasukkan
        foreach ($dataUntukInsert as &$data) {
            $data['created_at'] = now();
            $data['updated_at'] = now();
        }

        // Masukkan semua data yang sudah disiapkan ke database
        DB::table('kelas_pertandingan')->insert($dataUntukInsert);
    }
}