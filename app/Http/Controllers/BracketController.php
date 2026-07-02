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
use App\Exports\PertandinganExportAll;



class BracketController extends Controller
{
    public function show(KelasPertandingan $kelas)
    {
        $kelas->load(['event', 'kategoriPertandingan', 'jenisPertandingan', 'kelas.rentangUsia']);

        $pertandingan_list = Pertandingan::where('kelas_pertandingan_id', $kelas->id)
            ->orderBy('round_number')->orderBy('match_number')->get();

        $all_unit_ids_in_bracket = $pertandingan_list->pluck('unit1_id')->merge($pertandingan_list->pluck('unit2_id'))->filter()->unique();

        $pemain_by_unit_id = collect();
        if ($all_unit_ids_in_bracket->isNotEmpty()) {
            $pemain_by_unit_id = BracketPeserta::where('kelas_pertandingan_id', $kelas->id)
                ->whereIn('unit_id', $all_unit_ids_in_bracket)
                ->with('player.contingent')->get()->groupBy('unit_id');
        }

        $pertandingan_list->each(function ($pertandingan) use ($pemain_by_unit_id) {
            $pertandingan->pemain_unit_1 = $pemain_by_unit_id->get($pertandingan->unit1_id, collect());
            $pertandingan->pemain_unit_2 = $pemain_by_unit_id->get($pertandingan->unit2_id, collect());
        });

        $rounds = $pertandingan_list->groupBy('round_number');

        // Ambil SEMUA unit yang ada di kelas ini, lalu kurangi dengan yang sudah ada di bracket
        $all_unit_ids_in_class = BracketPeserta::where('kelas_pertandingan_id', $kelas->id)->pluck('unit_id')->unique();
        $unassigned_unit_ids = $all_unit_ids_in_class->diff($all_unit_ids_in_bracket);

        $unassigned_units = collect();
        if ($unassigned_unit_ids->isNotEmpty()) {
            $unassigned_units = BracketPeserta::where('kelas_pertandingan_id', $kelas->id)
                ->whereIn('unit_id', $unassigned_unit_ids)
                ->with('player.contingent')->get()->groupBy('unit_id');
        }

        $allApprovedPlayers = $kelas->players()->where('status', 2)->with('contingent')->get();

        return view('admin.bracketShow', [
            'kelas' => $kelas,
            'rounds' => $rounds,
            'unassignedUnits' => $unassigned_units,
            'allApprovedPlayers' => $allApprovedPlayers,
            'totalRounds' => $rounds->keys()->max() ?? 0
        ]);
    }

