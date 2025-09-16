<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelasPertandingan;
use App\Models\Pertandingan;
use App\Models\Player;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\BracketPeserta;
use Exception;

class BracketController extends Controller
{
    /**
     * Menampilkan halaman bracket.
     */
    public function show(KelasPertandingan $kelas)
    {

        // Eager load relasi dari objek $kelas itu sendiri jika diperlukan oleh view
    $kelas->load(['event', 'kategoriPertandingan', 'jenisPertandingan']);

    // ====================================================================
    // BAGIAN 1: Mengambil dan Memproses Data Pertandingan (Rounds)
    // ====================================================================

    // LANGKAH 1: Ambil semua data pertandingan untuk kelas ini.
    // Kita tidak lagi memuat 'player1' atau 'player2', tapi kita masih bisa memuat 'winner'.
    $pertandingan_list = Pertandingan::where('kelas_pertandingan_id', $kelas->id)
                                     ->with('winner') // Asumsi 'winner_id' masih menunjuk ke satu Player
                                     ->orderBy('round_number')
                                     ->orderBy('match_number')
                                     ->get();

    // LANGKAH 2: Kumpulkan semua ID unit yang unik dari semua pertandingan.
    $unit_ids = $pertandingan_list->pluck('unit1_id')
                                  ->merge($pertandingan_list->pluck('unit2_id'))
                                  ->filter() // Menghapus ID yang null
                                  ->unique();

    $pemain_by_unit_id = collect(); // Inisialisasi sebagai koleksi kosong

    // LANGKAH 3: Jika ada unit yang akan diproses, ambil semua data pemainnya dalam SATU query.
    if ($unit_ids->isNotEmpty()) {
        // Ambil semua pemain yang relevan sekaligus
        $semua_pemain_unit = BracketPeserta::where('kelas_pertandingan_id', $kelas->id)
                                           ->whereIn('unit_id', $unit_ids)
                                           ->with('player.contingent') // Eager load relasi yang dalam
                                           ->get();

        // LANGKAH 4: Kelompokkan hasilnya berdasarkan 'unit_id' agar mudah dicari nanti.
        $pemain_by_unit_id = $semua_pemain_unit->groupBy('unit_id');
    }

    // LANGKAH 5: "Tempelkan" data pemain ke setiap objek pertandingan.
    $pertandingan_list->each(function ($pertandingan) use ($pemain_by_unit_id) {
        // Buat atribut baru secara dinamis.
        // Jika tidak ada pemain untuk unit tersebut, kembalikan koleksi kosong.
        $pertandingan->pemain_unit_1 = $pemain_by_unit_id->get($pertandingan->unit1_id, collect());
        $pertandingan->pemain_unit_2 = $pemain_by_unit_id->get($pertandingan->unit2_id, collect());
    });
    
    // LANGKAH 6: Kelompokkan hasil akhir berdasarkan nomor ronde untuk ditampilkan di view.
    $rounds = $pertandingan_list->groupBy('round_number');

    // ====================================================================
    // BAGIAN 2: Mengambil Pemain yang Belum Ditugaskan (Unassigned Players)
    // ====================================================================
    
    // Logika ini juga perlu diubah karena relasi langsung ke Player sudah tidak ada.
    
    // Pertama, cari semua ID unit yang sudah bertanding di Ronde 1.
    $units_in_round_1 = Pertandingan::where('kelas_pertandingan_id', $kelas->id)
                                    ->where('round_number', 1)
                                    ->pluck('unit1_id')
                                    ->merge(Pertandingan::where('kelas_pertandingan_id', $kelas->id)
                                                        ->where('round_number', 1)
                                                        ->pluck('unit2_id'))
                                    ->filter()
                                    ->unique();

    // Kedua, dapatkan semua ID pemain yang tergabung dalam unit-unit tersebut.
    $assigned_player_ids_round_1 = collect();
    if ($units_in_round_1->isNotEmpty()) {
        $assigned_player_ids_round_1 = BracketPeserta::where('kelas_pertandingan_id', $kelas->id)
                                                      ->whereIn('unit_id', $units_in_round_1)
                                                      ->pluck('player_id');
    }
    
    // Terakhir, cari pemain yang statusnya terverifikasi dan ID-nya TIDAK ADA di daftar pemain yang sudah ditugaskan.
    $currentEventId = $kelas->event_id;
    $unassignedPlayers = Player::where('kelas_pertandingan_id', $kelas->id)
        ->where('status', 2) // Hanya pemain terverifikasi
        ->whereNotIn('id', $assigned_player_ids_round_1)
        ->whereHas('contingent', function ($query) use ($currentEventId) {
            $query->where('event_id', $currentEventId);
        })
        ->with('contingent')
        ->get();

    // ====================================================================
    // BAGIAN 3: Mengirim Semua Data ke View
    // ====================================================================
    return view('admin.bracketShow', [
        'kelas' => $kelas,
        'rounds' => $rounds,
        'unassignedPlayers' => $unassignedPlayers,
        'totalRounds' => $rounds->keys()->max() ?? 0
    ]);
    }

