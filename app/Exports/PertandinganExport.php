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

class PertandinganExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $kelas;

    public function __construct(KelasPertandingan $kelas)
    {
        $this->kelas = $kelas;
    }

    /**
    * [DIPERBARUI] Mengambil data HANYA pertandingan yang sudah memiliki unit.
    */
    public function collection()
    {
        // Kueri sekarang memiliki filter `whereNotNull`
        return Pertandingan::where('kelas_pertandingan_id', $this->kelas->id)
            ->where(function ($query) {
                // Kondisi: ambil jika unit1_id TIDAK KOSONG ATAU unit2_id TIDAK KOSONG
                $query->whereNotNull('unit1_id')
                      ->orWhereNotNull('unit2_id');
            })
            ->with(['arena']) // Muat relasi yang benar saja
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
            'Partai', // Tambahkan ID untuk referensi
            'Kategori',
            'Jenis Pertandingan',
            'Kelas',
            'Gender',
            'Unit 1 (Tim Biru)',
            'Kontingen Unit 1',
            'Unit 2 (Tim Merah)',
            'Kontingen Unit 2',
            'Arena',
        ];
    }

    /**
    * [DIPERBARUI] Memetakan setiap baris data. Logika header babak dihapus.
    * @param Pertandingan $pertandingan
    */
    public function map($pertandingan): array
    {
        // Panggil data pemain menggunakan sintaks Accessor (snake_case)
        $pemainUnit1 = $pertandingan->pemain_unit_1;
        $pemainUnit2 = $pertandingan->pemain_unit_2;

        return [
            '',
            $this->kelas->kategoriPertandingan->nama_kategori,
            $this->kelas->jenisPertandingan->nama_jenis,
            $this->kelas->kelas->nama_kelas,
            $this->kelas->gender,
            // Gabungkan nama pemain jika unit berisi lebih dari satu
            $pemainUnit1->map(fn($p) => $p->player->name)->implode(', '),
            // Ambil nama kontingen dari pemain pertama
            $pemainUnit1->first()?->player?->contingent?->name ?? '-',
            // Lakukan hal yang sama untuk unit 2
            $pemainUnit2->map(fn($p) => $p->player->name)->implode(', '),
            $pemainUnit2->first()?->player?->contingent?->name ?? '-',
            $pertandingan->arena?->arena_name ?? 'Belum Ditentukan',
        ];
    }

    /**
     * [DIPERBARUI] Menerapkan styling sederhana pada sheet Excel. Logika header babak dihapus.
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