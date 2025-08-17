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
        // Data for Header & Dashboard Cards
        $totalPlayers = Player::count();
        $pendingContingentsCount = Contingent::where('status', 0)->count();
        $activeEvents = Event::where('status', 'Pendaftaran Dibuka')->latest()->take(5)->get(); // Assuming status 1 = active

        $events = Event::withCount('players')->latest()->get();

        // Data for "Verifikasi Kontingen" Table
        $contingentsForVerification = Contingent::with(['user', 'event', 'players', 'transactions'])
            ->where('status', 0) // 0 = Menunggu Verifikasi
            ->latest()
            ->get();

        // Data for "Verifikasi Atlet" Table
        $playersForVerification = Player::with(['contingent.event', 'kelasPertandingan'])
            ->where('status', 1) // 1 = Pending (sudah bayar, menunggu verif dokumen)
            ->latest()
            ->get();

        return view('admin.index', compact(
            'totalPlayers',
            'pendingContingentsCount',
            'activeEvents',
            'events',
            'contingentsForVerification',
            'playersForVerification'
        ));
    }

    /**
     * Process the verification for a contingent.
     */
    public function verifyContingent(Request $request, Contingent $contingent)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string',
        ]);

        if ($request->action == 'approve') {
            $contingent->status = 1; // Set status to "Disetujui"
            $contingent->catatan = null;
        } else {
            $contingent->status = 2; // Set status to "Ditolak"
            $contingent->catatan = $request->catatan;
        }

        $contingent->save();

        return redirect()->route('adminIndex')->with('status', 'Verifikasi kontingen berhasil diproses.');
    }

    /**
     * Process the verification for a player.
     */
    public function verifyPlayer(Request $request, Player $player)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string',
        ]);

        if ($request->action == 'approve') {
            $player->status = 2; // Set status to "Terverifikasi"
            $player->catatan = null;
        } else {
            $player->status = 3; // Set status to "Ditolak"
            $player->catatan = $request->catatan;
        }

        $player->save();

        return redirect()->route('adminIndex')->with('status', 'Verifikasi atlet berhasil diproses.');
    }
}
