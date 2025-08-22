<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RentangUsiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menangani Foreign Key Constraint
        Schema::disableForeignKeyConstraints();
        DB::table('rentang_usia')->truncate();
        Schema::enableForeignKeyConstraints();

        $daftarRentangUsia = [
            ['rentang_usia' => 'Pra Usia Dini (Dibawah 5 Tahun)'], // <-- DATA BARU
            ['rentang_usia' => 'Usia Dini 1 (5-8 Tahun)'],
            ['rentang_usia' => 'Usia Dini 2 (8-11 Tahun)'],
            ['rentang_usia' => 'Pra Remaja (11-14 Tahun)'],
            ['rentang_usia' => 'Remaja (13-17 Tahun)'],
            ['rentang_usia' => 'Dewasa (17-23 Tahun)'],
        ];

        // Tambahkan timestamp
        foreach ($daftarRentangUsia as &$data) {
            $data['created_at'] = now();
            $data['updated_at'] = now();
        }

        DB::table('rentang_usia')->insert($daftarRentangUsia);
    }
}