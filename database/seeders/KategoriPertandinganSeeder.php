<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriPertandinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Kosongkan tabel sebelum mengisi
        // DB::table('kategori_pertandingan')->delete();

        DB::table('kategori_pertandingan')->insert([
            ['nama_kategori' => 'Pemasalan'],
            ['nama_kategori' => 'Prestasi'],
        ]);
    }
}
