<?php

namespace App\Http\Controllers;

use App\Models\Contingent;
use App\Models\Event;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class adminController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        $managedEventIds = $admin->eventRoles->pluck('event_id');

        // --- Data Dashboard & "Kelola Event" Section ---
        $eventsQuery = Event::whereIn('id', $managedEventIds);
        $events = (clone $eventsQuery)->withCount('players')->latest()->get();
        $activeEvents = (clone $eventsQuery)->where('status', 1)->latest()->take(5)->get();

        // --- Data Verification Tables ---
        $contingentsForVerification = Contingent::with(['user', 'event', 'players', 'transactions'])
            ->whereIn('event_id', $managedEventIds)
            ->where('status', 0) // 0 = Menunggu Verifikasi
            ->latest()
            ->get();

        $playersForVerification = Player::with(['contingent.event', 'kelasPertandingan', 'playerInvoice'])
            ->whereHas('contingent', fn($q) => $q->whereIn('event_id', $managedEventIds))
            ->where('status', 1) // 1 = Pending (sudah bayar, menunggu verif dokumen)
            ->latest()
            ->get();

        // --- Data Approved Lists Tables ---
        $approvedContingents = Contingent::with(['user', 'event', 'players'])
            ->whereIn('event_id', $managedEventIds)
            ->where('status', 1) // 1 = Disetujui
            ->latest()
            ->get();

        $approvedPlayers = Player::with(['contingent.event', 'kelasPertandingan'])
            ->whereHas('contingent', fn($q) => $q->whereIn('event_id', $managedEventIds))
            ->where('status', 2) // 2 = Terverifikasi
            ->latest()
            ->get();

        // --- Data Dashboard Cards ---
        $totalPlayers = Player::whereHas('contingent', fn($q) => $q->whereIn('event_id', $managedEventIds))->count();
        $pendingContingentsCount = $contingentsForVerification->count();

        // Calculate total contingents for the managed events
        $totalContingents = Contingent::whereIn('event_id', $managedEventIds)->count();

        // Calculate total pending players for the managed events
        $pendingPlayersCount = $playersForVerification->count();


        return view('admin.index', compact(
            'totalPlayers',
            'pendingContingentsCount',
            'activeEvents',
            'events',
            'contingentsForVerification',
            'playersForVerification',
            'approvedContingents',
            'approvedPlayers',
            'totalContingents',      // Pass new data
            'pendingPlayersCount'    // Pass new data
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
