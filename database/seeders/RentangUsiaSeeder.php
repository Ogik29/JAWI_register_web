<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RentangUsiaSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('rentang_usia')->truncate();
        Schema::enableForeignKeyConstraints();

        $daftarRentangUsia = [
            // Sesuai dengan gambar terbaru
            ['rentang_usia' => 'Pra Usia Dini (<= 5 tahun)'],
            ['rentang_usia' => 'Usia Dini 1 (> 5 s.d 8 tahun)'],
            ['rentang_usia' => 'Usia Dini 2 (> 8 s.d 11 tahun)'],
            ['rentang_usia' => 'Pra Remaja (> 11 s.d 14 tahun)'],
            ['rentang_usia' => 'Remaja (> 14 s.d 17 tahun)'],
            ['rentang_usia' => 'Dewasa (> 17 s.d 35 tahun)'],
            ['rentang_usia' => 'Master A (> 35 s.d 45 tahun)'],
            ['rentang_usia' => 'Master B (> 45 tahun ke atas)'],
        ];

        // Tambahkan timestamp
        foreach ($daftarRentangUsia as &$data) {
            $data['created_at'] = now();
            $data['updated_at'] = now();
        }

        DB::table('rentang_usia')->insert($daftarRentangUsia);
    }
}