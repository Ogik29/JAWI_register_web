<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasPertandinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Kosongkan tabel sebelum mengisi
        // DB::table('kelas_pertandingan')->delete();

        // Ambil ID dari tabel relasi
        $pemasalanId = DB::table('kategori_pertandingan')->where('nama_kategori', 'Pemasalan')->value('id');
        $prestasiId = DB::table('kategori_pertandingan')->where('nama_kategori', 'Prestasi')->value('id');
        $tandingId = DB::table('jenis_pertandingan')->where('nama_jenis', 'Tanding')->value('id');
        $seniId = DB::table('jenis_pertandingan')->where('nama_jenis', 'Seni')->value('id');

        $kelas = [];

        // =================================================================
        // KATEGORI TANDING PEMASALAN (PUTRA & PUTRI)
        // Harga: 200.000
        // =================================================================
        $tandingPemasalan = [
            // PUTRA
            ['nama_kelas' => 'A diatas 18-19kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'B diatas 19-20kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'C diatas 20-21kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'D diatas 21-22kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'E diatas 22-23kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'F diatas 23-24kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'G diatas 24-25kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'H diatas 25-26kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'I diatas 26-27kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putra'],

            // PUTRI
            ['nama_kelas' => 'A diatas 18-19kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'B diatas 19-20kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'C diatas 20-21kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'D diatas 21-22kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'E diatas 22-23kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'F diatas 23-24kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'G diatas 24-25kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'H diatas 25-26kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'I diatas 26-27kg', 'rentang_usia' => 'Usia Dini 1 5-8 Tahun', 'gender' => 'Putri'],
        ];

        foreach ($tandingPemasalan as $k) {
            $kelas[] = array_merge($k, [
                'kategori_pertandingan_id' => $pemasalanId,
                'jenis_pertandingan_id' => $tandingId,
                'harga' => 200000,
                'event_id' => 1,
            ]);
        }

        // =================================================================
        // KATEGORI TANDING PRESTASI (PUTRA & PUTRI)
        // Harga: 175.000
        // =================================================================
        $tandingPrestasiData = [
            // Putra
            ['nama_kelas' => 'A diatas 26-28kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'B diatas 28-30kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'C diatas 30-32kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'D diatas 32-34kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'E diatas 34-36kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'F diatas 36-38kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'G diatas 38-40kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'H diatas 40-42kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'I diatas 42-44kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'A diatas 30-33kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'B diatas 33-36kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'C diatas 36-39kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'D diatas 39-42kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'E diatas 42-45kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'F diatas 45-48kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'G diatas 48-51kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'H diatas 51-54kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'I diatas 54-57kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'A diatas 39-43kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'B diatas 43-47kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'C diatas 47-51kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'D diatas 51-55kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'E diatas 55-59kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'F diatas 59-63kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'G diatas 63-67kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'H diatas 67-71kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'I diatas 71-75kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'A diatas 45-50kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'B diatas 50-55kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'C diatas 55-60kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'D diatas 60-65kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'E diatas 65-70kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'F diatas 70-75kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'G diatas 75-80kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'H diatas 80-85kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'I diatas 85-90kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putra'],
            ['nama_kelas' => 'J diatas 90-95kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putra'],

            // Putri
            ['nama_kelas' => 'A diatas 26-28kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'B diatas 28-30kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'C diatas 30-32kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'D diatas 32-34kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'E diatas 34-36kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'F diatas 36-38kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'G diatas 38-40kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'H diatas 40-42kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'I diatas 42-44kg', 'rentang_usia' => 'Usia Dini 2 8-11 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'A diatas 30-33kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'B diatas 33-36kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'C diatas 36-39kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'D diatas 39-42kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'E diatas 42-45kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'F diatas 45-48kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'G diatas 48-51kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'H diatas 51-54kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'I diatas 54-57kg', 'rentang_usia' => 'Pra Remaja 11-14 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'A diatas 39-43kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'B diatas 43-47kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'C diatas 47-51kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'D diatas 51-55kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'E diatas 55-59kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'F diatas 59-63kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'G diatas 63-67kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'H diatas 67-71kg', 'rentang_usia' => 'Remaja 13-17 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'A diatas 45-50kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'B diatas 50-55kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'C diatas 55-60kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'D diatas 60-65kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'E diatas 65-70kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putri'],
            ['nama_kelas' => 'F diatas 70-75kg', 'rentang_usia' => 'Dewasa 17-23 Tahun', 'gender' => 'Putri'],
        ];

        foreach ($tandingPrestasiData as $k) {
            $kelas[] = array_merge($k, [
                'kategori_pertandingan_id' => $prestasiId,
                'jenis_pertandingan_id' => $tandingId,
                'harga' => 175000,
                'event_id' => 1,
            ]);
        }

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
            // Data dari tabel gambar "Kelas Seni Pemasalan"
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

        $usiaSeniPemasalan = ['Usia Dini 1 5-8 Tahun', 'Usia Dini 2 8-11 Tahun', 'Pra Remaja 11-14 Tahun'];

        foreach ($seniPemasalanData as $k) {
            foreach ($usiaSeniPemasalan as $usia) {
                $kelas[] = [
                    'nama_kelas' => $k['nama_kelas'],
                    'harga' => $k['harga'],
                    'rentang_usia' => $usia,
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
            // Data dari tabel gambar "Kelas Seni Prestasi"
            ['nama_kelas' => 'Tunggal', 'harga' => 175000],
            ['nama_kelas' => 'Ganda', 'harga' => 350000],
            ['nama_kelas' => 'Beregu', 'harga' => 525000],
            ['nama_kelas' => 'Tunggal Bebas', 'harga' => 175000],
            ['nama_kelas' => 'Perorangan Jurus Paket SMA', 'harga' => 175000],
            ['nama_kelas' => 'Berpasangan Jurus Paket SMP', 'harga' => 350000],
            ['nama_kelas' => 'Berkelompok Jurus Paket TK', 'harga' => 525000],
        ];

        $usiaSeniPrestasi = ['Remaja 13-17 Tahun', 'Dewasa 17-23 Tahun'];

        foreach ($seniPrestasiData as $k) {
            foreach ($usiaSeniPrestasi as $usia) {
                $kelas[] = [
                    'nama_kelas' => $k['nama_kelas'],
                    'harga' => $k['harga'],
                    'rentang_usia' => $usia,
                    'kategori_pertandingan_id' => $prestasiId,
                    'jenis_pertandingan_id' => $seniId,
                    'gender' => null,
                    'event_id' => 1,
                ];
            }
        }

        // Masukkan semua data ke database
        DB::table('kelas_pertandingan')->insert($kelas);
    }
}
