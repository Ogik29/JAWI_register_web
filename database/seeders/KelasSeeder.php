<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Nonaktifkan sementara foreign key checks
        Schema::disableForeignKeyConstraints();

        // 2. Hapus data lama (tabel anak dulu, baru tabel induk)
        DB::table('kelas_pertandingan')->truncate();
        DB::table('kelas')->truncate();

        // 3. Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();

        // Data untuk tabel 'kelas'
        $daftarKelas = [
            // Kategori Seni (Pemasalan & Prestasi)
            'Seni Tunggal',
            'Seni Tunggal Bebas',
            'Seni Ganda',
            'Seni Regu',
            'Perorangan Jurus Paket',
            'Berpasangan Jurus Paket',
            'Berkelompok Jurus Paket',
            'Tunggal Tangan Kosong',
            'Tunggal Bersenjata',
            'Ganda Tangan Kosong',
            'Ganda Bersenjata',
            'Beregu Jurus 1-6',
            'Tunggal Bebas Tangan Kosongan',
            'Tunggal Bebas Bersenjata',
            'Berpasangan Jurus Paket SD A - SD B',
            'Berkelompok Jurus Paket TK',
            'Tunggal', // Umum untuk prestasi
            'Ganda',   // Umum untuk prestasi
            'Beregu',  // Umum untuk prestasi
            'Tunggal Bebas', // Umum untuk prestasi
            'Perorangan Jurus Paket SMA',
            'Berpasangan Jurus Paket SMP',

            // Contoh Kategori Tanding (Berdasarkan Berat Badan)
            'Kelas A 12 - 15kg',
            'Kelas B 15 - 18kg',
            'Kelas C 18 - 21kg',
            'Kelas D 21 - 24kg',
            'Kelas E 24 - 27kg',
            'Kelas F 27 - 30kg',
            'Kelas G 30 - 33kg',
            'Kelas H 33 - 36kg',
            'Kelas I 36 - 39kg',
            'Kelas J 39 - 42kg',
            'Kelas K 42 - 45kg',
            'Kelas L 45 - 48kg',
            'Kelas M 48 - 51kg',
            'Kelas N 51 - 54kg',
            'Kelas O 54 - 57kg',
            'Kelas P 57 - 60kg',
            'Kelas Q 60 - 63kg',
            'Kelas R 63 - 66kg',
            'Kelas S 66 - 69kg',
            'Kelas T 69 - 72kg',
            'Kelas U 72 - 75kg',
            'Kelas V 75 - 78kg',
            'Kelas W 78 - 81kg',
            'Kelas X 81 - 84kg',
            'Kelas Y 84 - 87kg',
            'Kelas Z 87 - 90kg',
        ];

        $kelasData = [];

        // Loop melalui daftar kelas dan siapkan data untuk dimasukkan
        foreach ($daftarKelas as $nama) {
            $kelasData[] = [
                'nama_kelas' => $nama,
                'rentang_usia_id' => 1, // <-- Menambahkan ID rentang usia
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Masukkan semua data ke database dalam satu query
        DB::table('kelas')->insert($kelasData);
    }
}