<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Contingent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class historyController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $contingents = Contingent::with(['event', 'user', 'players.kelasPertandingan'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('historyContingent.index', [
            'contingents' => $contingents
        ]);
    }

    public function updateContingent(Request $request, Contingent $contingent)
    {
        if ($contingent->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('contingent')->where('event_id', $contingent->event_id)->ignore($contingent->id),
            ],
        ], [
            'name.unique' => 'Nama kontingen ini sudah ada di event ini. Gunakan nama lain.',
        ]);

        // Update nama
        $contingent->name = $request->name;

        // Jika statusnya 'ditolak' (2), ubah kembali menjadi 'pending' (0)
        if ($contingent->status == 2) {
            $contingent->status = 0;
        }

        $contingent->save();

        return redirect()->route('history')->with('status', 'Nama kontingen berhasil diperbarui.');
    }

    public function editPlayer(Player $player)
    {
        if ($player->contingent->user_id !== Auth::id()) {
            abort(403);
        }

        return view('historyContingent.editPlayer', compact('player'));
    }

    public function updatePlayer(Request $request, Player $player)
    {
        if ($player->contingent->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|size:16',
            // Tambahkan validasi lain di sini
            'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_diri' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_persetujuan_ortu' => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Update data teks
        $player->update($request->except(['foto_ktp', 'foto_diri', 'foto_persetujuan_ortu']));

        if ($request->hasFile('foto_ktp')) {
            if ($player->foto_ktp) \Illuminate\Support\Facades\Storage::disk('public')->delete($player->foto_ktp);
            $player->foto_ktp = $request->file('foto_ktp')->store('player_documents', 'public');
        }

        if ($request->hasFile('foto_diri')) {
            if ($player->foto_diri) \Illuminate\Support\Facades\Storage::disk('public')->delete($player->foto_diri);
            $player->foto_diri = $request->file('foto_diri')->store('player_documents', 'public');
        }

        if ($request->hasFile('foto_persetujuan_ortu')) {
            if ($player->foto_persetujuan_ortu) \Illuminate\Support\Facades\Storage::disk('public')->delete($player->foto_persetujuan_ortu);
            $player->foto_persetujuan_ortu = $request->file('foto_persetujuan_ortu')->store('player_documents', 'public');
        }

        if ($player->status == 3) {
            $player->status = 1;
        }

        $player->save();

        return redirect()->route('history')->with('status', 'Data peserta berhasil diperbarui.');
    }

    public function destroyPlayer(Player $player)
    {
        // Otorisasi: Pastikan user yang login adalah pemilik kontingen
        if ($player->contingent->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak untuk menghapus peserta ini.');
        }

        if ($player->foto_ktp) {
            Storage::disk('public')->delete($player->foto_ktp);
        }
        if ($player->foto_diri) {
            Storage::disk('public')->delete($player->foto_diri);
        }
        if ($player->foto_persetujuan_ortu) {
            Storage::disk('public')->delete($player->foto_persetujuan_ortu);
        }

        // Hapus record player dari database
        $player->delete();

        return redirect()->route('history')->with('status', 'Data peserta berhasil dihapus.');
    }
}
