<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Player;
use App\Models\Contingent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use App\Models\KelasPertandingan;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Exports\ApprovedContingentsExport;
use App\Exports\ApprovedParticipantsExport;
use App\Exports\PendingDataVerificationExport;
// use App\Models\Event;
// use Illuminate\Http\Request;

class adminController extends Controller
{
    /**
     * Helper function to group players into their respective teams (ganda, regu, etc.).
     *
     * @param \Illuminate\Database\Eloquent\Collection $players
     * @return array
     */
    private function groupPlayers($players)
    {
        $groupedRegistrations = [];

        $playersByTeam = $players->groupBy(function ($player) {
            return $player->contingent_id . '-' . $player->kelas_pertandingan_id;
        });

        foreach ($playersByTeam as $playersInTeam) {
            $firstPlayer = $playersInTeam->first();
            if (!$firstPlayer || !$firstPlayer->kelasPertandingan || !$firstPlayer->kelasPertandingan->kelas) {
                continue;
            }

            $classDetails = $firstPlayer->kelasPertandingan;
            $pemainPerPendaftaran = $classDetails->kelas->jumlah_pemain ?: 1;
            $jumlahPendaftaran = ceil($playersInTeam->count() / $pemainPerPendaftaran);

            for ($i = 0; $i < $jumlahPendaftaran; $i++) {
                $pemainUntukItemIni = $playersInTeam->slice($i * $pemainPerPendaftaran, $pemainPerPendaftaran);

                if ($pemainUntukItemIni->isEmpty()) {
                    continue;
                }

                $groupedRegistrations[] = [
                    'player_instances' => $pemainUntukItemIni,
                    'player_names' => $pemainUntukItemIni->pluck('name')->implode(', '),
                    'nama_kelas' => $classDetails->kelas->nama_kelas ?? 'N/A',
                    'gender' => $classDetails->gender,
                    'status' => $firstPlayer->status,
                ];
            }
        }
        return $groupedRegistrations;
    }

    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $admin = Auth::user();
        $managedEventIds = $admin->eventRoles->pluck('event_id');

        $eventsQuery = Event::whereIn('id', $managedEventIds);
        $events = (clone $eventsQuery)->withCount('players')->latest()->get();
        $activeEvents = (clone $eventsQuery)->where('status', 1)->latest()->take(5)->get();

        $playerRelations = [
            'contingent.event',
            'playerInvoice',
            'kelasPertandingan.kelas.rentangUsia',
            'kelasPertandingan.kategoriPertandingan',
            'kelasPertandingan.jenisPertandingan'
        ];

        $contingentsForVerification = Contingent::with(['user', 'event', 'transactions'])
            ->whereIn('event_id', $managedEventIds)->where('status', 0)->latest()->get();
        $contingentsForDataVerification = Contingent::with(['user', 'event', 'transactions'])
            ->whereIn('event_id', $managedEventIds)->where('status', 3)->latest()->get();

        // ===============================================
        // AWAL DARI BLOK LOGIKA YANG DIREVISI TOTAL
        // ===============================================

        // GRUP 1: Atlet yang menunggu verifikasi PEMBAYARAN.
        // Syarat:
        // - Punya invoice.
        // - Bukti bayar SUDAH diunggah.
        // - Status pemain masih menunggu verifikasi (status=1).
        $playersForPaymentVerification = Player::with($playerRelations)
            ->whereHas('contingent', fn($q) => $q->whereIn('event_id', $managedEventIds))
            ->where('status', 1)
            ->whereHas('playerInvoice', function ($query) {
                $query->whereNotNull('foto_invoice'); // Kuncinya di sini: bukti bayar sudah ada
            })
            ->latest()
            ->get();

        // GRUP 2: Atlet yang menunggu verifikasi DATA (karena kontingen lunas atau event gratis).
        // Syarat:
        // - Kontingennya sudah lunas (status 1).
        // - ATAU Event-nya gratis untuk atlet (harga kelas = 0).
        // - Status pemain masih menunggu verifikasi (status=1).
        $playersForDataVerification = Player::with($playerRelations)
            ->where('status', 0)
            ->whereHas('contingent', function ($query) use ($managedEventIds) {
                $query->whereIn('event_id', $managedEventIds)
                    ->where('status', 1); // <-- Kontingen sudah disetujui
            })
            // Juga ikutkan pemain yang bukti bayarnya belum diunggah atau belum punya invoice
            ->where(function ($query) {
                $query->whereDoesntHave('playerInvoice') // Belum punya invoice SAMA SEKALI
                    ->orWhereHas('playerInvoice', function ($subQuery) {
                        $subQuery->whereNull('foto_invoice'); // Sudah punya invoice, TAPI bukti bayar KOSONG
                    });
            })
            ->latest()
            ->get();

