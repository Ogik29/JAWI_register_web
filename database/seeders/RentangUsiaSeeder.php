<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // <-- Tambahkan ini

class RentangUsiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Nonaktifkan sementara foreign key checks untuk mengizinkan truncate
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan tabel (tabel anak dulu, baru tabel induk)
        // DB::table('rentang_usia_event')->truncate();
        DB::table('rentang_usia')->truncate();

        // 3. Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();

        // 4. Siapkan data untuk tabel 'rentang_usia'
        $daftarRentangUsia = [
            'Usia Dini 1 (5-8 Tahun)',
            'Usia Dini 2 (8-11 Tahun)',
            'Pra Remaja (11-14 Tahun)',
            'Remaja (13-17 Tahun)',
            'Dewasa (17-23 Tahun)',
        ];

        $dataUntukInsert = [];
        foreach ($daftarRentangUsia as $rentang) {
            $dataUntukInsert[] = [
                'rentang_usia' => $rentang,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Masukkan data ke tabel 'rentang_usia'
        DB::table('rentang_usia')->insert($dataUntukInsert);
        
        // // 5. Siapkan data untuk tabel pivot 'rentang_usia_event'

        // // Ambil semua ID yang baru saja dibuat dari tabel 'rentang_usia'
        // $rentangUsiaIds = DB::table('rentang_usia')->pluck('id');

        // $pivotData = [];
        // foreach ($rentangUsiaIds as $usiaId) {
        //     $pivotData[] = [
        //         // 'event_id' => 1, // Sesuai permintaan, set event_id ke 1
        //         'rentang_usia_id' => $usiaId,
        //     ];
        // }

        // // Masukkan data relasi ke tabel pivot 'rentang_usia_event'
        // DB::table('rentang_usia_event')->insert($pivotData);
    }
}