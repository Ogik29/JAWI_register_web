<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelasPertandingan;
use App\Models\Pertandingan;
use App\Models\Player;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
            ->where(function ($query) {
                // Cari pemain yang tidak ada di slot player1 ATAU player2 pada babak 1
                $query->whereDoesntHave('matchesAsPlayer1', function ($q) {
                    $q->where('round_number', 1);
                })
                ->whereDoesntHave('matchesAsPlayer2', function ($q) {
                    $q->where('round_number', 1);
                });
            })
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
     * [DIPERBARUI TOTAL] - Metode Cerdas untuk Mengatur Posisi Pemain.
     * Kini bisa menangani penempatan biasa dan TUKAR POSISI (SWAP).
     */
    public function updatePosition(Request $request)
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'match_id' => 'required|exists:pertandingan,id',
            'slot' => 'required|in:1,2',
        ]);

        $draggedPlayerId = $validated['player_id'];
        $targetMatch = Pertandingan::findOrFail($validated['match_id']);
        $targetSlotNumber = $validated['slot'];
        $targetColumn = 'player' . $targetSlotNumber . '_id';

        // Aturan 1: Blokir jika bukan Babak 1
        if ($targetMatch->round_number != 1) {
            return response()->json(['status' => 'error', 'message' => 'Pemain hanya bisa diatur secara manual pada Babak Pertama.'], 403);
        }

        DB::transaction(function () use ($draggedPlayerId, $targetMatch, $targetColumn) {
            
            $occupantPlayerId = $targetMatch->$targetColumn; // Siapa yang ada di slot tujuan?

            // Aturan 2: Mencegah menambahkan lawan ke pertandingan 'bye'
            // Jika satu slot terisi dan lainnya kosong, maka itu adalah pertandingan 'bye'
            if (($targetMatch->player1_id && !$targetMatch->player2_id) || (!$targetMatch->player1_id && $targetMatch->player2_id)) {
                // Jika admin mencoba mengisi slot yang kosong pada pertandingan 'bye', blokir.
                if (is_null($occupantPlayerId)) {
                     // throwResponse akan menghentikan transaksi dan mengirimkan error JSON
                     return response()->json(['status' => 'error', 'message' => 'Tidak bisa menambahkan lawan pada pertandingan BYE. Loloskan pemain terlebih dahulu.'], 403)->throwResponse();
                }
            }

            // Temukan posisi Asal dari pemain yang digeser (jika dia sudah ada di bracket)
            $sourceMatch = Pertandingan::where('round_number', 1)
                ->where(fn($q) => $q->where('player1_id', $draggedPlayerId)->orWhere('player2_id', $draggedPlayerId))
                ->first();
            
            // SKENARIO A: Pemain digeser dari satu slot ke slot lain (SWAP)
            if ($sourceMatch) {
                $sourceColumn = ($sourceMatch->player1_id == $draggedPlayerId) ? 'player1_id' : 'player2_id';

                // Tukar Posisi: Pemain di slot tujuan dipindahkan ke slot asal
                $targetMatch->$targetColumn = $draggedPlayerId;
                $sourceMatch->$sourceColumn = $occupantPlayerId; 
                
                $sourceMatch->save();
                $targetMatch->save();
            } 
            // SKENARIO B: Pemain digeser dari daftar pemain ke slot (PLACEMENT)
            else {
                if ($occupantPlayerId) {
                    // Pengaman jika frontend gagal memblokir, ini akan mencegah menimpa pemain lain
                    return response()->json(['status' => 'error', 'message' => 'Slot tujuan sudah terisi oleh pemain lain.'], 409)->throwResponse();
                }
                $targetMatch->$targetColumn = $draggedPlayerId;
                if ($targetMatch->player1_id && $targetMatch->player2_id) {
                    $targetMatch->status = 'siap_dimulai';
                }
                $targetMatch->save();
            }
        });

        return response()->json(['status' => 'success', 'message' => 'Posisi pemain berhasil diperbarui.']);
    }

    /**
     * Update skor dan pemenang. Mampu menangani kasus "Manual Bye".
     */
    public function updateMatch(Request $request, Pertandingan $pertandingan)
    {
        $valid_winners = [$pertandingan->player1_id];
        if ($pertandingan->player2_id) {
            $valid_winners[] = $pertandingan->player2_id;
        }

        $request->validate([
            'score1' => 'nullable|integer',
            'score2' => 'nullable|integer',
            'winner_id' => ['required', 'exists:players,id', Rule::in($valid_winners)],
        ]);

        DB::transaction(function () use ($request, $pertandingan) {
            $this->retractPlayersFromNextMatch($pertandingan);
            $pertandingan->score1 = $request->input('score1');
            $pertandingan->score2 = $request->input('score2');
            $pertandingan->winner_id = $request->input('winner_id');
            $pertandingan->status = 'selesai';
            $pertandingan->save();
            $this->advanceWinner($pertandingan);
        });

        return back()->with('success', 'Pemenang berhasil ditentukan dan bracket telah diperbarui!');
    }

    /**
     * Fitur DRAW tidak lagi otomatis meloloskan pemain "bye".
     */
    public function generate(KelasPertandingan $kelas)
    {
        DB::transaction(function () use ($kelas) {
            $kelas->pertandingan()->delete();
            $players = $kelas->players()->where('status', 2)->get()->shuffle();
            $playerCount = $players->count();
            if ($playerCount < 2) {
                return redirect()->route('bracket.show', $kelas)->with('error', 'Peserta terverifikasi minimal 2 untuk membuat bracket.');
            }
            $bracketSize = pow(2, ceil(log($playerCount, 2)));
            $totalRounds = log($bracketSize, 2);
            $nextRoundMatches = [];
            for ($round = $totalRounds; $round >= 1; $round--) {
                $matchesInThisRound = [];
                $matchCountInThisRound = pow(2, $totalRounds - $round);
                for ($i = 0; $i < $matchCountInThisRound; $i++) {
                    $match = Pertandingan::create([
                        'kelas_pertandingan_id' => $kelas->id, 'round_number' => $round,
                        'match_number' => $i + 1, 'next_match_id' => $nextRoundMatches[floor($i / 2)]->id ?? null,
                    ]);
                    $matchesInThisRound[] = $match;
                }
                $nextRoundMatches = $matchesInThisRound;
            }
            $firstRoundMatches = collect($nextRoundMatches);
            $byeCount = $bracketSize - $playerCount;
            $matchesWithPlayersCount = ($playerCount - $byeCount) / 2;
            $playerIndex = 0;
            for ($i = 0; $i < $matchesWithPlayersCount; $i++) {
                $match = $firstRoundMatches[$i];
                $match->update(['player1_id' => $players[$playerIndex++]->id, 'player2_id' => $players[$playerIndex++]->id, 'status' => 'siap_dimulai']);
            }
            $matchesForByes = $firstRoundMatches->slice($matchesWithPlayersCount);
            foreach ($matchesForByes as $match) {
                if ($playerIndex < $playerCount) {
                    $playerWithBye = $players[$playerIndex++];
                    $match->update(['player1_id' => $playerWithBye->id, 'player2_id' => null, 'status' => 'menunggu_peserta']);
                }
            }
        });

        return redirect()->route('bracket.show', $kelas)->with('success', 'Bracket berhasil dibuat dan diundi!');
    }

    private function advanceWinner(Pertandingan $match) { if (!$match->winner_id || !$match->next_match_id) return; $nextMatch = $match->nextMatch; if (!$nextMatch) return; $targetSlot = ($match->match_number % 2 != 0) ? 'player1_id' : 'player2_id'; $nextMatch->$targetSlot = $match->winner_id; if ($nextMatch->player1_id && $nextMatch->player2_id) { $nextMatch->status = 'siap_dimulai'; } else { $nextMatch->status = 'menunggu_peserta'; } $nextMatch->save(); }
    private function retractPlayersFromNextMatch(Pertandingan $pertandingan) { if (!$pertandingan->next_match_id) return; $nextMatch = $pertandingan->nextMatch; if (!$nextMatch) return; $playersInThisMatch = array_filter([$pertandingan->player1_id, $pertandingan->player2_id]); foreach($playersInThisMatch as $playerId){ if ($nextMatch->player1_id == $playerId) $nextMatch->player1_id = null; if ($nextMatch->player2_id == $playerId) $nextMatch->player2_id = null; } $nextMatch->status = 'menunggu_peserta'; $nextMatch->save(); }
}