        // Lakukan pengelompokan untuk kedua grup
        $groupedPlayersForVerification = $this->groupPlayers($playersForPaymentVerification);
        $groupedPlayersForDataVerification = $this->groupPlayers($playersForDataVerification);

        // ===============================================
        // AKHIR DARI BLOK LOGIKA YANG DIREVISI
        // ===============================================

        $approvedContingents = Contingent::with(['user', 'event', 'players'])
            ->whereIn('event_id', $managedEventIds)->where('status', 1)->latest('updated_at')->get();
        $approvedPlayers = Player::with($playerRelations)
            ->whereHas('contingent', fn($q) => $q->whereIn('event_id', $managedEventIds))
            ->where('status', 2)->latest('updated_at')->get();
        $rejectedContingents = Contingent::with(['user', 'event'])
            ->whereIn('event_id', $managedEventIds)->where('status', 2)->latest('updated_at')->get();
        $rejectedPlayers = Player::with($playerRelations)
            ->whereHas('contingent', fn($q) => $q->whereIn('event_id', $managedEventIds))
            ->where('status', 3)->latest('updated_at')->get();

        $groupedApprovedPlayers = $this->groupPlayers($approvedPlayers);
        $groupedRejectedPlayers = $this->groupPlayers($rejectedPlayers);

        $totalPlayers = Player::whereHas('contingent', fn($q) => $q->whereIn('event_id', $managedEventIds))->count();
        $pendingContingentsCount = $contingentsForVerification->count() + $contingentsForDataVerification->count();
        $totalContingents = Contingent::whereIn('event_id', $managedEventIds)->count();

        // Hitung total atlet pending dari kedua grup
        $pendingPlayersCount = $playersForPaymentVerification->count() + $playersForDataVerification->count();

        $kategoriPrestasiId = \App\Models\KategoriPertandingan::where('nama_kategori', 'Prestasi')->value('id') ?? 0;
        $kelasUntukBracket = KelasPertandingan::with(['event', 'kelas.rentangUsia', 'kategoriPertandingan', 'jenisPertandingan'])
            ->whereIn('event_id', $managedEventIds)->where('kategori_pertandingan_id', $kategoriPrestasiId)
            ->withCount(['players' => fn($query) => $query->where('status', 2)])
            ->having('players_count', '>', 0)->get();

        $kelasUntukBracket->each(fn($kelas) => $kelas->has_drawing = \App\Models\Pertandingan::where('kelas_pertandingan_id', $kelas->id)->exists());