    /**
     * Metode Cerdas untuk Mengatur Posisi Pemain (Penempatan & Tukar Posisi).
     * Ini adalah inti dari fitur geser-dan-letakkan.
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
    $targetColumn = 'unit' . $targetSlotNumber . '_id'; // Menggunakan kolom unit_id

    if ($targetMatch->round_number != 1) {
        return response()->json(['status' => 'error', 'message' => 'Peserta hanya bisa diatur secara manual pada Babak Pertama.'], 403);
    }

    // LANGKAH PENTING: Cari tahu unit_id dari player_id yang di-drag
    $bracketPeserta = BracketPeserta::where('player_id', $draggedPlayerId)
                                    ->where('kelas_pertandingan_id', $targetMatch->kelas_pertandingan_id)
                                    ->firstOrFail(); // Gagal jika pemain tidak terdaftar di kelas ini
    $draggedUnitId = $bracketPeserta->unit_id;


    DB::transaction(function () use ($draggedUnitId, $targetMatch, $targetColumn) {
        
        $occupantUnitId = $targetMatch->$targetColumn; // Mengambil unit_id yang sudah ada di slot target

        // Cek logika BYE: tidak bisa menambahkan lawan jika salah satu slot kosong (menandakan BYE)
        if (($targetMatch->unit1_id && !$targetMatch->unit2_id) || (!$targetMatch->unit1_id && $targetMatch->unit2_id)) {
            if (is_null($occupantUnitId)) {
                 return response()->json(['status' => 'error', 'message' => 'Tidak bisa menambahkan lawan pada pertandingan BYE.'], 403)->throwResponse();
            }
        }

        // Cari pertandingan sumber (source) tempat unit yang di-drag saat ini berada
        $sourceMatch = Pertandingan::where('round_number', 1)
            ->where('kelas_pertandingan_id', $targetMatch->kelas_pertandingan_id) // Tambahkan scope kelas
            ->where(fn($q) => $q->where('unit1_id', $draggedUnitId)->orWhere('unit2_id', $draggedUnitId))
            ->first();
        
        // KASUS 1: Unit yang di-drag sudah ada di bracket (operasi SWAP/TUKAR)
        if ($sourceMatch) {
            $sourceColumn = ($sourceMatch->unit1_id == $draggedUnitId) ? 'unit1_id' : 'unit2_id';
            
            // Lakukan penukaran ID unit
            $targetMatch->$targetColumn = $draggedUnitId;
            $sourceMatch->$sourceColumn = $occupantUnitId; 
            
            $sourceMatch->save();
            $targetMatch->save();
        } 
        // KASUS 2: Unit yang di-drag berasal dari luar bracket (operasi PLACE/PENEMPATAN)
        else {
            // Jika slot tujuan sudah diisi, tolak.
            if ($occupantUnitId) {
                return response()->json(['status' => 'error', 'message' => 'Slot tujuan sudah terisi oleh unit lain.'], 409)->throwResponse();
            }
            
            $targetMatch->$targetColumn = $draggedUnitId;

            // Jika setelah diisi kedua slot menjadi penuh, update status pertandingan
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
}