<?php

namespace App\Exports;

use App\Models\Contingent;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ApprovedContingentsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $managedEventIds;

    public function __construct(array $managedEventIds)
    {
        $this->managedEventIds = $managedEventIds;
    }

    /**
     * Mengambil data kontingen dengan relasi yang lebih detail.
     */
    public function query()
    {
        // Eager Loading diperluas untuk mencakup Kategori dan Jenis Pertandingan
        return Contingent::query()
            ->whereIn('event_id', $this->managedEventIds)
            ->where('status', 1)
            ->with([
                'user', // Nama manajer
                'players' => function ($query) {
                    // Hanya memuat pemain yang disetujui
                    $query->where('status', 2)
                        ->with('kelasPertandingan.kategoriPertandingan', 'kelasPertandingan.jenisPertandingan');
                }
            ]);
    }

    /**
     * Menentukan header baru sesuai format yang diminta.
     */
    public function headings(): array
    {
        return [
            // Kolom Dasar
            'Nama Kontingen',
            'Nama Manajer',
            'Total Atlet Terverifikasi',

            // Kolom Kategori Prestasi
            'Total Atlet PRESTASI',
            'Prestasi - Tanding',
            'Prestasi - Seni',
            'Prestasi - Jurus Baku',

            // Kolom Kategori Pemasalan
            'Total Atlet PEMASALAN',
            'Pemasalan - Tanding',
            'Pemasalan - Seni',
            'Pemasalan - Jurus Baku',
        ];
    }

    /**
     * Memetakan dan menghitung data untuk setiap baris.
     * Ini adalah bagian utama yang diubah total.
     */
    public function map($contingent): array
    {
        // 1. Dapatkan koleksi semua pemain terverifikasi untuk kontingen ini
        $players = $contingent->players;
        $totalAtlet = $players->count();

        // Filter pemain yang masuk kategori "Prestasi"
        $prestasiPlayers = $players->filter(function ($player) {
            // Asumsikan nama kategori adalah 'Prestasi'
            return $player->kelasPertandingan &&
                $player->kelasPertandingan->kategoriPertandingan &&
                $player->kelasPertandingan->kategoriPertandingan->nama_kategori === 'Prestasi';
        });

        $totalPrestasi = $prestasiPlayers->count();

        // Hitung per jenis pertandingan di dalam kategori Prestasi
        $prestasiTanding = $prestasiPlayers->filter(function ($player) {
            return $player->kelasPertandingan->jenisPertandingan->nama_jenis === 'Tanding';
        })->count();

        $prestasiSeni = $prestasiPlayers->filter(function ($player) {
            return $player->kelasPertandingan->jenisPertandingan->nama_jenis === 'Seni';
        })->count();

        $prestasiJurusBaku = $prestasiPlayers->filter(function ($player) {
            return $player->kelasPertandingan->jenisPertandingan->nama_jenis === 'Tunggal Baku';
        })->count();

        // Filter pemain yang masuk kategori "Pemasalan"
        $pemasalanPlayers = $players->filter(function ($player) {
            // Asumsikan nama kategori adalah 'Pemasalan'
            return $player->kelasPertandingan &&
                $player->kelasPertandingan->kategoriPertandingan &&
                $player->kelasPertandingan->kategoriPertandingan->nama_kategori === 'Pemasalan';
        });

        $totalPemasalan = $pemasalanPlayers->count();

        // Hitung per jenis pertandingan di dalam kategori Pemasalan
        $pemasalanTanding = $pemasalanPlayers->filter(function ($player) {
            return $player->kelasPertandingan->jenisPertandingan->nama_jenis === 'Tanding';
        })->count();

        $pemasalanSeni = $pemasalanPlayers->filter(function ($player) {
            return $player->kelasPertandingan->jenisPertandingan->nama_jenis === 'Seni';
        })->count();

        $pemasalanJurusBaku = $pemasalanPlayers->filter(function ($player) {
            return $player->kelasPertandingan->jenisPertandingan->nama_jenis === 'Tunggal Baku';
        })->count();


        // Mengembalikan array data sesuai urutan di headings()
        return [
            // Data Dasar
            $contingent->name,
            $contingent->user->nama_lengkap,
            $totalAtlet,

            // Data Prestasi
            $totalPrestasi,
            $prestasiTanding,
            $prestasiSeni,
            $prestasiJurusBaku,

            // Data Pemasalan
            $totalPemasalan,
            $pemasalanTanding,
            $pemasalanSeni,
            $pemasalanJurusBaku,
        ];
    }

    /**
     * Menerapkan style ke worksheet (opsional, untuk mempercantik).
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Menebalkan baris header
            1 => ['font' => ['bold' => true]],

            // Memberi warna latar belakang pada header kolom Kategori
            'D1:G1' => ['fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE0E0E0'], // Abu-abu muda
            ]],
            'H1:K1' => ['fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFF2F2F2'], // Abu-abu sangat muda
            ]],
        ];
    }
}
