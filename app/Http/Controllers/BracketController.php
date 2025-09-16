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

    public function generate(KelasPertandingan $kelas)
    {
        try {
            DB::transaction(function () use ($kelas) {
                $kelas->pertandingan()->delete();

                // 1. Hapus data BracketPeserta yang lama untuk kelas ini.
                BracketPeserta::where('kelas_pertandingan_id', $kelas->id)->delete();

                // 2. Ambil semua Player yang terverifikasi.
                $verifiedPlayers = Player::where('kelas_pertandingan_id', $kelas->id)
                    ->where('status', 2)
                    ->get();

                // 3. Buat entri baru di BracketPeserta untuk setiap Player.
                // Di sini, kita asumsikan 1 player = 1 unit.
                // Jika ganda/regu, logikanya akan lebih kompleks.
                $unitIdCounter = 1;
                foreach ($verifiedPlayers as $player) {
                    BracketPeserta::create([
                        'kelas_pertandingan_id' => $kelas->id,
                        'unit_id' => $unitIdCounter,
                        'player_id' => $player->id,
                    ]);
                    $unitIdCounter++;
                }


                // Kode di bawah ini tetap sama, karena ia mengambil data dari
                // BracketPeserta yang baru saja kita buat.
                $unitIds = BracketPeserta::where('kelas_pertandingan_id', $kelas->id)
                    ->pluck('unit_id')->unique()->values()->all();

                $unitCount = count($unitIds);
                if ($unitCount < 2) {
                    throw new Exception('Peserta/Tim terverifikasi minimal 2 untuk membuat bracket.');
                }

                $bracketSize = pow(2, ceil(log($unitCount, 2)));
                $totalRounds = log($bracketSize, 2);
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

                $firstRoundMatches = collect($nextRoundMatches);
                $byeCount = $bracketSize - $unitCount;

                shuffle($unitIds);

                $byeMatches = collect();
                $fullMatches = collect();
                $nextMatchIdsWithBye = [];
                $byesToPlace = $byeCount;

                foreach ($firstRoundMatches as $match) {
                    if ($byesToPlace > 0 && !in_array($match->next_match_id, $nextMatchIdsWithBye)) {
                        $byeMatches->push($match);
                        $nextMatchIdsWithBye[] = $match->next_match_id;
                        $byesToPlace--;
                    } else {
                        $fullMatches->push($match);
                    }
                }

                $unitIndex = 0;

                foreach ($byeMatches as $match) {
                    $match->update([
                        'unit1_id' => $unitIds[$unitIndex++],
                        'unit2_id' => null,
                        'status'   => 'menunggu_peserta',
                    ]);
                }

                foreach ($fullMatches as $match) {
                    $unit1 = $unitIds[$unitIndex++] ?? null;
                    $unit2 = $unitIds[$unitIndex++] ?? null;
                    $match->update([
                        'unit1_id' => $unit1,
                        'unit2_id' => $unit2,
                        'status'   => 'siap_dimulai',
                    ]);
                }
            });
        } catch (Exception $e) {
            return redirect()->route('bracket.show', $kelas->id)->with('error', $e->getMessage());
        }

        return redirect()->route('bracket.show', $kelas->id)->with('success', 'Bracket berhasil dibuat dan diundi secara adil!');
    }
}
