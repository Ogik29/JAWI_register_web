<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelasPertandingan;
use App\Models\Pertandingan;
use App\Models\Player;
use Illuminate\Support\Facades\DB;

class BracketController extends Controller
{
    /**
     * Menampilkan halaman bracket.
     */
    public function show(KelasPertandingan $kelas)
    {
        $kelas->load(['event', 'kelas', 'kategoriPertandingan', 'jenisPertandingan']);

        $rounds = $kelas->pertandingan()
            ->with(['player1.contingent', 'player2.contingent', 'winner'])
            ->orderBy('round_number')
            ->orderBy('match_number')
            ->get()
            ->groupBy('round_number');

        $currentEventId = $kelas->event_id;

        $unassignedPlayers = Player::where('kelas_pertandingan_id', $kelas->id)
            ->where('status', 2) // Hanya pemain terverifikasi
            ->whereDoesntHave('matchesAsPlayer1')
            ->whereDoesntHave('matchesAsPlayer2')
            ->whereHas('contingent', function ($query) use ($currentEventId) {
                $query->where('event_id', $currentEventId);
            })
            ->get();

        return view('admin.bracketShow', [
            'kelas' => $kelas,
            'rounds' => $rounds,
            'unassignedPlayers' => $unassignedPlayers,
            'totalRounds' => $rounds->keys()->max() ?? 0
        ]);
    }

    /**
     * FITUR DRAW BARU: Membuat dan mengacak seluruh struktur bracket yang stabil.
     */
    public function generate(KelasPertandingan $kelas)
    {
        DB::transaction(function () use ($kelas) {
            // 1. Hapus bracket lama jika ada
            $kelas->pertandingan()->delete();

            // 2. Ambil pemain terverifikasi (status 2) dan acak urutannya
            $players = $kelas->players()->where('status', 2)->get()->shuffle();
            $playerCount = $players->count();

            if ($playerCount < 2) {
                // Gunakan redirect() helper untuk menghentikan transaksi dan kembali
                return redirect()->route('bracket.show', $kelas)->with('error', 'Peserta terverifikasi minimal 2 untuk membuat bracket.');
            }

            // 3. Hitung properti bracket
            $bracketSize = pow(2, ceil(log($playerCount, 2)));
            $totalRounds = log($bracketSize, 2);

            // 4. Buat semua pertandingan kosong dari final mundur ke babak 1
            $nextRoundMatches = [];
            for ($round = $totalRounds; $round >= 1; $round--) {
                $matchesInThisRound = [];
                $matchCountInThisRound = pow(2, $totalRounds - $round);

                for ($i = 0; $i < $matchCountInThisRound; $i++) {
                    $match = Pertandingan::create([
                        'kelas_pertandingan_id' => $kelas->id,
                        'round_number' => $round,
                        'match_number' => $i + 1,
                        'next_match_id' => $nextRoundMatches[floor($i / 2)]->id ?? null,
                    ]);
                    $matchesInThisRound[] = $match;
                }
                $nextRoundMatches = $matchesInThisRound;
            }

            // 5. Atur matchups untuk Babak 1
            $firstRoundMatches = collect($nextRoundMatches);
            $byeCount = $bracketSize - $playerCount;
            $matchesWithPlayersCount = ($playerCount - $byeCount) / 2;

            $playerIndex = 0;

            // Isi pertandingan normal
            for ($i = 0; $i < $matchesWithPlayersCount; $i++) {
                $match = $firstRoundMatches[$i];
                $match->update([
                    'player1_id' => $players[$playerIndex++]->id,
                    'player2_id' => $players[$playerIndex++]->id,
                    'status' => 'siap_dimulai',
                ]);
            }

            // Berikan "Bye" kepada sisa pemain
            $matchesForByes = $firstRoundMatches->slice($matchesWithPlayersCount);
            foreach ($matchesForByes as $match) {
                if ($playerIndex < $playerCount) {
                    $playerWithBye = $players[$playerIndex++];
                    $match->update([
                        'player1_id' => $playerWithBye->id,
                        'winner_id' => $playerWithBye->id,
                        'status' => 'selesai'
                    ]);
                    $this->advanceWinner($match);
                }
            }
        });

        return redirect()->route('bracket.show', $kelas)->with('success', 'Bracket berhasil dibuat dan diundi!');
    }


    /**
     * Update skor dan pemenang.
     */
    public function updateMatch(Request $request, Pertandingan $pertandingan)
    {
        $request->validate([
            'score1' => 'nullable|integer',
            'score2' => 'nullable|integer',
            'winner_id' => 'nullable|integer'
        ]);

        $pertandingan->score1 = $request->score1;
        $pertandingan->score2 = $request->score2;
        $pertandingan->status = 'selesai';

        // Tentukan pemenang
        if ($request->winner_id && in_array($request->winner_id, [$pertandingan->player1_id, $pertandingan->player2_id])) {
            $pertandingan->winner_id = $request->winner_id;
        } else {
            $pertandingan->winner_id = null;
        }

        $pertandingan->save();

        // Majukan pemenang ke pertandingan berikutnya jika ada
        if ($pertandingan->winner_id) {
            $this->advanceWinner($pertandingan);
        }

        return back()->with('success', 'Skor berhasil diperbarui!');
    }


    /**
     * Method helper untuk memajukan pemenang ke babak selanjutnya.
     */
    private function advanceWinner(Pertandingan $match)
    {
        if (!$match->winner_id || !$match->next_match_id) {
            return;
        }

        $nextMatch = Pertandingan::find($match->next_match_id);

        // Tentukan slot berdasarkan nomor match di babak sebelumnya
        if ($match->match_number % 2 != 0) { // Jika nomor match ganjil, masuk ke slot 1
            $nextMatch->player1_id = $match->winner_id;
        } else { // Jika nomor match genap, masuk ke slot 2
            $nextMatch->player2_id = $match->winner_id;
        }

        // Jika kedua slot sudah terisi, ubah status match berikutnya
        if ($nextMatch->player1_id && $nextMatch->player2_id) {
            $nextMatch->status = 'siap_dimulai';
        }

        $nextMatch->save();
    }
}
