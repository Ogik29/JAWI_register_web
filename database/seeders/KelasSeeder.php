<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KelasSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('kelas_pertandingan')->truncate();
        DB::table('kelas')->truncate();
        Schema::enableForeignKeyConstraints();
        
        $usiaIds = DB::table('rentang_usia')->pluck('id', 'rentang_usia');
        $dataToInsert = [];

        // =================================================================
        // DEFINISI DATA LENGKAP DARI SEMUA DOKUMEN
        // =================================================================
        $dataLengkap = [
            'Pra Usia Dini (<= 5 tahun)' => [
                'jurus' => ['Jurus Tunggal Bebas']
            ],
            'Usia Dini 1 (> 5 s.d 8 tahun)' => [
                'jurus' => [
                    'Jurus Tunggal Tangan Kosong',
                    'Jurus Tunggal Senjata (Toya dan Golok)',
                    'Jurus Tunggal Bebas'
                ]
            ],
            'Usia Dini 2 (> 8 s.d 11 tahun)' => [
                'tanding_putra' => ['Kelas A (diatas 26 kg sampai 28 kg)', 'Kelas B (diatas 28 kg sampai 30 kg)', 'Kelas C (diatas 30 kg sampai 32 kg)', 'Kelas D (diatas 32 kg sampai 34 kg)', 'Kelas E (diatas 34 kg sampai 36 kg)', 'Kelas F (diatas 36 kg sampai 38 kg)', 'Kelas G (diatas 38 kg sampai 40 kg)', 'Kelas H (diatas 40 kg sampai 42 kg)', 'Kelas I (diatas 42 kg sampai 44 kg)', 'Kelas J (diatas 44 kg sampai 46 kg)', 'Kelas K (diatas 46 kg sampai 48 kg)', 'Kelas L (diatas 48 kg sampai 50 kg)', 'Kelas M (diatas 50 kg sampai 52 kg)', 'Kelas N (diatas 52 kg sampai 54 kg)', 'Kelas O (diatas 54 kg sampai 56 kg)', 'Kelas P (diatas 56 kg sampai 58 kg)', 'Kelas Q (diatas 58 kg sampai 60 kg)', 'Kelas R (diatas 60 kg sampai 62 kg)', 'Kelas S (diatas 62 kg sampai 64 kg)', 'Open (diatas 64 kg sampai 68 kg)'],
                'tanding_putri' => ['Kelas A (diatas 26 kg sampai 28 kg)', 'Kelas B (diatas 28 kg sampai 30 kg)', 'Kelas C (diatas 30 kg sampai 32 kg)', 'Kelas D (diatas 32 kg sampai 34 kg)', 'Kelas E (diatas 34 kg sampai 36 kg)', 'Kelas F (diatas 36 kg sampai 38 kg)', 'Kelas G (diatas 38 kg sampai 40 kg)', 'Kelas H (diatas 40 kg sampai 42 kg)', 'Kelas I (diatas 42 kg sampai 44 kg)', 'Kelas J (diatas 44 kg sampai 46 kg)', 'Kelas K (diatas 46 kg sampai 48 kg)', 'Kelas L (diatas 48 kg sampai 50 kg)', 'Kelas M (diatas 50 kg sampai 52 kg)', 'Kelas N (diatas 52 kg sampai 54 kg)', 'Kelas O (diatas 54 kg sampai 56 kg)', 'Kelas P (diatas 56 kg sampai 58 kg)', 'Kelas Q (diatas 58 kg sampai 60 kg)', 'Kelas R (diatas 60 kg sampai 62 kg)', 'Kelas S (diatas 62 kg sampai 64 kg)', 'Open (diatas 64 kg sampai 68 kg)'],
                'jurus' => ['Jurus Tunggal Tangan Kosong', 'Jurus Tunggal Senjata (Toya dan Golok)', 'Jurus Tunggal Bebas', 'Jurus Ganda Tangan Kosong', 'Jurus Ganda Senjata', 'Jurus Regu A 1 - 6']
            ],
            'Pra Remaja (> 11 s.d 14 tahun)' => [
                'tanding_putra' => ['Kelas A (diatas 30 kg sampai 33 kg)', 'Kelas B (diatas 33 kg sampai 36 kg)', 'Kelas C (diatas 36 kg sampai 39 kg)', 'Kelas D (diatas 39 kg sampai 42 kg)', 'Kelas E (diatas 42 kg sampai 45 kg)', 'Kelas F (diatas 45 kg sampai 48 kg)', 'Kelas G (diatas 48 kg sampai 51 kg)', 'Kelas H (diatas 51 kg sampai 54 kg)', 'Kelas I (diatas 54 kg sampai 57 kg)', 'Kelas J (diatas 57 kg sampai 60 kg)', 'Kelas K (diatas 60 kg sampai 63 kg)', 'Kelas L (diatas 63 kg sampai 66 kg)', 'Kelas M (diatas 66 kg sampai 69 kg)', 'Kelas N (diatas 69 kg sampai 72 kg)', 'Kelas O (diatas 72 kg sampai 75 kg)', 'Kelas P (diatas 75 kg sampai 78 kg)', 'Open (diatas 78 kg sampai 84 kg)'],
                'tanding_putri' => ['Kelas A (diatas 30 kg sampai 33 kg)', 'Kelas B (diatas 33 kg sampai 36 kg)', 'Kelas C (diatas 36 kg sampai 39 kg)', 'Kelas D (diatas 39 kg sampai 42 kg)', 'Kelas E (diatas 42 kg sampai 45 kg)', 'Kelas F (diatas 45 kg sampai 48 kg)', 'Kelas G (diatas 48 kg sampai 51 kg)', 'Kelas H (diatas 51 kg sampai 54 kg)', 'Kelas I (diatas 54 kg sampai 57 kg)', 'Kelas J (diatas 57 kg sampai 60 kg)', 'Kelas K (diatas 60 kg sampai 63 kg)', 'Kelas L (diatas 63 kg sampai 66 kg)', 'Kelas M (diatas 66 kg sampai 69 kg)', 'Kelas N (diatas 69 kg sampai 72 kg)', 'Kelas O (diatas 72 kg sampai 75 kg)', 'Kelas P (diatas 75 kg sampai 78 kg)', 'Open (diatas 78 kg sampai 84 kg)'],
                'jurus' => ['Jurus Tunggal Tangan Kosong', 'Jurus Tunggal Senjata (Toya dan Golok)', 'Jurus Tunggal Bebas', 'Jurus Ganda Tangan Kosong', 'Jurus Ganda Senjata', 'Jurus Regu B (7 - 12)']
            ],
            'Remaja (> 14 s.d 17 tahun)' => [
                'tanding_putra' => ['Kelas <39 (Dibawah 39 kg)', 'Kelas A (39 kg sampai 43 kg)', 'Kelas B (43 kg sampai 47 kg)', 'Kelas C (47 kg sampai 51 kg)', 'Kelas D (51 kg sampai 55 kg)', 'Kelas E (55 kg sampai 59 kg)', 'Kelas F (59 kg sampai 63 kg)', 'Kelas G (63 kg sampai 67 kg)', 'Kelas H (67 kg sampai 71 kg)', 'Kelas I (71 kg sampai 75 kg)', 'Kelas J (75 kg sampai 79 kg)', 'Kelas K (79 kg sampai 83 kg)', 'Kelas L (83 kg sampai 87 kg)', 'Open 1 (diatas 87 kg sampai 100 kg)', 'Open 2 (diatas 100 kg)'],
                'tanding_putri' => ['Kelas <39 (Dibawah 39 kg)', 'Kelas A (39 kg sampai 43 kg)', 'Kelas B (43 kg sampai 47 kg)', 'Kelas C (47 kg sampai 51 kg)', 'Kelas D (51 kg sampai 55 kg)', 'Kelas E (55 kg sampai 59 kg)', 'Kelas F (59 kg sampai 63 kg)', 'Kelas G (63 kg sampai 67 kg)', 'Kelas H (67 kg sampai 71 kg)', 'Kelas I (71 kg sampai 75 kg)', 'Kelas J (75 kg sampai 79 kg)', 'Open 1 (diatas 79 kg sampai 92 kg)', 'Open 2 (diatas 92 kg)'],
                'jurus' => ['Jurus Tunggal', 'Jurus Tunggal Bebas', 'Jurus Ganda', 'Jurus Regu']
            ],
            'Dewasa (> 17 s.d 35 tahun)' => [
                'tanding_putra' => ['Kelas <45 (Dibawah 45 kg)', 'Kelas A (45 kg sampai 50 kg)', 'Kelas B (50 kg sampai 55 kg)', 'Kelas C (55 kg sampai 60 kg)', 'Kelas D (60 kg sampai 65 kg)', 'Kelas E (65 kg sampai 70 kg)', 'Kelas F (70 kg sampai 75 kg)', 'Kelas G (75 kg sampai 80 kg)', 'Kelas H (80 kg sampai 85 kg)', 'Kelas I (85 kg sampai 90 kg)', 'Kelas J (90 kg sampai 95 kg)', 'Open 1 (diatas 95 kg sampai 110 kg)', 'Open 2 (diatas 110kg)'],
                'tanding_putri' => ['Kelas <45 (Dibawah 45 kg)', 'Kelas A (45 kg sampai 50 kg)', 'Kelas B (50 kg sampai 55 kg)', 'Kelas C (55 kg sampai 60 kg)', 'Kelas D (60 kg sampai 65 kg)', 'Kelas E (65 kg sampai 70 kg)', 'Kelas F (70 kg sampai 75 kg)', 'Kelas G (75 kg sampai 80 kg)', 'Kelas H (80 kg sampai 85 kg)', 'Open 1 (diatas 85 kg sampai 100 kg)', 'Open 2 (diatas 100 kg)'],
                'jurus' => ['Jurus Tunggal', 'Jurus Tunggal Bebas', 'Jurus Ganda', 'Jurus Regu']
            ],
             'Master A (> 35 s.d 45 tahun)' => [
                'tanding_putra' => ['Kelas <45 (Dibawah 45 kg)', 'Kelas A (45 kg sampai 50 kg)', 'Kelas B (50 kg sampai 55 kg)', 'Kelas C (55 kg sampai 60 kg)', 'Kelas D (60 kg sampai 65 kg)', 'Kelas E (65 kg sampai 70 kg)', 'Kelas F (70 kg sampai 75 kg)', 'Kelas G (75 kg sampai 80 kg)', 'Kelas H (80 kg sampai 85 kg)', 'Kelas I (85 kg sampai 90 kg)', 'Kelas J (90 kg sampai 95 kg)', 'Open 1 (diatas 95 kg sampai 110 kg)', 'Open 2 (diatas 110 kg)'],
                'tanding_putri' => ['Kelas <45 (Dibawah 45 kg)', 'Kelas A (45 kg sampai 50 kg)', 'Kelas B (50 kg sampai 55 kg)', 'Kelas C (55 kg sampai 60 kg)', 'Kelas D (60 kg sampai 65 kg)', 'Kelas E (65 kg sampai 70 kg)', 'Kelas F (70 kg sampai 75 kg)', 'Kelas G (75 kg sampai 80 kg)', 'Kelas H (80 kg sampai 85 kg)', 'Open 1 (diatas 85 kg sampai 100 kg)', 'Open 2 (diatas 100 kg)']
            ],
             'Master B (> 45 tahun ke atas)' => [
                'tanding_putra' => ['Kelas <45 (Dibawah 45 kg)', 'Kelas A (45 kg sampai 50 kg)', 'Kelas B (50 kg sampai 55 kg)', 'Kelas C (55 kg sampai 60 kg)', 'Kelas D (60 kg sampai 65 kg)', 'Kelas E (65 kg sampai 70 kg)', 'Kelas F (70 kg sampai 75 kg)', 'Kelas G (75 kg sampai 80 kg)', 'Kelas H (80 kg sampai 85 kg)', 'Kelas I (85 kg sampai 90 kg)', 'Kelas J (90 kg sampai 95 kg)', 'Open 1 (diatas 95 kg sampai 110 kg)', 'Open 2 (diatas 110 kg)'],
                'tanding_putri' => ['Kelas <45 (Dibawah 45 kg)', 'Kelas A (45 kg sampai 50 kg)', 'Kelas B (50 kg sampai 55 kg)', 'Kelas C (55 kg sampai 60 kg)', 'Kelas D (60 kg sampai 65 kg)', 'Kelas E (65 kg sampai 70 kg)', 'Kelas F (70 kg sampai 75 kg)', 'Kelas G (75 kg sampai 80 kg)', 'Kelas H (80 kg sampai 85 kg)', 'Open 1 (diatas 85 kg sampai 100 kg)', 'Open 2 (diatas 100 kg)']
            ],
        ];

        // PROSES DATA DAN SIAPKAN UNTUK INSERT
        foreach ($dataLengkap as $usiaNama => $kategori) {
            if (!isset($usiaIds[$usiaNama])) continue;
            
            $usiaId = $usiaIds[$usiaNama];

            foreach ($kategori as $jenisKey => $kelasArray) {
                foreach ($kelasArray as $namaKelas) {
                    $namaFinal = ($jenisKey === 'tanding_putra' ? 'Putra ' : ($jenisKey === 'tanding_putri' ? 'Putri ' : '')) . $namaKelas;
                    
                    // Gunakan 'firstOrCreate' like logic untuk menghindari duplikat nama kelas
                    // Ini tidak efisien di seeder, jadi kita akan filter manual
                    $exists = false;
                    foreach ($dataToInsert as $existingData) {
                        if ($existingData['nama_kelas'] === $namaFinal && $existingData['rentang_usia_id'] === $usiaId) {
                            $exists = true;
                            break;
                        }
                    }

                    if (!$exists) {
                         $dataToInsert[] = ['nama_kelas' => $namaFinal, 'rentang_usia_id' => $usiaId];
                    }
                }
            }
        }
        
        // TENTUKAN JUMLAH PEMAIN & TAMBAHKAN TIMESTAMPS
        foreach ($dataToInsert as &$data) {
            $namaKelasLower = strtolower($data['nama_kelas']);
            $jumlahPemain = 1;
            if (str_contains($namaKelasLower, 'ganda')) $jumlahPemain = 2;
            elseif (str_contains($namaKelasLower, 'regu')) $jumlahPemain = 3;

            $data['jumlah_pemain'] = $jumlahPemain;
            $data['created_at'] = now();
            $data['updated_at'] = now();
        }

        DB::table('kelas')->insert($dataToInsert);
    }
}