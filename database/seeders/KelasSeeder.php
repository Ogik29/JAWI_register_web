<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menonaktifkan constraint foreign key untuk proses truncate
        Schema::disableForeignKeyConstraints();
        DB::table('kelas_pertandingan')->truncate(); // Truncate tabel pivot/relasi dulu jika ada
        DB::table('kelas')->truncate();
        Schema::enableForeignKeyConstraints();

        // Data kelas yang disesuaikan dengan ID dari screenshot tabel rentang_usia
        $allPossibleClasses = [
            // ID 1 -> 'Pra Usia Dini (<= 5 tahun)'
            1 => ['Tunggal Bebas', 'Berkelompok'],

            // ID 2 -> 'Usia Dini 1 (> 5 s.d 8 tahun)'
            2 => [
                'A = 18 - 19 KG',
                'B = diatas 19 - 20 kg',
                'C = diatas 20 - 21 kg',
                'D = diatas 21 - 22 kg',
                'E = diatas 22 - 23 kg',
                'F = diatas 23 - 24 kg',
                'G = diatas 24 - 25 kg',
                'H = diatas 25 - 26 kg',
                'I = diatas 26 - 27 kg',
                'J = diatas 27 - 28 Kg',
                'Open = diatas 28 - 29 kg',
                'Tunggal Tangan Kosong',
                'Tunggal Bersenjata',
                'Ganda Tangan Kosong',
                'Ganda Bersenjata',
                'Regu A (1 - 6)',
                'Regu B (7 - 12)',
                'Tunggal Bebas',
                'Perseorangan',
                'Berpasangan',
                'Berkelompok'
            ],

            // ID 3 -> 'Usia Dini 2 (> 8 s.d 11 tahun)'
            3 => [
                'A = diatas 26 - 28 kg',
                'B = diatas 28 - 30 kg',
                'C = diatas 30 - 32 kg',
                'D = diatas 32 - 34 kg',
                'E = diatas 34 - 36 kg',
                'F = diatas 36 - 38 kg',
                'G = diatas 38 - 40 kg',
                'H = diatas 40 - 42 kg',
                'I = diatas 42 - 44 kg',
                'J = diatas 44 - 46 kg',
                'K = diatas 46 - 48 kg',
                'L = diatas 48 - 50 kg',
                'M = diatas 50 - 52 kg',
                'N = diatas 52 - 54 kg',
                'O = diatas 54 - 56 kg',
                'P = diatas 56 - 58 kg',
                'Q = diatas 58 - 60 kg',
                'R = diatas 60 - 62 kg',
                'S = diatas 62 - 64 kg',
                'OPEN = diatas 64 - 68 kg',
                'Tunggal Tangan Kosong',
                'Tunggal Bersenjata',
                'Ganda Tangan Kosong',
                'Ganda Bersenjata',
                'Regu A (1 - 6)',
                'Regu B (7 - 12)',
                'Tunggal Bebas',
                'Perseorangan',
                'Berpasangan',
                'Berkelompok'
            ],

            // ID 4 -> 'Pra Remaja (> 11 s.d 14 tahun)'
            4 => [
                'Under = dibawah 30 Kg',
                'A = 30 - 33 Kg',
                'B = diatas 33 - 36 Kg',
                'C = diatas 36 - 39 Kg',
                'D = diatas 39 - 42 Kg',
                'E = diatas 42 - 45 Kg',
                'F = diatas 45 - 48 Kg',
                'G = diatas 48 - 51 Kg',
                'H = diatas 51 - 54 Kg',
                'I = diatas 54 - 57 Kg',
                'J = diatas 57 - 60 Kg',
                'K = diatas 60 - 63 Kg',
                'L = diatas 63 - 66 Kg',
                'M = diatas 66 - 69 Kg',
                'N = diatas 69 - 72 Kg',
                'O = diatas 72 - 75 Kg',
                'P = diatas 75 - 78 Kg',
                'OPEN = diatas 78 - 84 Kg',
                'Tunggal Tangan Kosong',
                'Tunggal Bersenjata',
                'Ganda Tangan Kosong',
                'Ganda Bersenjata',
                'Regu A (1 - 6)',
                'Regu B (7 - 12)',
                'Tunggal Bebas',
                'Perseorangan',
                'Berpasangan',
                'Berkelompok'
            ],

            // ID 5 -> 'Remaja (> 14 s.d 17 tahun)'
            5 => [
                'Under = dibawah 39 Kg',
                'A = 39 - 43 Kg',
                'B = diatas 43 - 47 Kg',
                'C = diatas 47 - 51 Kg',
                'D = diatas 51 - 55 Kg',
                'E = diatas 55 - 59 Kg',
                'F = diatas 59 - 63 Kg',
                'G = diatas 63 - 67 Kg',
                'H = diatas 67 - 71 Kg',
                'I = diatas 71 - 75 Kg',
                'J = diatas 75 - 79 Kg',
                'K = diatas 79 - 83 Kg',
                'L = diatas 83 - 87 Kg',
                'OPEN 1 = diatas 87 - 100 Kg',
                'OPEN 2 = diatas 100 Kg',
                'Tunggal',
                'Ganda',
                'Regu',
                'Tunggal Bebas',
                'Perseorangan',
                'Berpasangan',
                'Berkelompok'
            ],

            // ID 6 -> 'Dewasa (> 17 s.d 35 tahun)'
            6 => [
                'Under = dibawah 45 Kg',
                'A = 45 - 50 Kg',
                'B = diatas 50 - 55 Kg',
                'C = diatas 55 - 60 Kg',
                'D = diatas 60 - 65 Kg',
                'E = diatas 65 - 70 Kg',
                'F = diatas 70 - 75 Kg',
                'G = diatas 75 - 80 Kg',
                'H = diatas 80 - 85 Kg',
                'I = diatas 85 - 90 Kg',
                'J = diatas 90 - 95 Kg',
                'OPEN 1 = diatas 95 - 110 kg',
                'OPEN 2 = diatas 100 kg',
                'Tunggal',
                'Ganda',
                'Regu',
                'Tunggal Bebas',
                'Perseorangan',
                'Berpasangan',
                'Berkelompok'
            ]
        ];

        $dataToInsert = [];

        foreach ($allPossibleClasses as $usiaId => $kelasArray) {
            foreach ($kelasArray as $namaKelas) {
                // Langsung masukkan ID rentang usia
                $dataToInsert[] = ['nama_kelas' => $namaKelas, 'rentang_usia_id' => $usiaId];
            }
        }

        // Hapus duplikat berdasarkan 'nama_kelas' dan 'rentang_usia_id' yang sama
        $uniqueData = collect($dataToInsert)->unique(function ($item) {
            return $item['nama_kelas'] . $item['rentang_usia_id'];
        })->values()->all();

        $finalData = [];
        $now = Carbon::now();

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
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Masukkan data ke dalam database
        DB::table('kelas')->insert($finalData);
    }
}
