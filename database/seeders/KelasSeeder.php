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

        // Gabungkan semua kemungkinan nama kelas dari Excel untuk menghindari duplikat
        $allPossibleClasses = [
            'PRA USIA DINI (dibawah 5 tahun)' => ['Tunggal Bebas', 'Berkelompok'],
            'USIA DINI 1 (diatas 5 - 8 tahun)' => array_merge(
                ['A = 18 - 19 KG', 'B = diatas 19 - 20 kg', 'C = diatas 20 - 21 kg', 'D = diatas 21 - 22 kg', 'E = diatas 22 - 23 kg', 'F = diatas 23 - 24 kg', 'G = diatas 24 - 25 kg', 'H = diatas 25 - 26 kg', 'I = diatas 26 - 27 kg', 'J = diatas 27 - 28 Kg', 'Open = diatas 28 - 29 kg'],
                ['Tunggal Tangan Kosong', 'Tunggal Bersenjata', 'Ganda Tangan Kosong', 'Ganda Bersenjata', 'Regu A (1 - 6)', 'Regu B (7 - 12)', 'Tunggal Bebas', 'Perseorangan', 'Berpasangan', 'Berkelompok']
            ),
            'USIA DINI 2 (diatas 8 - 11 tahun)' => array_merge(
                ['A = diatas 26 - 28 kg', 'B = diatas 28 - 30 kg', 'C = diatas 30 - 32 kg', 'D = diatas 32 - 34 kg', 'E = diatas 34 - 36 kg', 'F = diatas 36 - 38 kg', 'G = diatas 38 - 40 kg', 'H = diatas 40 - 42 kg', 'I = diatas 42 - 44 kg', 'J = diatas 44 - 46 kg', 'K = diatas 46 - 48 kg', 'L = diatas 48 - 50 kg', 'M = diatas 50 - 52 kg', 'N = diatas 52 - 54 kg', 'O = diatas 54 - 56 kg', 'P = diatas 56 - 58 kg', 'Q = diatas 58 - 60 kg', 'R = diatas 60 - 62 kg', 'S = diatas 62 - 64 kg', 'OPEN = diatas 64 - 68 kg'],
                ['Tunggal Tangan Kosong', 'Tunggal Bersenjata', 'Ganda Tangan Kosong', 'Ganda Bersenjata', 'Regu A (1 - 6)', 'Regu B (7 - 12)', 'Tunggal Bebas', 'Perseorangan', 'Berpasangan', 'Berkelompok']
            ),
            'PRA REMAJA (diatas 11 - 14 tahun)' => array_merge(
                ['Under = dibawah 30 Kg', 'A = 30 - 33 Kg', 'B = diatas 33 - 36 Kg', 'C = diatas 36 - 39 Kg', 'D = diatas 39 - 42 Kg', 'E = diatas 42 - 45 Kg', 'F = diatas 45 - 48 Kg', 'G = diatas 48 - 51 Kg', 'H = diatas 51 - 54 Kg', 'I = diatas 54 - 57 Kg', 'J = diatas 57 - 60 Kg', 'K = diatas 60 - 63 Kg', 'L = diatas 63 - 66 Kg', 'M = diatas 66 - 69 Kg', 'N = diatas 69 - 72 Kg', 'O = diatas 72 - 75 Kg', 'P = diatas 75 - 78 Kg', 'OPEN = diatas 78 - 84 Kg'],
                ['Tunggal Tangan Kosong', 'Tunggal Bersenjata', 'Ganda Tangan Kosong', 'Ganda Bersenjata', 'Regu A (1 - 6)', 'Regu B (7 - 12)', 'Tunggal Bebas', 'Perseorangan', 'Berpasangan', 'Berkelompok']
            ),
            'REMAJA (diatas 14 - 17 tahun)' => array_merge(
                ['Under = dibawah 39 Kg', 'A = 39 - 43 Kg', 'B = diatas 43 - 47 Kg', 'C = diatas 47 - 51 Kg', 'D = diatas 51 - 55 Kg', 'E = diatas 55 - 59 Kg', 'F = diatas 59 - 63 Kg', 'G = diatas 63 - 67 Kg', 'H = diatas 67 - 71 Kg', 'I = diatas 71 - 75 Kg', 'J = diatas 75 - 79 Kg', 'K = diatas 79 - 83 Kg', 'L = diatas 83 - 87 Kg', 'OPEN 1 = diatas 87 - 100 Kg', 'OPEN 2 = diatas 100 Kg'],
                ['Tunggal', 'Ganda', 'Regu', 'Tunggal Bebas', 'Perseorangan', 'Berpasangan', 'Berkelompok']
            ),
            'DEWASA (diatas 17 - 35 tahun)' => array_merge(
                ['Under = dibawah 45 Kg', 'A = 45 - 50 Kg', 'B = diatas 50 - 55 Kg', 'C = diatas 55 - 60 Kg', 'D = diatas 60 - 65 Kg', 'E = diatas 65 - 70 Kg', 'F = diatas 70 - 75 Kg', 'G = diatas 75 - 80 Kg', 'H = diatas 80 - 85 Kg', 'I = diatas 85 - 90 Kg', 'J = diatas 90 - 95 Kg', 'OPEN 1 = diatas 95 - 110 kg', 'OPEN 2 = diatas 100 kg'],
                ['Tunggal', 'Ganda', 'Regu', 'Tunggal Bebas', 'Perseorangan', 'Berpasangan', 'Berkelompok']
            )
        ];

        foreach ($allPossibleClasses as $usiaNama => $kelasArray) {
            if (isset($usiaIds[$usiaNama])) {
                $usiaId = $usiaIds[$usiaNama];
                foreach ($kelasArray as $namaKelas) {
                    $dataToInsert[] = ['nama_kelas' => $namaKelas, 'rentang_usia_id' => $usiaId];
                }
            }
        }
        
        // Hapus duplikat berdasarkan 'nama_kelas' dan 'rentang_usia_id'
        $uniqueData = collect($dataToInsert)->unique(function ($item) {
            return $item['nama_kelas'].$item['rentang_usia_id'];
        })->all();
        
        $finalData = [];
        foreach ($uniqueData as $data) {
            $namaKelasLower = strtolower($data['nama_kelas']);
            $jumlahPemain = 1;
            if (str_contains($namaKelasLower, 'ganda') || str_contains($namaKelasLower, 'berpasangan')) {
                $jumlahPemain = 2;
            } elseif (str_contains($namaKelasLower, 'regu') || str_contains($namaKelasLower, 'berkelompok')) {
                $jumlahPemain = 3;
            }

            $finalData[] = [
                'nama_kelas' => $data['nama_kelas'],
                'rentang_usia_id' => $data['rentang_usia_id'],
                'jumlah_pemain' => $jumlahPemain,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('kelas')->insert($finalData);
    }
}