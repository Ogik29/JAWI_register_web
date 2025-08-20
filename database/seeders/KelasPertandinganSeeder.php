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
        // 1. Matikan foreign key check & kosongkan tabel
        Schema::disableForeignKeyConstraints();
        DB::table('kelas_pertandingan')->truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Ambil semua data master yang diperlukan dalam beberapa query saja
        $pemasalanId = DB::table('kategori_pertandingan')->where('nama_kategori', 'Pemasalan')->value('id');
        $prestasiId = DB::table('kategori_pertandingan')->where('nama_kategori', 'Prestasi')->value('id');
        $seniId = DB::table('jenis_pertandingan')->where('nama_jenis', 'Seni')->value('id');
        
        // Ambil SEMUA data kelas SATU KALI saja dan jadikan collection yang mudah dicari
        // Key-nya adalah 'nama_kelas', value-nya adalah object kelas
        $semuaKelas = DB::table('kelas')->get()->keyBy('nama_kelas');

        $dataUntukInsert = [];

        // =================================================================
        // KATEGORI SENI PEMASALAN
        // =================================================================
        $seniPemasalanData = [
            ['nama_kelas' => 'Seni Tunggal', 'harga' => 200000],
            ['nama_kelas' => 'Seni Tunggal Bebas', 'harga' => 200000],
            ['nama_kelas' => 'Seni Ganda', 'harga' => 400000],
            ['nama_kelas' => 'Seni Regu', 'harga' => 600000],
            ['nama_kelas' => 'Perorangan Jurus Paket', 'harga' => 200000],
            ['nama_kelas' => 'Berpasangan Jurus Paket', 'harga' => 400000],
            ['nama_kelas' => 'Berkelompok Jurus Paket', 'harga' => 600000],
            ['nama_kelas' => 'Tunggal Tangan Kosong', 'harga' => 200000],
            ['nama_kelas' => 'Tunggal Bersenjata', 'harga' => 200000],
            ['nama_kelas' => 'Ganda Tangan Kosong', 'harga' => 400000],
            ['nama_kelas' => 'Ganda Bersenjata', 'harga' => 400000],
            ['nama_kelas' => 'Beregu Jurus 1-6', 'harga' => 600000],
            ['nama_kelas' => 'Tunggal Bebas Tangan Kosongan', 'harga' => 200000],
            ['nama_kelas' => 'Tunggal Bebas Bersenjata', 'harga' => 200000],
            ['nama_kelas' => 'Berpasangan Jurus Paket SD A - SD B', 'harga' => 400000],
            ['nama_kelas' => 'Berkelompok Jurus Paket TK', 'harga' => 600000],
        ];

        foreach ($seniPemasalanData as $k) {
            // Cek apakah 'nama_kelas' ada di data yang sudah kita ambil
            if (isset($semuaKelas[$k['nama_kelas']])) {
                // Jika ada, ambil ID-nya. Tidak perlu query ke DB lagi.
                $kelasId = $semuaKelas[$k['nama_kelas']]->id;
                
                $dataUntukInsert[] = [
                    'kelas_id' => $kelasId,
                    'harga' => $k['harga'],
                    'kategori_pertandingan_id' => $pemasalanId,
                    'jenis_pertandingan_id' => $seniId,
                    'gender' => null,
                    'event_id' => 1,
                ];
            }
        }

        // =================================================================
        // KATEGORI SENI PRESTASI
        // =================================================================
        $seniPrestasiData = [
            ['nama_kelas' => 'Seni Tunggal', 'harga' => 175000],
            ['nama_kelas' => 'Seni Tunggal Bebas', 'harga' => 175000],
            ['nama_kelas' => 'Seni Ganda', 'harga' => 350000],
            ['nama_kelas' => 'Seni Regu', 'harga' => 525000],
            ['nama_kelas' => 'Perorangan Jurus Paket', 'harga' => 175000],
            ['nama_kelas' => 'Berpasangan Jurus Paket', 'harga' => 350000],
            ['nama_kelas' => 'Berkelompok Jurus Paket', 'harga' => 525000],
            ['nama_kelas' => 'Tunggal', 'harga' => 175000],
            ['nama_kelas' => 'Ganda', 'harga' => 350000],
            ['nama_kelas' => 'Beregu', 'harga' => 525000],
            ['nama_kelas' => 'Tunggal Bebas', 'harga' => 175000],
            ['nama_kelas' => 'Perorangan Jurus Paket SMA', 'harga' => 175000],
            ['nama_kelas' => 'Berpasangan Jurus Paket SMP', 'harga' => 350000],
            ['nama_kelas' => 'Berkelompok Jurus Paket TK', 'harga' => 525000],
        ];

        foreach ($seniPrestasiData as $k) {
            // Lakukan pengecekan yang sama
            if (isset($semuaKelas[$k['nama_kelas']])) {
                $kelasId = $semuaKelas[$k['nama_kelas']]->id;
                
                $dataUntukInsert[] = [
                    'kelas_id' => $kelasId,
                    'harga' => $k['harga'],
                    'kategori_pertandingan_id' => $prestasiId,
                    'jenis_pertandingan_id' => $seniId,
                    'gender' => null,
                    'event_id' => 1,
                ];
            }
        }

        // 3. Masukkan semua data yang terkumpul ke database dalam SATU QUERY
        if (!empty($dataUntukInsert)) {
            DB::table('kelas_pertandingan')->insert($dataUntukInsert);
        }
    }
}