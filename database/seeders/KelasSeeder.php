<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // <-- WAJIB TAMBAHKAN INI

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // =================================================================
        // BAGIAN PERBAIKAN: Menangani Foreign Key Constraint
        // =================================================================
        
        // 1. Nonaktifkan sementara pemeriksaan foreign key
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan tabel (WAJIB: tabel anak dulu, baru tabel induk)
        DB::table('kelas_pertandingan')->truncate(); // <-- Kosongkan tabel anak
        DB::table('kelas')->truncate();              // <-- Baru kosongkan tabel induk

        // 3. Aktifkan kembali pemeriksaan foreign key
        Schema::enableForeignKeyConstraints();
        
        // =================================================================
        // Sisa kode seeder Anda (tidak ada perubahan di bawah ini)
        // =================================================================

        // Ambil ID rentang usia dari database untuk pemetaan
        $usiaIds = DB::table('rentang_usia')->pluck('id', 'rentang_usia');

        $dataToInsert = [];

        // DATA KELAS TANDING (PUTRA & PUTRI)
        $tandingData = [
            'Usia Dini 1 (5-8 Tahun)'   => ['Kelas A (18 - 19 kg)', 'Kelas B (19 - 20 kg)', 'Kelas C (20 - 21 kg)', 'Kelas D (21 - 22 kg)', 'Kelas E (22 - 23 kg)', 'Kelas F (23 - 24 kg)', 'Kelas G (24 - 25 kg)', 'Kelas H (25 - 26 kg)', 'Kelas I (26 - 27 kg)'],
            'Usia Dini 2 (8-11 Tahun)'  => ['Kelas A (26 - 28 kg)', 'Kelas B (28 - 30 kg)', 'Kelas C (30 - 32 kg)', 'Kelas D (32 - 34 kg)', 'Kelas E (34 - 36 kg)', 'Kelas F (36 - 38 kg)', 'Kelas G (38 - 40 kg)', 'Kelas H (40 - 42 kg)', 'Kelas I (42 - 44 kg)'],
            'Pra Remaja (11-14 Tahun)'  => ['Kelas A (30 - 33 kg)', 'Kelas B (33 - 36 kg)', 'Kelas C (36 - 39 kg)', 'Kelas D (39 - 42 kg)', 'Kelas E (42 - 45 kg)', 'Kelas F (45 - 48 kg)', 'Kelas G (48 - 51 kg)', 'Kelas H (51 - 54 kg)', 'Kelas I (54 - 57 kg)'],
            'Remaja (13-17 Tahun)'      => ['Kelas A (39 - 43 kg)', 'Kelas B (43 - 47 kg)', 'Kelas C (47 - 51 kg)', 'Kelas D (51 - 55 kg)', 'Kelas E (55 - 59 kg)', 'Kelas F (59 - 63 kg)', 'Kelas G (63 - 67 kg)', 'Kelas H (67 - 71 kg)', 'Kelas I (71 - 75 kg)'],
            'Dewasa (17-23 Tahun)'      => ['Kelas A (45 - 50 kg)', 'Kelas B (50 - 55 kg)', 'Kelas C (55 - 60 kg)', 'Kelas D (60 - 65 kg)', 'Kelas E (65 - 70 kg)', 'Kelas F (70 - 75 kg)', 'Kelas G (75 - 80 kg)', 'Kelas H (80 - 85 kg)', 'Kelas I (85 - 90 kg)', 'Kelas J (90 - 95 kg)'],
        ];

        foreach ($tandingData as $usiaNama => $kelasArray) {
            if (isset($usiaIds[$usiaNama])) {
                $usiaId = $usiaIds[$usiaNama];
                foreach ($kelasArray as $namaKelas) {
                    $dataToInsert[] = ['nama_kelas' => $namaKelas, 'rentang_usia_id' => $usiaId];
                }
            }
        }

        // DATA KELAS SENI PEMASALAN
        $seniPemasalanKelas = ['Tunggal Tangan Kosong', 'Tunggal Bersenjata', 'Ganda Tangan Kosong', 'Ganda Bersenjata', 'Beregu Jurus 1 - 6', 'Tunggal Bebas Kosongan', 'Tunggal Bebas Bersenjata', 'Berpasangan Jurus Baku SD A - SD B', 'Berkelompok Jurus Baku TK'];
        $seniPemasalanUsia = ['Usia Dini 1 (5-8 Tahun)', 'Usia Dini 2 (8-11 Tahun)', 'Pra Remaja (11-14 Tahun)'];

        foreach ($seniPemasalanKelas as $namaKelas) {
            foreach ($seniPemasalanUsia as $usiaNama) {
                if (isset($usiaIds[$usiaNama])) {
                    $dataToInsert[] = ['nama_kelas' => $namaKelas, 'rentang_usia_id' => $usiaIds[$usiaNama]];
                }
            }
        }

        // DATA KELAS SENI PRESTASI
        $seniPrestasiKelas = ['Tunggal', 'Ganda', 'Beregu', 'Tunggal Bebas', 'Perorangan Jurus Baku SMA', 'Berpasangan Jurus Baku SMP', 'Berkelompok Jurus Baku TK'];
        $seniPrestasiUsia = ['Remaja (13-17 Tahun)', 'Dewasa (17-23 Tahun)'];

        foreach ($seniPrestasiKelas as $namaKelas) {
            foreach ($seniPrestasiUsia as $usiaNama) {
                if (isset($usiaIds[$usiaNama])) {
                    $dataToInsert[] = ['nama_kelas' => $namaKelas, 'rentang_usia_id' => $usiaIds[$usiaNama]];
                }
            }
        }
        
        // Tambahkan timestamp ke semua data yang akan dimasukkan
        foreach ($dataToInsert as &$data) {
            $data['created_at'] = now();
            $data['updated_at'] = now();
        }

        // Masukkan semua data yang sudah disiapkan ke database
        DB::table('kelas')->insert($dataToInsert);
    }
}