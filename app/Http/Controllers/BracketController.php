<?php

namespace App\Http\Controllers;

use App\Models\BracketPeserta;
use App\Models\KelasPertandingan;
use App\Models\Pertandingan;
use App\Models\Player; // Pastikan ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception; // Pastikan ini di-import
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PertandinganExport;

class BracketController extends Controller
{
    public function show(KelasPertandingan $kelas)
    {
        $kelas->load(['event', 'kategoriPertandingan', 'jenisPertandingan']);

        $pertandingan_list = Pertandingan::where('kelas_pertandingan_id', $kelas->id)
            ->with('winner')
            ->orderBy('round_number')
            ->orderBy('match_number')
            ->get();

        $unit_ids = $pertandingan_list->pluck('unit1_id')
            ->merge($pertandingan_list->pluck('unit2_id'))
            ->filter()
            ->unique();

        $pemain_by_unit_id = collect();

        if ($unit_ids->isNotEmpty()) {
            $semua_pemain_unit = BracketPeserta::where('kelas_pertandingan_id', $kelas->id)
                ->whereIn('unit_id', $unit_ids)
                ->with('player.contingent')
                ->get();
            $pemain_by_unit_id = $semua_pemain_unit->groupBy('unit_id');
        }

        $pertandingan_list->each(function ($pertandingan) use ($pemain_by_unit_id) {
            $pertandingan->pemain_unit_1 = $pemain_by_unit_id->get($pertandingan->unit1_id, collect());
            $pertandingan->pemain_unit_2 = $pemain_by_unit_id->get($pertandingan->unit2_id, collect());
        });

        $rounds = $pertandingan_list->groupBy('round_number');

        $units_in_round_1 = Pertandingan::where('kelas_pertandingan_id', $kelas->id)
            ->where('round_number', 1)
            ->pluck('unit1_id')
            ->merge(Pertandingan::where('kelas_pertandingan_id', $kelas->id)
                ->where('round_number', 1)
                ->pluck('unit2_id'))
            ->filter()
            ->unique();

        $assigned_player_ids_round_1 = collect();
        if ($units_in_round_1->isNotEmpty()) {
            $assigned_player_ids_round_1 = BracketPeserta::where('kelas_pertandingan_id', $kelas->id)
                ->whereIn('unit_id', $units_in_round_1)
                ->pluck('player_id');
        }

        $currentEventId = $kelas->event_id;
        $unassignedPlayers = Player::where('kelas_pertandingan_id', $kelas->id)
            ->where('status', 2)
            ->whereNotIn('id', $assigned_player_ids_round_1)
            ->whereHas('contingent', function ($query) use ($currentEventId) {
                $query->where('event_id', $currentEventId);
            })
            ->with('contingent')
            ->get();

        return view('admin.bracketShow', [
            'kelas' => $kelas,
            'rounds' => $rounds,
            'unassignedPlayers' => $unassignedPlayers,
            'totalRounds' => $rounds->keys()->max() ?? 0
        ]);
    }

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
        $targetColumn = 'unit' . $targetSlotNumber . '_id';

        if ($targetMatch->round_number != 1) {
            return response()->json(['status' => 'error', 'message' => 'Peserta hanya bisa diatur secara manual pada Babak Pertama.'], 403);
        }

        $bracketPeserta = BracketPeserta::where('player_id', $draggedPlayerId)
            ->where('kelas_pertandingan_id', $targetMatch->kelas_pertandingan_id)
            ->firstOrFail();
        $draggedUnitId = $bracketPeserta->unit_id;

