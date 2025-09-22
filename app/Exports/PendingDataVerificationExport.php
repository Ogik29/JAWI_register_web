<?php

namespace App\Exports;

use App\Models\Player;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class PendingDataVerificationExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $players;
    private $rowNumber = 0;

    // Konstruktor menerima Collection pemain yang sudah difilter dari Controller
    public function __construct(Collection $players)
    {
        $this->players = $players;
    }

    /**
     * Langsung menggunakan collection yang diberikan dari Controller.
     */
    public function collection()
    {
        // Panggil fungsi grouping untuk memproses data
        return $this->groupPlayersByRegistration($this->players);
    }

    /**
     * Menentukan judul kolom di file Excel.
     */
    public function headings(): array
    {
        // Formatnya sama persis dengan yang approved
        return [
            'No',
            'Event',
            'Kontingen',
            'Kategori Pertandingan',
            'Jenis Pertandingan',
            'Rentang Usia',
            'Kelas',
            'Gender',
            'Pemain (Nama)',
            'Pemain (Tanggal Lahir)',
            'No. Telepon',
            'Pemain (NIK)',
        ];
    }

    /**
     * Memetakan data dari setiap item di collection ke baris Excel.
     */
    public function map($registration): array
    {
        $this->rowNumber++;

        $playerNames = $registration['players']->pluck('name')->implode("\n");
        $playerBirthDates = $registration['players']->pluck('tgl_lahir')->map(function ($date) {
            return Carbon::parse($date)->format('d F Y');
        })->implode("\n");
        $playerNiks = $registration['players']->pluck('nik')->implode("\n");
        $playerPhones = $registration['players']->pluck('no_telp')->implode("\n");

        return [
            $this->rowNumber,
            $registration['event_name'],
            $registration['contingent_name'],
            $registration['kategori_name'],
            $registration['jenis_name'],
            $registration['rentang_usia_name'],
            $registration['nama_kelas'],
            $registration['gender'],
            $playerNames,
            $playerBirthDates,
            $playerPhones,
            $playerNiks,
        ];
    }

    /**
     * Menerapkan style ke worksheet.
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);
        $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I:L')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:L')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
    }

    /**
     * Fungsi helper untuk mengelompokkan pemain, sama persis seperti di ApprovedParticipantsExport.
     */
    private function groupPlayersByRegistration(Collection $players): Collection
    {
        $registrations = [];
        $playersByContingent = $players->groupBy('contingent_id');

        foreach ($playersByContingent as $contingentId => $playersInContingent) {
            $playersByClass = $playersInContingent->groupBy('kelas_pertandingan_id');

            foreach ($playersByClass as $kelasPertandinganId => $playersInGroup) {
                $firstPlayer = $playersInGroup->first();
                if (!$firstPlayer || !$firstPlayer->kelasPertandingan || !$firstPlayer->kelasPertandingan->kelas) {
                    continue;
                }

                $classDetails = $firstPlayer->kelasPertandingan;
                $pemainPerPendaftaran = $classDetails->kelas->jumlah_pemain ?: 1;
                $jumlahPendaftaran = ceil($playersInGroup->count() / $pemainPerPendaftaran);
                $allPlayers = $playersInGroup->values()->all();

                for ($i = 0; $i < $jumlahPendaftaran; $i++) {
                    $offset = $i * $pemainPerPendaftaran;
                    $pemainUntukItemIni = array_slice($allPlayers, $offset, $pemainPerPendaftaran);
                    if (empty($pemainUntukItemIni)) continue;

                    // Kita perlu pastikan data relasi event ada
                    $eventName = $firstPlayer->contingent && $firstPlayer->contingent->event
                        ? $firstPlayer->contingent->event->name
                        : 'Nama Event Tidak Tersedia';

                    $registrations[] = [
                        'event_name'        => $eventName,
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
        }
        return new Collection($registrations);
    }
}
