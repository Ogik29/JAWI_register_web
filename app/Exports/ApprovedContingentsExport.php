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
     * Mengambil data kontingen yang akan diekspor.
     */
    public function query()
    {
        // Mengambil semua kontingen yang statusnya disetujui (status=1) dari event yang dikelola admin
        // dan melakukan Eager Loading untuk relasi yang dibutuhkan untuk perhitungan
        return Contingent::query()
            ->whereIn('event_id', $this->managedEventIds)
            ->where('status', 1)
            ->with([
                'user', // Untuk nama manajer
                'players' => function ($query) {
                    $query->where('status', 2)->with('kelasPertandingan.jenisPertandingan');
                }
            ]);
    }

    public function headings(): array
    {
        return [
            'Nama Kontingen',
            'Nama Manajer',
            'Total Atlet Terverifikasi',
            'Total Atlet Tanding',
            'Total Atlet Seni',
            'Total Atlet Jurus Baku',
        ];
    }

    public function map($contingent): array
    {
        // Hitung total atlet terverifikasi dari relasi yang sudah di-load
        $totalAtlet = $contingent->players->count();

        // Hitung total atlet berdasarkan jenis pertandingan
        $atletTanding = $contingent->players->filter(function ($player) {
            // Asumsikan nama jenis pertandingan adalah 'Tanding'
            return $player->kelasPertandingan && $player->kelasPertandingan->jenisPertandingan->nama_jenis === 'Tanding';
        })->count();

        $atletSeni = $contingent->players->filter(function ($player) {
            // Asumsikan nama jenis pertandingan adalah 'Seni'
            return $player->kelasPertandingan && $player->kelasPertandingan->jenisPertandingan->nama_jenis === 'Seni';
        })->count();

        $atletJurusBaku = $contingent->players->filter(function ($player) {
            return $player->kelasPertandingan && $player->kelasPertandingan->jenisPertandingan->nama_jenis === 'Tunggal Baku';
        })->count();

        return [
            $contingent->name,
            $contingent->user->nama_lengkap,
            $totalAtlet,
            $atletTanding,
            $atletSeni,
            $atletJurusBaku,
        ];
    }

    /**
     * Menerapkan style ke worksheet.
     */
    public function styles(Worksheet $sheet)
    {
        // Menebalkan tulisan pada baris header (baris pertama)
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