    /**
     * [DIPERBARUI] Metode Cerdas untuk Mengatur Posisi UNIT.
     */
    /**
     * [DIPERBARUI & DISEMPURNAKAN] Metode Cerdas untuk Mengatur Posisi UNIT.
     * Mampu melakukan swap dan memutus koneksi 'next_match_id' yang konflik.
     */
    /**
     * [DIPERBARUI & DISEMPURNAKAN] Metode Cerdas untuk Mengatur Posisi UNIT.
     * Mampu melakukan swap, memutus koneksi konflik, DAN MENGARAHKAN ULANG koneksi saat swap.
     */
    public function updatePosition(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:bracket_peserta,unit_id',
            'match_id' => 'required|exists:pertandingan,id',
            'slot' => 'required|in:1,2',
        ]);

        $draggedUnitId = $validated['unit_id'];
        $targetMatch = Pertandingan::findOrFail($validated['match_id']);
        $targetColumn = 'unit' . $validated['slot'] . '_id';

        DB::transaction(function () use ($draggedUnitId, $targetMatch, $targetColumn) {
            // LANGKAH 1: IDENTIFIKASI PIHAK TERLIBAT
            $displacedUnitId = $targetMatch->$targetColumn;
            $sourceMatch = Pertandingan::where('kelas_pertandingan_id', $targetMatch->kelas_pertandingan_id)
                ->where(fn($q) => $q->where('unit1_id', $draggedUnitId)->orWhere('unit2_id', $draggedUnitId))
                ->first();

            // LANGKAH 2: LAKUKAN SWAP UNIT
            $targetMatch->$targetColumn = $draggedUnitId;
            $targetMatch->save();

            if ($sourceMatch) {
                $sourceColumn = ($sourceMatch->unit1_id == $draggedUnitId) ? 'unit1_id' : 'unit2_id';
                $sourceMatch->$sourceColumn = $displacedUnitId;
                $sourceMatch->save();
            } else if ($displacedUnitId) {
                // Unit dari unassigned menimpa unit lain, buat unit yang tertimpa menjadi unassigned.
                Pertandingan::where('id', '!=', $targetMatch->id)
                    ->where(fn($q) => $q->where('unit1_id', $displacedUnitId)->orWhere('unit2_id', $displacedUnitId))
                    ->update(['unit1_id' => null, 'unit2_id' => null]);
            }

            // LANGKAH 3: SINKRONISASI ULANG SELURUH KONEKSI BRACKET
            $this->resynchronizeNextMatchConnections($targetMatch->kelas_pertandingan_id);

            // LANGKAH 4: UPDATE STATUS SELURUH PERTANDINGAN
            Pertandingan::where('kelas_pertandingan_id', $targetMatch->kelas_pertandingan_id)
                ->chunkById(100, function ($matches) {
                    foreach ($matches as $match) {
                        $match->status = ($match->unit1_id && $match->unit2_id) ? 'siap_dimulai' : 'menunggu_peserta';
                        $match->save();
                    }
                });
        });

        return response()->json(['status' => 'success', 'message' => 'Posisi unit berhasil diperbarui dan bagan disinkronkan!']);
    }

    /**
     * [FUNGSI BARU] Membangun ulang semua koneksi next_match_id untuk satu kelas.
     * Ini menjamin konsistensi data setelah ada perubahan manual.
     */
    private function resynchronizeNextMatchConnections(int $kelasPertandinganId)
    {
        // 1. Reset semua koneksi yang ada untuk memulai dari awal
        Pertandingan::where('kelas_pertandingan_id', $kelasPertandinganId)
            ->update(['next_match_id' => null]);

        // 2. Ambil semua pertandingan dalam struktur yang mudah diakses (dikelompokkan per ronde)
        $allMatches = Pertandingan::where('kelas_pertandingan_id', $kelasPertandinganId)
            ->orderBy('round_number')->orderBy('match_number')->get();
        $rounds = $allMatches->groupBy('round_number');
        $totalRounds = $rounds->keys()->max() ?? 0;

        // 3. Iterasi dari ronde pertama hingga ronde sebelum final
        for ($roundNum = 1; $roundNum < $totalRounds; $roundNum++) {
            if (!isset($rounds[$roundNum]) || !isset($rounds[$roundNum + 1])) {
                continue; // Lewati jika ronde saat ini atau ronde berikutnya tidak ada
            }

            foreach ($rounds[$roundNum] as $sourceMatch) {
                // Tentukan pertandingan tujuan di ronde berikutnya
                $targetMatchNumber = floor(($sourceMatch->match_number - 1) / 2) + 1;
                $targetMatch = $rounds[$roundNum + 1]->firstWhere('match_number', $targetMatchNumber);

                if (!$targetMatch) {
                    continue; // Lewati jika target tidak ditemukan
                }

                // Tentukan slot mana yang akan diisi oleh pemenang pertandingan ini
                $targetColumn = ($sourceMatch->match_number % 2 != 0) ? 'unit1_id' : 'unit2_id';

                // ATURAN KUNCI: Buat koneksi HANYA JIKA slot tujuan itu KOSONG
                if (is_null($targetMatch->$targetColumn)) {
                    $sourceMatch->next_match_id = $targetMatch->id;
                    $sourceMatch->save();
                }
            }
        }
    }
    /**

     * [DISEMPURNAKAN] Fitur DRAW hanya menempatkan pemain, tanpa meloloskan pemenang "bye".
     */
    // File: app/Http/Controllers/BracketController.php

    // File: app/Http/Controllers/BracketController.php

    // File: app/HttpControllers/BracketController.php

    public function generate(KelasPertandingan $kelas)
    {
        try {
            DB::transaction(function () use ($kelas) {

                // =========================================================================
                // BAGIAN 1 & 2: PENGELOMPOKAN UNIT (TIDAK BERUBAH)
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
                        BracketPeserta::create(['kelas_pertandingan_id' => $kelas->id, 'player_id' => $player->id, 'unit_id' => $unitCounter++]);
                    }
                } else {
                    $playersByContingent = $players->groupBy('contingent_id');
                    foreach ($playersByContingent as $contingentPlayers) {
                        $teams = $contingentPlayers->chunk($jumlahPemainPerUnit);
                        foreach ($teams as $teamPlayers) {
                            if ($teamPlayers->count() < $jumlahPemainPerUnit) {
                                $contingentName = $teamPlayers->first()->contingent->name ?? 'Tidak diketahui';
                                throw new Exception("Kontingen '{$contingentName}' tidak lengkap.");
                            }
                            foreach ($teamPlayers as $player) {
                                BracketPeserta::create(['kelas_pertandingan_id' => $kelas->id, 'player_id' => $player->id, 'unit_id' => $unitCounter]);
                            }
                            $unitCounter++;
                        }
                    }
                }

                // =========================================================================
                // BAGIAN 3: PEMBUATAN STRUKTUR BRACKET (TIDAK BERUBAH)
                // =========================================================================

                $unitIds = BracketPeserta::where('kelas_pertandingan_id', $kelas->id)
                    ->pluck('unit_id')->unique()->values()->shuffle()->toArray();

                $unitCount = count($unitIds);
                if ($unitCount < 2) {
                    throw new Exception('Jumlah unit/tim terverifikasi kurang dari 2.');
                }

                $totalRounds = ceil(log($unitCount, 2));
                $bracketSize = pow(2, $totalRounds);
                $byeCount = $bracketSize - $unitCount;

                $allMatches = [];
                $nextRoundMatches = [];
                for ($round = $totalRounds; $round >= 2; $round--) {
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
                    $allMatches[$round] = collect($matchesInThisRound);
                }

                $unitsWithBye = array_slice($unitIds, 0, $byeCount);
                $unitsInFirstRound = array_slice($unitIds, $byeCount);

                $firstRoundMatches = [];
                $firstRoundMatchCount = count($unitsInFirstRound) / 2;
                for ($i = 0; $i < $firstRoundMatchCount; $i++) {
                    $match = Pertandingan::create([
                        'kelas_pertandingan_id' => $kelas->id,
                        'round_number' => 1,
                        'match_number' => $i + 1,
                        'unit1_id' => $unitsInFirstRound[$i * 2],
                        'unit2_id' => $unitsInFirstRound[($i * 2) + 1],
                        'status' => 'siap_dimulai',
                    ]);
                    $firstRoundMatches[] = $match;
                }
                $allMatches[1] = collect($firstRoundMatches);

                // ======================================================================================
                // [BAGIAN YANG DIUBAH] LOGIKA PENGISIAN BABAK 2 DENGAN DISTRIBUSI "BYE" YANG MERATA
                // ======================================================================================

                $round2Matches = $allMatches[2] ?? collect();
                $round2Slots = []; // Ini akan kita isi dengan cerdas

                $matchesFromRound1 = $allMatches[1]->all(); // Pemenang dari babak 1
                $byes = $unitsWithBye; // Unit yang lolos langsung

                $totalFeederSlots = count($matchesFromRound1) + count($byes);
                $matchesPtr = 0;
                $byesPtr = 0;

                // Algoritma untuk menyisipkan 'bye' secara merata
                for ($i = 0; $i < $totalFeederSlots; $i++) {
                    // Formula ini menentukan kapan harus menempatkan 'bye' agar tersebar
                    // floor(($i + 1) * count($byes) / $totalFeederSlots) -> Berapa banyak 'bye' yang seharusnya sudah ditempatkan pada iterasi ini.
                    // $byesPtr -> Berapa banyak 'bye' yang sudah ditempatkan.
                    if ($byesPtr < count($byes) && floor(($i + 1) * count($byes) / $totalFeederSlots) > $byesPtr) {
                        $round2Slots[] = $byes[$byesPtr];
                        $byesPtr++;
                    } else {
                        if (isset($matchesFromRound1[$matchesPtr])) {
                            $round2Slots[] = $matchesFromRound1[$matchesPtr];
                            $matchesPtr++;
                        }
                    }
                }

                // Sekarang, isi Babak 2 dengan feeder slot yang sudah terdistribusi
                foreach ($round2Matches as $index => $matchInRound2) {
                    $slot1 = $round2Slots[$index * 2] ?? null;
                    $slot2 = $round2Slots[($index * 2) + 1] ?? null;

                    if ($slot1) {
                        if ($slot1 instanceof Pertandingan) {
                            $slot1->next_match_id = $matchInRound2->id;
                            $slot1->save();
                        } elseif (is_numeric($slot1)) {
                            $matchInRound2->unit1_id = $slot1;
                        }
                    }

                    if ($slot2) {
                        if ($slot2 instanceof Pertandingan) {
                            $slot2->next_match_id = $matchInRound2->id;
                            $slot2->save();
                        } elseif (is_numeric($slot2)) {
                            $matchInRound2->unit2_id = $slot2;
                        }
                    }

                    $matchInRound2->save();
                }

                $this->revalidateBracketConnections($kelas->id);
            });
        } catch (Exception $e) {
            return redirect()->route('bracket.show', $kelas->id)->with('error', $e->getMessage());
        }

        return redirect()->route('bracket.show', $kelas->id)->with('success', 'Unit peserta berhasil dikelompokkan dan bracket telah diundi!');
    }



    private function revalidateBracketConnections(int $kelasPertandinganId)
    {
        // Ambil semua pertandingan yang memiliki koneksi ke babak selanjutnya
        $matchesWithNext = Pertandingan::where('kelas_pertandingan_id', $kelasPertandinganId)
            ->whereNotNull('next_match_id')
            ->with('nextMatch') // Eager load pertandingan tujuan
            ->get();

        foreach ($matchesWithNext as $sourceMatch) {
            $targetMatch = $sourceMatch->nextMatch;

            // Jika pertandingan tujuan tidak ada (kemungkinan data korup), lewati.
            if (!$targetMatch) continue;

            // Cek apakah slot yang seharusnya diisi oleh pertandingan ini sudah terisi manual oleh unit lain.
            $targetSlot = ($sourceMatch->match_number % 2 != 0) ? 'unit1_id' : 'unit2_id';
            $occupantUnitId = $targetMatch->$targetSlot;

            // JIKA slot tujuan sudah terisi OLEH UNIT LAIN, putuskan koneksi.
            // Kita juga cek apakah unit yang mengisi itu berasal dari "bye" di babak ini.
            if ($occupantUnitId && !$sourceMatch->where(fn($q) => $q->where('unit1_id', $occupantUnitId)->orWhere('unit2_id', $occupantUnitId))->exists()) {
                $sourceMatch->next_match_id = null;
                $sourceMatch->save();
            }
        }

        // Terakhir, update semua status pertandingan
        $allMatchesInClass = Pertandingan::where('kelas_pertandingan_id', $kelasPertandinganId)->get();
        foreach ($allMatchesInClass as $match) {
            if ($match->unit1_id && $match->unit2_id) {
                $match->status = 'siap_dimulai';
            } else {
                $match->status = 'menunggu_peserta';
            }
            $match->save();
        }
    }

    // HAPUS FUNGSI HELPER advanceUnit, KITA TIDAK MEMBUTUHKANNYA LAGI DI SINI

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

    public function exportAll(KelasPertandingan $kelas)
    {
        $fileName = 'Semua Pertandingan - ' .
            str_replace(' ', '_', $kelas->kelas->nama_kelas) . '_' .
            $kelas->gender . '.csv';

        return Excel::download(new PertandinganExportAll($kelas), $fileName, \Maatwebsite\Excel\Excel::CSV);
    }
}