        return view('admin.index', compact(
            'totalPlayers',
            'pendingContingentsCount',
            'activeEvents',
            'events',
            'contingentsForVerification',
            'contingentsForDataVerification',
            'approvedContingents',
            'totalContingents',
            'pendingPlayersCount',
            'rejectedContingents',
            'groupedPlayersForVerification',
            'groupedPlayersForDataVerification', // Ganti nama variabel lama
            'groupedApprovedPlayers',
            'groupedRejectedPlayers',
            'kelasUntukBracket'
        ));
    }

    /**
     * Verify or reject a contingent.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contingent $contingent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyContingent(Request $request, Contingent $contingent)
    {
        $this->authorizeAdminAction($contingent->event_id);
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string|required_if:action,reject'
        ]);

        // LOGIKA BARU UNTUK MULTI-TAHAP VERIFIKASI
        if ($request->action == 'approve') {
            if ($contingent->event->harga_contingent == 0) {
                $contingent->status = 1;
            } elseif ($contingent->status == 0) { // Tahap 1: Verifikasi Pembayaran
                $contingent->status = 3; // Lolos ke Verifikasi Data
            } elseif ($contingent->status == 3) { // Tahap 2: Verifikasi Data
                $contingent->status = 1; // Sepenuhnya disetujui
            }
            $contingent->catatan = null; // Hapus catatan jika disetujui
        } else {
            // Jika ditolak, dari status manapun akan menjadi 2
            $contingent->status = 2;
            $contingent->catatan = $request->catatan;
        }

        $contingent->save();
        return redirect()->route('adminIndex')->with('status', 'Verifikasi kontingen berhasil diproses.');
    }

    /**
     * Verify or reject a player.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Player $player
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyPlayer(Request $request, Player $player)
    {
        $this->authorizeAdminAction($player->contingent->event_id);
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string|required_if:action,reject'
        ]);

        $player->status = ($request->action == 'approve') ? 2 : 3;
        $player->catatan = ($request->action == 'approve') ? null : $request->catatan;
        $player->save();
        return redirect()->route('adminIndex')->with('status', 'Verifikasi atlet berhasil diproses.');
    }


    public function exportApprovedParticipants(Event $event)
    {
        $fileName = 'peserta-disetujui-' . $event->slug . '.xlsx';

        //  $approvedPlayers = Player::whereHas('contingent', function ($query) use ($event) {
        //     $query->where('event_id', $event->id);
        // })
        //     ->where('status', 2)
        //     ->with([
        //         'contingent',
        //         'kelasPertandingan.kelas.rentangUsia',
        //         'kelasPertandingan.kategoriPertandingan',
        //         'kelasPertandingan.jenisPertandingan'
        //     ])
        //     ->get();

        // // return $approvedPlayers;

        return Excel::download(new ApprovedParticipantsExport($event), $fileName);
    }

    /**
     * Print all verified player cards for a specific event.
     *
     * @param \App\Models\Event $event
     * @return \Illuminate\Http\Response
     */
    public function printAllCards(Event $event)
    {
        // 1. Otorisasi admin
        $this->authorizeAdminAction($event->id);

        // 2. Ambil semua pemain yang terverifikasi untuk event ini
        $approvedPlayers = Player::where('status', 2)
            ->whereHas('contingent', function ($query) use ($event) {
                $query->where('event_id', $event->id);
            })
            ->with([
                'contingent.event',
                'kelasPertandingan.kelas.rentangUsia'
            ])
            ->orderBy('contingent_id') // Urutkan berdasarkan kontingen
            ->orderBy('name') // Lalu urutkan berdasarkan nama
            ->get();

        // Cek jika tidak ada peserta
        if ($approvedPlayers->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada peserta terverifikasi untuk dicetak pada event ini.');
        }

        // 3. Kirim data ke view PDF baru
        $pdf = Pdf::loadView('pdf.all_player_cards', [
            'players' => $approvedPlayers,
            'event' => $event,
        ]);

        // 4. Set ukuran kertas A4 (standar untuk multi-kartu)
        $pdf->setPaper('a4', 'portrait');

        // 5. Tampilkan PDF di browser
        return $pdf->stream('semua-kartu-peserta-' . Str::slug($event->name) . '.pdf');
    }

    public function exportApprovedContingents()
    {
        // Otorisasi: Dapatkan event yang dikelola oleh admin saat ini
        $admin = Auth::user();
        $managedEventIds = $admin->eventRoles->pluck('event_id')->toArray();

        // Cek jika admin tidak mengelola event apapun
        if (empty($managedEventIds)) {
            return redirect()->back()->with('error', 'Anda tidak mengelola event apapun untuk diekspor.');
        }

        // Siapkan nama file
        $fileName = 'rekapitulasi-kontingen-disetujui.xlsx';

        // Panggil class export yang baru dibuat dengan membawa ID event yang dikelola
        return Excel::download(new ApprovedContingentsExport($managedEventIds), $fileName);
    }

    public function exportPendingDataVerificationParticipants(Event $event)
    {
        // Otorisasi admin
        $this->authorizeAdminAction($event->id);

        // Siapkan nama file
        $fileName = 'peserta-pending-verifikasi-data-' . $event->slug . '.xlsx';

        // Logika query ini sama persis dengan yang ada di metode index() untuk mengambil data pending
        $pendingPlayers = Player::with([
            'contingent.event',
            'kelasPertandingan.kelas.rentangUsia',
            'kelasPertandingan.kategoriPertandingan',
            'kelasPertandingan.jenisPertandingan'
        ])
            ->where('status', 0)
            ->whereHas('contingent', function ($query) use ($event) {
                $query->where('event_id', $event->id)
                    ->where('status', 1); // Kontingen sudah lunas
            })
            ->where(function ($query) {
                $query->whereDoesntHave('playerInvoice') // Belum punya invoice SAMA SEKALI
                    ->orWhereHas('playerInvoice', function ($subQuery) {
                        $subQuery->whereNull('foto_invoice'); // Sudah punya invoice, TAPI bukti bayar KOSONG
                    });
            })
            ->get();

        // Panggil class export yang baru kita buat
        return Excel::download(new PendingDataVerificationExport($pendingPlayers), $fileName);
    }

    /**
     * Authorize that the admin has access to the given event.
     *
     * @param int $event_id
     * @return void
     */
    private function authorizeAdminAction($event_id)
    {
        $adminEventIds = Auth::user()->eventRoles->pluck('event_id')->toArray();
        if (!in_array($event_id, $adminEventIds)) {
            abort(403, 'Anda tidak memiliki hak akses untuk event ini.');
        }
    }
}
