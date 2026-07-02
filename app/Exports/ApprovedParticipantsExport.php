<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat; // <-- 1. Import class ini
use Maatwebsite\Excel\Concerns\WithColumnFormatting; // <-- 2. Import concern ini

class ApprovedParticipantsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting // <-- 3. Tambahkan WithColumnFormatting
{
    protected $event;
    private $rowNumber = 0;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function collection()
    {
        $approvedPlayers = Player::whereHas('contingent', function ($query) {
            $query->where('event_id', $this->event->id);
        })
            ->where('status', 2)
            ->with([
                'contingent.event', // Pastikan relasi event dimuat untuk event_name
                'kelasPertandingan.kelas.rentangUsia',
                'kelasPertandingan.kategoriPertandingan',
                'kelasPertandingan.jenisPertandingan'
            ])
            ->get();
        return $this->groupPlayersByRegistration($approvedPlayers);
    }

    public function headings(): array
    {
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

    public function map($registration): array
    {
        $this->rowNumber++;
        $playerNames = $registration['players']->pluck('name')->implode("\n");
        $playerBirthDates = $registration['players']->pluck('tgl_lahir')->map(fn($d) => Carbon::parse($d)->format('d F Y'))->implode("\n");
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
            "'" . $playerNiks . "'"
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);
        $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I:L')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:L')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
    }

    /**
     * Tentukan format untuk kolom tertentu.
     *
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            // Kolom L adalah kolom ke-12 (NIK)
            // Format "@" memaksa Excel untuk memperlakukannya sebagai Teks.
            'L' => NumberFormat::FORMAT_TEXT,

            // Kolom K adalah kolom No. Telepon, juga bagus untuk dijadikan Teks
            // untuk menjaga angka 0 di depan jika ada.
            'K' => NumberFormat::FORMAT_TEXT,
        ];
    }


    /**
     * Fungsi helper untuk mengelompokkan pemain.
     * <<< INI ADALAH BAGIAN YANG DIPERBAIKI >>>
     */
    private function groupPlayersByRegistration(Collection $players): Collection
    {
        $registrations = [];

        // LANGKAH 1: Kelompokkan pemain berdasarkan contingent_id MEREKA.
        $playersByContingent = $players->groupBy('contingent_id');

        // Lakukan iterasi untuk setiap kontingen
        foreach ($playersByContingent as $contingentId => $playersInContingent) {

            // LANGKAH 2: Di dalam setiap kontingen, kelompokkan lagi berdasarkan kelas pertandingan.
            $playersByClass = $playersInContingent->groupBy('kelas_pertandingan_id');

            // Lakukan iterasi untuk setiap kelas di dalam kontingen tersebut
            foreach ($playersByClass as $kelasPertandinganId => $playersInGroup) {
                $firstPlayer = $playersInGroup->first();
                if (!$firstPlayer || !$firstPlayer->kelasPertandingan || !$firstPlayer->kelasPertandingan->kelas) {
                    continue;
                }

                $classDetails = $firstPlayer->kelasPertandingan;
                // Sekarang semua $playersInGroup dijamin dari kontingen yang sama.
                // Logika grouping asli Anda sekarang aman untuk digunakan.
                $pemainPerPendaftaran = $classDetails->kelas->jumlah_pemain ?: 1;
                $jumlahPemainTotal = $playersInGroup->count();
                $jumlahPendaftaran = ceil($jumlahPemainTotal / $pemainPerPendaftaran);

                $allPlayers = $playersInGroup->values()->all();

                for ($i = 0; $i < $jumlahPendaftaran; $i++) {
                    $offset = $i * $pemainPerPendaftaran;
                    $pemainUntukItemIni = array_slice($allPlayers, $offset, $pemainPerPendaftaran);
                    if (empty($pemainUntukItemIni)) continue;

                    // Karena $firstPlayer diambil dari grup yang sudah benar,
                    // maka contingent->name juga pasti benar.
                    $registrations[] = [
                        'event_name'        => $this->event->name,
                        'contingent_name'   => $firstPlayer->contingent->name, // Ini sekarang sudah pasti benar
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
