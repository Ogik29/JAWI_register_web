<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Player;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ApprovedParticipantsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $event;
    private $rowNumber = 0;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $approvedPlayers = Player::whereHas('contingent', function ($query) {
            $query->where('event_id', $this->event->id);
        })
        ->where('status', 2) // 2 = Terverifikasi/Disetujui
        ->with([
            'contingent',
            'kelasPertandingan.kelas.rentangUsia', // <-- Muat relasi rentangUsia
            'kelasPertandingan.kategoriPertandingan',
            'kelasPertandingan.jenisPertandingan'
        ])
        ->get();
        
        return $this->groupPlayersByRegistration($approvedPlayers);
    }

    /**
     * Menentukan judul kolom di file Excel.
     */
    public function headings(): array
    {
        return [
            'No',
            'Kontingen',
            'Kategori Pertandingan', // Prestasi / Pemasalan
            'Jenis Pertandingan',   // Tanding / Seni / Jurus Baku
            'Rentang Usia',
            'Kelas',
            'Gender',
            'Pemain (Nama)',
            'Pemain (Tanggal Lahir)',
            'Pemain (NIK)',
        ];
    }

    /**
     * Memetakan data dari setiap item di collection ke baris Excel.
     */
    public function map($registration): array
    {
        $this->rowNumber++;

        // Ubah data pemain menjadi string yang dipisahkan baris baru
        $playerNames = $registration['players']->pluck('name')->implode("\n");
        $playerBirthDates = $registration['players']->pluck('tgl_lahir')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('d F Y');
        })->implode("\n");
        $playerNiks = $registration['players']->pluck('nik')->implode("\n");

        return [
            $this->rowNumber,
            $registration['contingent_name'],
            $registration['kategori_name'],
            $registration['jenis_name'],
            $registration['rentang_usia_name'], // Kolom baru
            $registration['nama_kelas'],
            $registration['gender'],
            $playerNames,
            $playerBirthDates, // Kolom baru
            $playerNiks,       // Kolom baru
        ];
    }
    
    /**
     * Menerapkan style ke worksheet.
     * Membuat text-wrap aktif untuk kolom pemain.
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Mengaktifkan text wrapping untuk kolom yang berisi banyak pemain
        // agar tidak tumpah ke sel lain.
        $sheet->getStyle('H')->getAlignment()->setWrapText(true);
        $sheet->getStyle('I')->getAlignment()->setWrapText(true);
        $sheet->getStyle('J')->getAlignment()->setWrapText(true);
        
        // Mengatur alignment vertikal ke atas untuk semua sel
        $sheet->getStyle('A:J')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
    }


    /**
     * Fungsi helper untuk mengelompokkan pemain.
     */
    private function groupPlayersByRegistration(Collection $players): Collection
    {
        $registrations = [];
        $playersByClass = $players->groupBy('kelas_pertandingan_id');

        foreach ($playersByClass as $kelasPertandinganId => $playersInGroup) {
            $firstPlayer = $playersInGroup->first();
            if (!$firstPlayer || !$firstPlayer->kelasPertandingan || !$firstPlayer->kelasPertandingan->kelas) {
                continue;
            }

            $classDetails = $firstPlayer->kelasPertandingan;
            $pemainPerPendaftaran = $classDetails->kelas->jumlah_pemain ?: 1;
            $jumlahPemainTotal = $playersInGroup->count();
            $jumlahPendaftaran = ceil($jumlahPemainTotal / $pemainPerPendaftaran);
            
            $allPlayers = $playersInGroup->values()->all();

            for ($i = 0; $i < $jumlahPendaftaran; $i++) {
                $offset = $i * $pemainPerPendaftaran;
                $pemainUntukItemIni = array_slice($allPlayers, $offset, $pemainPerPendaftaran);
                if (empty($pemainUntukItemIni)) continue;

                $registrations[] = [
                    'contingent_name'   => $firstPlayer->contingent->name,
                    'kategori_name'     => $classDetails->kategoriPertandingan->nama_kategori,
                    'jenis_name'        => $classDetails->jenisPertandingan->nama_jenis,
                    'rentang_usia_name' => $classDetails->kelas->rentangUsia->rentang_usia,
                    'nama_kelas'        => $classDetails->kelas->nama_kelas,
                    'gender'            => $classDetails->gender,
                    'players'           => collect($pemainUntukItemIni)
                ];
            }
        }
        return new Collection($registrations);
    }
}