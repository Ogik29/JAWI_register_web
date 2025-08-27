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
        // Tidak ada perubahan di sini, karena query Player::...->get() sudah mengambil semua kolom
        // termasuk 'no_telp'.
        $approvedPlayers = Player::whereHas('contingent', function ($query) {
            $query->where('event_id', $this->event->id);
        })
            ->where('status', 2)
            ->with([
                'contingent',
                'kelasPertandingan.kelas.rentangUsia',
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
        // PERUBAHAN DI SINI: Menambahkan kolom 'Event' dan 'No. Telepon'
        return [
            'No',
            'Event', // <-- KOLOM BARU
            'Kontingen',
            'Kategori Pertandingan',
            'Jenis Pertandingan',
            'Rentang Usia',
            'Kelas',
            'Gender',
            'Pemain (Nama)',
            'Pemain (Tanggal Lahir)',
            'No. Telepon', // <-- KOLOM BARU
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
        // PERUBAHAN DI SINI: Menambahkan data no_telp
        $playerPhones = $registration['players']->pluck('no_telp')->implode("\n"); // <-- DATA BARU

        // PERUBAHAN DI SINI: Menambahkan variabel baru ke array
        return [
            $this->rowNumber,
            $registration['event_name'],      // <-- KOLOM BARU
            $registration['contingent_name'],
            $registration['kategori_name'],
            $registration['jenis_name'],
            $registration['rentang_usia_name'],
            $registration['nama_kelas'],
            $registration['gender'],
            $playerNames,
            $playerBirthDates,
            $playerPhones,                  // <-- KOLOM BARU
            $playerNiks,
        ];
    }

    /**
     * Menerapkan style ke worksheet.
     */
    public function styles(Worksheet $sheet)
    {
        // PERUBAHAN DI SINI: Mengubah range dari J1 ke L1 dan J ke L
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);
        $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Mengaktifkan text wrapping
        $sheet->getStyle('I')->getAlignment()->setWrapText(true); // Nama
        $sheet->getStyle('J')->getAlignment()->setWrapText(true); // Tgl Lahir
        $sheet->getStyle('K')->getAlignment()->setWrapText(true); // NIK
        $sheet->getStyle('L')->getAlignment()->setWrapText(true); // No. Telp <-- STYLE BARU

        // Mengatur alignment vertikal ke atas untuk semua sel
        $sheet->getStyle('A:L')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
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

                // PERUBAHAN DI SINI: Menambahkan 'event_name' ke dalam data grup
                $registrations[] = [
                    'event_name'        => $this->event->name, // <-- DATA BARU
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
