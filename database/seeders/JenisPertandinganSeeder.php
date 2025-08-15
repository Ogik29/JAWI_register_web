<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPertandinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Kosongkan tabel sebelum mengisi
        // DB::table('jenis_pertandingan')->delete();

        DB::table('jenis_pertandingan')->insert([
            ['nama_jenis' => 'Tanding'],
            ['nama_jenis' => 'Seni'],
        ]);
    }
}
