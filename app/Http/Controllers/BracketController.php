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
            $kelas->pertandingan()->delete();

            $unitIds = BracketPeserta::where('kelas_pertandingan_id', $kelas->id)
                ->whereHas('player', function ($query) {
                    $query->where('status', 2);
                })
                ->pluck('unit_id')->unique()->values()->all();

            $unitCount = count($unitIds);
            if ($unitCount < 2) {
                throw new Exception('Peserta/Tim terverifikasi minimal 2 untuk membuat bracket.');
            }

            // Pembuatan struktur bracket (tetap sama, sudah benar)
            $bracketSize = pow(2, ceil(log($unitCount, 2)));
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

            // =========================================================================
            // [LOGIKA BARU] Penempatan BYE Terdistribusi Secara Strategis
            // =========================================================================

            $firstRoundMatches = collect($nextRoundMatches);
            $byeCount = $bracketSize - $unitCount;

            // Acak urutan unit yang akan ditempatkan untuk keadilan undian
            shuffle($unitIds);

            // 1. Pisahkan pertandingan Babak 1 menjadi dua grup: yang akan mendapat BYE dan yang akan penuh.
            $byeMatches = collect();
            $fullMatches = collect();
            $nextMatchIdsWithBye = []; // Melacak next_match_id yang sudah dialokasikan untuk BYE
            $byesToPlace = $byeCount;

            foreach ($firstRoundMatches as $match) {
                // Jika kita masih harus menempatkan BYE DAN `next_match_id` dari pertandingan ini
                // BELUM pernah kita gunakan untuk menempatkan BYE sebelumnya...
                if ($byesToPlace > 0 && !in_array($match->next_match_id, $nextMatchIdsWithBye)) {
                    // ...maka pertandingan ini kita pilih untuk menjadi host BYE.
                    $byeMatches->push($match);
                    $nextMatchIdsWithBye[] = $match->next_match_id; // Tandai `next_match_id` ini agar tidak dipilih lagi.
                    $byesToPlace--;
                } else {
                    // Jika tidak, pertandingan ini akan menjadi pertandingan penuh (unit vs unit).
                    $fullMatches->push($match);
                }
            }

            // 2. Lakukan penempatan unit ke dalam grup pertandingan yang sudah dipisahkan.
            $unitIndex = 0;

            // Tempatkan SATU unit ke setiap pertandingan BYE.
            foreach ($byeMatches as $match) {
                $match->update([
                    'unit1_id' => $unitIds[$unitIndex++],
                    'unit2_id' => null,
                    'status'   => 'menunggu_peserta',
                ]);
            }

            // Tempatkan DUA unit ke setiap pertandingan penuh.
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
    
    // Metode updateMatch(), advanceWinner(), dan retractPlayersFromNextMatch() telah dihapus.
}