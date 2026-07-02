<?php

namespace App\Exports;

use App\Models\Pertandingan;
use App\Models\KelasPertandingan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PertandinganExportAll implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $kelas;

    public function __construct(KelasPertandingan $kelas)
    {
        $this->kelas = $kelas;
    }

    /**
     * Mengambil SEMUA data pertandingan termasuk yang kosong (tidak ada filter whereNotNull).
     */
    public function collection()
    {
        // Ambil SEMUA pertandingan tanpa filter unit
        return Pertandingan::where('kelas_pertandingan_id', $this->kelas->id)
            ->with(['arena']) // Eager load arena
            ->orderBy('round_number')
            ->orderBy('match_number')
            ->get();
    }

    /**
     * Mendefinisikan header untuk kolom-kolom tabel.
     */
    public function headings(): array
    {
        return [
            'Partai',
            'Kategori',
            'Jenis Pertandingan',
            'Kelas',
            'Unit 1 (Tim Biru)',
            'Kontingen Unit 1',
            'Unit 2 (Tim Merah)',
            'Kontingen Unit 2',
            'Arena',
            'Next Match ID',
        ];
    }

    /**
     * Memetakan setiap baris data pertandingan ke format Excel.
     * @param Pertandingan $pertandingan
     */
    public function map($pertandingan): array
    {
        // Ambil data pemain menggunakan accessor
        $pemainUnit1 = $pertandingan->pemain_unit_1;
        $pemainUnit2 = $pertandingan->pemain_unit_2;

        // Nama pemain Unit 1 (jika ada)
        $namaUnit1 = $pemainUnit1->isNotEmpty()
            ? $pemainUnit1->map(fn($p) => $p->player->name)->implode(', ')
            : '-';

        // Kontingen Unit 1 (jika ada)
        $kontingenUnit1 = $pemainUnit1->first()?->player?->contingent?->name ?? '-';

        // Nama pemain Unit 2 (jika ada)
        $namaUnit2 = $pemainUnit2->isNotEmpty()
            ? $pemainUnit2->map(fn($p) => $p->player->name)->implode(', ')
            : '-';

        // Kontingen Unit 2 (jika ada)
        $kontingenUnit2 = $pemainUnit2->first()?->player?->contingent?->name ?? '-';

        return [
            $pertandingan->id, // Partai = ID pertandingan
            $this->kelas->kategoriPertandingan->nama_kategori,
            $this->kelas->jenisPertandingan->nama_jenis,
            $this->kelas->kelas->nama_kelas,
            $namaUnit1,
            $kontingenUnit1,
            $namaUnit2,
            $kontingenUnit2,
            $pertandingan->arena?->arena_name ?? 'Belum Ditentukan',
            $pertandingan->next_match_id ?? '-',
        ];
    }

    /**
     * Menerapkan styling pada sheet Excel.
     */
    public function styles(Worksheet $sheet)
    {
        // Beri style pada baris header utama (baris 1)
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => '4F81BD'],
                ],
            ],
        ];
    }
}
