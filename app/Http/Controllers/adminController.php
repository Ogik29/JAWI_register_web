<?php

namespace App\Http\Controllers;

use App\Models\Contingent;
use App\Models\Event;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Exports\ApprovedParticipantsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\KelasPertandingan;
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

        // Status 0 = Menunggu Verifikasi Pembayaran
        $contingentsForVerification = Contingent::with(['user', 'event', 'players.kelasPertandingan.kelas', 'transactions'])
            ->whereIn('event_id', $managedEventIds)
            ->where('status', 0)
            ->latest()
            ->get();

        // BARU: Status 3 = Menunggu Verifikasi Data
        $contingentsForDataVerification = Contingent::with(['user', 'event', 'players.kelasPertandingan.kelas', 'transactions'])
            ->whereIn('event_id', $managedEventIds)
            ->where('status', 3)
            ->latest()
            ->get();

        $playersForVerification = Player::with($playerRelations)
            ->whereHas('contingent', fn($q) => $q->whereIn('event_id', $managedEventIds))
            ->where('status', 1)
            ->latest()
            ->get();

        $approvedContingents = Contingent::with(['user', 'event', 'players.kelasPertandingan.kelas', 'transactions'])
            ->whereIn('event_id', $managedEventIds)
            ->where('status', 1)
            ->latest('updated_at')
            ->get();

        $approvedPlayers = Player::with($playerRelations)
            ->whereHas('contingent', fn($q) => $q->whereIn('event_id', $managedEventIds))
            ->where('status', 2)
            ->latest('updated_at')
            ->get();

        $rejectedContingents = Contingent::with(['user', 'event', 'players.kelasPertandingan.kelas', 'transactions'])
            ->whereIn('event_id', $managedEventIds)
            ->where('status', 2)
            ->latest('updated_at')
            ->get();

        $rejectedPlayers = Player::with($playerRelations)
            ->whereHas('contingent', fn($q) => $q->whereIn('event_id', $managedEventIds))
            ->where('status', 3)
            ->latest('updated_at')
            ->get();

        $groupedPlayersForVerification = $this->groupPlayers($playersForVerification);
        $groupedApprovedPlayers = $this->groupPlayers($approvedPlayers);
        $groupedRejectedPlayers = $this->groupPlayers($rejectedPlayers);

        $totalPlayers = Player::whereHas('contingent', fn($q) => $q->whereIn('event_id', $managedEventIds))->count();
        $pendingContingentsCount = $contingentsForVerification->count() + $contingentsForDataVerification->count();
        $totalContingents = Contingent::whereIn('event_id', $managedEventIds)->count();
        $pendingPlayersCount = $playersForVerification->count();

        // KODE BARU: Ambil data untuk navigasi Bracket
        $managedEventIds = $admin->eventRoles->pluck('event_id');

        // 1. Ambil ID dari Kategori "Prestasi". Asumsikan namanya konsisten.
        // Jika tidak ada kategori Prestasi, id = 0 agar query tidak error.
        $kategoriPrestasiId = \App\Models\KategoriPertandingan::where('nama_kategori', 'Prestasi')->value('id') ?? 0;

        // 2. Ambil semua kelas pertandingan yang:
        //    - Termasuk dalam event yang dikelola admin
        //    - Memiliki Kategori "Prestasi"
        //    - Memiliki pemain terverifikasi (status 2) yang jumlahnya lebih dari 0
        $kelasUntukBracket = KelasPertandingan::with([
            'event',
            'kelas' // relasi untuk mengambil nama kelas
        ])
            ->whereIn('event_id', $managedEventIds)
            ->where('kategori_pertandingan_id', $kategoriPrestasiId)
            ->whereHas('players', function ($query) {
                $query->where('status', 2); // Status 2 = Terverifikasi
            })
            ->get();
        // ==========================================================


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
            'groupedApprovedPlayers',
            'groupedRejectedPlayers',
            'kelasUntukBracket' // <-- TAMBAHKAN VARIABEL BARU INI
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

        return Excel::download(new ApprovedParticipantsExport($event), $fileName);
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
