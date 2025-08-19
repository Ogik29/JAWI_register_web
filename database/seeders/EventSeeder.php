<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Import class Str untuk membuat slug
use Carbon\Carbon; // Import class Carbon untuk manajemen tanggal

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Hapus data lama untuk menghindari duplikasi saat seeding ulang
        // DB::table('events')->truncate();

        DB::table('events')->insert([
            [
                'name' => 'Kejuaraan Pencak Silat Jakarta Open 2025',
                'slug' => Str::slug('Kejuaraan Pencak Silat Jakarta Open 2025'),
                'image' => 'poster-kejurcab-pagarnusa.jpg', // Contoh path gambar
                'desc' => 'Sebuah kejuaraan pencak silat tingkat nasional yang diselenggarakan di ibu kota. Ajang ini menjadi tolak ukur bagi para pesilat muda untuk unjuk gigi dan meraih prestasi di tingkat yang lebih tinggi.',
                'type' => 'kerjasama',
                'month' => 'Oktober',
                'harga_contingent' => 250000,
                'harga_peserta' => 150000,
                'kotaOrKabupaten' => 'DKI Jakarta',
                'lokasi' => 'GOR Ciracas, Jakarta Timur',
                'tgl_mulai_tanding' => Carbon::create(2025, 10, 20),
                'tgl_selesai_tanding' => Carbon::create(2025, 10, 23),
                'tgl_batas_pendaftaran' => Carbon::create(2025, 10, 1),
                'status' => 1,
                'cp' => '08282828339 AN.Budi <br> 08737372819 AN.Gopal',
                'juknis' => 'https://drive.google.com/drive/folders/1q-vAkN3uUt6wMcYnMBY5y3kCS28_yezF',
                'total_hadiah' => 2000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Festival Silat Tradisional Yogyakarta 2025',
                'slug' => Str::slug('Festival Silat Tradisional Yogyakarta 2025'),
                'image' => 'event-1.jpg', // Contoh path gambar
                'desc' => 'Festival ini bertujuan untuk melestarikan dan memperkenalkan kekayaan aliran pencak silat tradisional dari seluruh nusantara. Tidak hanya kompetisi, acara ini juga diisi dengan workshop dan pertunjukan budaya.',
                'type' => 'official',
                'month' => 'November',
                'harga_contingent' => 100000,
                'harga_peserta' => 75000,
                'kotaOrKabupaten' => 'Yogyakarta',
                'lokasi' => 'Alun-Alun Kidul, Yogyakarta',
                'tgl_mulai_tanding' => Carbon::create(2025, 11, 15),
                'tgl_selesai_tanding' => Carbon::create(2025, 11, 16),
                'tgl_batas_pendaftaran' => Carbon::create(2025, 11, 1),
                'status' => 0,
                'cp' => '08282828339 AN.Budi <br> 08737372819 AN.Gopal',
                'juknis' => 'https://drive.google.com/drive/folders/1q-vAkN3uUt6wMcYnMBY5y3kCS28_yezF',
                'total_hadiah' => 20000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Festival Silat Tradisional Yogyakarta 2026',
                'slug' => Str::slug('Festival Silat Tradisional Yogyakarta 2025'),
                'image' => 'event-1.jpg', // Contoh path gambar
                'desc' => 'Festival ini bertujuan untuk melestarikan dan memperkenalkan kekayaan aliran pencak silat tradisional dari seluruh nusantara. Tidak hanya kompetisi, acara ini juga diisi dengan workshop dan pertunjukan budaya.',
                'type' => 'official',
                'month' => 'November',
                'harga_contingent' => 100000,
                'harga_peserta' => 75000,
                'kotaOrKabupaten' => 'Yogyakarta',
                'lokasi' => 'Alun-Alun Kidul, Yogyakarta',
                'tgl_mulai_tanding' => Carbon::create(2025, 11, 15),
                'tgl_selesai_tanding' => Carbon::create(2025, 11, 16),
                'tgl_batas_pendaftaran' => Carbon::create(2025, 11, 1),
                'status' => 2,
                'cp' => '08282828339 AN.Budi <br> 08737372819 AN.Gopal',
                'juknis' => 'https://drive.google.com/drive/folders/1q-vAkN3uUt6wMcYnMBY5y3kCS28_yezF',
                'total_hadiah' => 20000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
