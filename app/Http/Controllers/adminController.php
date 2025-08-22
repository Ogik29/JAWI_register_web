<?php

namespace App\Http\Controllers;

use App\Models\Contingent;
use App\Models\Event;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class adminController extends Controller
{
    /**
     * Helper function to group players into their respective teams (ganda, regu, etc.).
     */
    private function groupPlayers($players)
    {
        $groupedRegistrations = [];

        // Group players by a unique team identifier: "contingentId-classId"
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
                    // We can take the status from the first player as it's the same for the whole query set
                    'status' => $firstPlayer->status,
                ];
            }
        }
        return $groupedRegistrations;
    }

    public function index()
    {
        $admin = Auth::user();
        $managedEventIds = $admin->eventRoles->pluck('event_id');

        // --- Data for Dashboard & "Kelola Event" Section ---
        $eventsQuery = Event::whereIn('id', $managedEventIds);
        $events = (clone $eventsQuery)->withCount('players')->latest()->get();
        $activeEvents = (clone $eventsQuery)->where('status', 1)->latest()->take(5)->get();

        // --- Data for Verification Tables (Pending) ---
        $contingentsForVerification = Contingent::with(['user', 'event', 'players', 'transactions'])
            ->whereIn('event_id', $managedEventIds)
            ->where('status', 0) // 0 = Menunggu Verifikasi
            ->latest()
            ->get();

        $playersForVerification = Player::with(['contingent.event', 'kelasPertandingan.kelas', 'playerInvoice'])
            ->whereHas('contingent', fn($q) => $q->whereIn('event_id', $managedEventIds))
            ->where('status', 1) // 1 = Pending
            ->latest()
            ->get();

        // --- Data for Approved Lists Tables ---
        $approvedPlayers = Player::with(['contingent.event', 'kelasPertandingan.kelas', 'playerInvoice'])
            ->whereHas('contingent', fn($q) => $q->whereIn('event_id', $managedEventIds))
            ->where('status', 2) // 2 = Terverifikasi
            ->latest()
            ->get();

        $approvedContingents = Contingent::with(['user', 'event', 'players'])
            ->whereIn('event_id', $managedEventIds)
            ->where('status', 1) // 1 = Disetujui
            ->latest()
            ->get();

        // --- Data for Rejected Lists Tables ---
        $rejectedContingents = Contingent::with(['user', 'event', 'players', 'transactions'])
            ->whereIn('event_id', $managedEventIds)
            ->where('status', 2) // 2 = Ditolak
            ->latest()
            ->get();

        $rejectedPlayers = Player::with(['contingent.event', 'kelasPertandingan.kelas', 'playerInvoice'])
            ->whereHas('contingent', fn($q) => $q->whereIn('event_id', $managedEventIds))
            ->where('status', 3) // 3 = Ditolak
            ->latest()
            ->get();

        // =================================================================
        // LOGIKA BARU: MENGELOMPOKKAN SEMUA DATA PEMAIN
        // =================================================================
        $groupedPlayersForVerification = $this->groupPlayers($playersForVerification);
        $groupedApprovedPlayers = $this->groupPlayers($approvedPlayers);
        $groupedRejectedPlayers = $this->groupPlayers($rejectedPlayers);


        // --- Data for Dashboard Cards ---
        $totalPlayers = Player::whereHas('contingent', fn($q) => $q->whereIn('event_id', $managedEventIds))->count();
        $pendingContingentsCount = $contingentsForVerification->count();
        $totalContingents = Contingent::whereIn('event_id', $managedEventIds)->count();
        $pendingPlayersCount = $playersForVerification->count(); // Count original players, not groups

        return view('admin.index', compact(
            'totalPlayers',
            'pendingContingentsCount',
            'activeEvents',
            'events',
            'contingentsForVerification',
            'approvedContingents',
            'totalContingents',
            'pendingPlayersCount',
            'rejectedContingents',
            // --- KIRIM DATA YANG SUDAH DI-GROUP ---
            'groupedPlayersForVerification',
            'groupedApprovedPlayers',
            'groupedRejectedPlayers'
        ));
    }

    public function verifyContingent(Request $request, Contingent $contingent)
    {
        $this->authorizeAdminAction($contingent->event_id);
        $request->validate(['action' => 'required|in:approve,reject', 'catatan' => 'nullable|string']);
        $contingent->status = ($request->action == 'approve') ? 1 : 2;
        $contingent->catatan = ($request->action == 'approve') ? null : $request->catatan;
        $contingent->save();
        return redirect()->route('adminIndex')->with('status', 'Verifikasi kontingen berhasil diproses.');
    }

    public function verifyPlayer(Request $request, Player $player)
    {
        $this->authorizeAdminAction($player->contingent->event_id);
        $request->validate(['action' => 'required|in:approve,reject', 'catatan' => 'nullable|string']);
        $player->status = ($request->action == 'approve') ? 2 : 3;
        $player->catatan = ($request->action == 'approve') ? null : $request->catatan;
        $player->save();
        return redirect()->route('adminIndex')->with('status', 'Verifikasi atlet berhasil diproses.');
    }

    private function authorizeAdminAction($event_id)
    {
        $adminEventIds = Auth::user()->eventRoles->pluck('event_id')->toArray();
        if (!in_array($event_id, $adminEventIds)) {
            abort(403, 'Anda tidak memiliki hak akses untuk event ini.');
        }
    }
}