        DB::transaction(function () use ($draggedUnitId, $targetMatch, $targetColumn) {
            $occupantUnitId = $targetMatch->$targetColumn;

            if (($targetMatch->unit1_id && !$targetMatch->unit2_id) || (!$targetMatch->unit1_id && $targetMatch->unit2_id)) {
                if (is_null($occupantUnitId)) {
                    return response()->json(['status' => 'error', 'message' => 'Tidak bisa menambahkan lawan pada pertandingan BYE.'], 403)->throwResponse();
                }
            }

            $sourceMatch = Pertandingan::where('round_number', 1)
                ->where('kelas_pertandingan_id', $targetMatch->kelas_pertandingan_id)
                ->where(fn($q) => $q->where('unit1_id', $draggedUnitId)->orWhere('unit2_id', $draggedUnitId))
                ->first();

            if ($sourceMatch) {
                $sourceColumn = ($sourceMatch->unit1_id == $draggedUnitId) ? 'unit1_id' : 'unit2_id';
                $targetMatch->$targetColumn = $draggedUnitId;
                $sourceMatch->$sourceColumn = $occupantUnitId;
                $sourceMatch->save();
                $targetMatch->save();
            } else {
                if ($occupantUnitId) {
                    return response()->json(['status' => 'error', 'message' => 'Slot tujuan sudah terisi oleh unit lain.'], 409)->throwResponse();
                }
                $targetMatch->$targetColumn = $draggedUnitId;
                if ($targetMatch->unit1_id && $targetMatch->unit2_id) {
                    $targetMatch->status = 'siap_dimulai';
                }
                $targetMatch->save();
            }
        });
        return response()->json(['status' => 'success', 'message' => 'Posisi unit berhasil diperbarui.']);
    }

    /**
     * [DISEMPURNAKAN] Fitur DRAW hanya menempatkan pemain, tanpa meloloskan pemenang "bye".
     */
 public function generate(KelasPertandingan $kelas)
{
    try {
        DB::transaction(function () use ($kelas) {
            
            // =========================================================================
            // BAGIAN 1 & 2: SUDAH BENAR, TIDAK DIUBAH
            // =========================================================================
            $kelas->pertandingan()->delete();
            $kelas->bracketPeserta()->delete();
            $jumlahPemainPerUnit = $kelas->kelas->jumlah_pemain;
            $players = $kelas->players()->where('status', 2)->with('contingent')->get();

            if ($players->isEmpty()) {
                throw new Exception('Tidak ada pemain terverifikasi untuk kelas ini.');
            }
            $unitCounter = 1;
            if ($jumlahPemainPerUnit == 1) {
                foreach ($players as $player) {
                    BracketPeserta::create(['kelas_pertandingan_id' => $kelas->id, 'player_id' => $player->id, 'unit_id' => $unitCounter]);
                    $unitCounter++;
                }
            } else {
                $playersByContingent = $players->groupBy('contingent_id');
                foreach ($playersByContingent as $contingentPlayers) {
                    $teams = $contingentPlayers->chunk($jumlahPemainPerUnit);
                    foreach ($teams as $teamPlayers) {
                        if ($teamPlayers->count() < $jumlahPemainPerUnit) {
                            $contingentName = $teamPlayers->first()->contingent->name ?? 'Tidak diketahui';
                            throw new Exception("Kontingen '{$contingentName}' memiliki jumlah pemain tidak lengkap ({$teamPlayers->count()} dari {$jumlahPemainPerUnit}).");
                        }
                        foreach ($teamPlayers as $player) {
                            BracketPeserta::create(['kelas_pertandingan_id' => $kelas->id, 'player_id' => $player->id, 'unit_id' => $unitCounter]);
                        }
                        $unitCounter++;
                    }
                }
            }

            // =========================================================================
            // BAGIAN 3: [DIPERBAIKI TOTAL] PEMBUATAN STRUKTUR PERTANDINGAN
            // =========================================================================
            
            $unitIds = BracketPeserta::where('kelas_pertandingan_id', $kelas->id)
                                     ->pluck('unit_id')->unique()->values();
            
            // ACAK unit SEKARANG, sebelum struktur bracket dibuat
            $shuffledUnitIds = $unitIds->shuffle();

            $unitCount = $shuffledUnitIds->count();
            if ($unitCount < 2) {
                throw new Exception('Jumlah unit/tim terverifikasi kurang dari 2.');
            }

            $bracketSize = pow(2, ceil(log($unitCount, 2)));
            $totalRounds = log($bracketSize, 2);
            $byeCount = $bracketSize - $unitCount;

            // Buat semua pertandingan kosong dari final mundur ke babak 1
            $nextRoundMatches = [];
            for ($round = $totalRounds; $round >= 1; $round--) {
                $matchesInThisRound = [];
                // [FIX] Jumlah pertandingan di babak 1 adalah bracketSize / 2, bukan dihitung dari totalRounds
                $matchCountInThisRound = ($round == 1) ? $bracketSize / 2 : pow(2, $totalRounds - $round);

                for ($i = 0; $i < $matchCountInThisRound; $i++) {
                    $match = Pertandingan::create([
                        'kelas_pertandingan_id' => $kelas->id,
                        'round_number' => $round,
                        'match_number' => $i + 1,
                        // Kunci di sini adalah memastikan $nextRoundMatches punya indeks yang benar
                        'next_match_id' => $nextRoundMatches[floor($i / 2)]->id ?? null,
                    ]);
                    $matchesInThisRound[] = $match;
                }
                $nextRoundMatches = $matchesInThisRound;
            }

            // Ambil semua pertandingan di babak pertama
            $firstRoundMatches = collect($nextRoundMatches);

            // Tempatkan SEMUA unit ke dalam slot `unit1_id` terlebih dahulu
            $unitIndex = 0;
            foreach ($firstRoundMatches as $match) {
                if ($unitIndex < $unitCount) {
                    $match->unit1_id = $shuffledUnitIds[$unitIndex++];
                    $match->save();
                }
            }
            
            // Sekarang, "promosikan" unit yang mendapat "bye" dan isi slot `unit2_id` untuk sisanya
            $matchesToFillOpponent = $firstRoundMatches->slice($byeCount);
            foreach ($matchesToFillOpponent as $match) {
                if ($unitIndex < $unitCount) {
                    $match->unit2_id = $shuffledUnitIds[$unitIndex++];
                    $match->status = 'siap_dimulai';
                    $match->save();
                }
            }
        });

    } catch (Exception $e) {
        return redirect()->route('bracket.show', $kelas->id)->with('error', $e->getMessage());
    }

    return redirect()->route('bracket.show', $kelas->id)->with('success', 'Unit peserta berhasil dikelompokkan dan bracket telah diundi!');
}
    
    // Metode updateMatch(), advanceWinner(), dan retractPlayersFromNextMatch() telah dihapus.

    public function exportExcel(KelasPertandingan $kelas)
    {
        // Buat nama file yang deskriptif
        $fileName = 'Jadwal Pertandingan - ' . 
                    str_replace(' ', '_', $kelas->kelas->nama_kelas) . '_' . 
                    $kelas->gender . '.xlsx';

        // Panggil class export dan unduh file
        return Excel::download(new PertandinganExport($kelas), $fileName);
    }
}
