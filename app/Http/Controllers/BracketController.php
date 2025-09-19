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
        // Eager loading relasi yang dibutuhkan
        $kelas->load(['event', 'kategoriPertandingan', 'jenisPertandingan', 'kelas.rentangUsia']);

        // Mengambil semua pertandingan dengan relasi yang diperlukan
        $pertandingan_list = Pertandingan::where('kelas_pertandingan_id', $kelas->id)
            ->with('winner') // Pemenang, jika ada
            ->orderBy('round_number')
            ->orderBy('match_number')
            ->get();

        // Mengumpulkan semua unit_id yang unik dari pertandingan
        $unit_ids = $pertandingan_list->pluck('unit1_id')
            ->merge($pertandingan_list->pluck('unit2_id'))
            ->filter()
            ->unique();

        // Mengambil data pemain dan mengelompokkannya berdasarkan unit_id
        $pemain_by_unit_id = collect();
        if ($unit_ids->isNotEmpty()) {
            $pemain_by_unit_id = BracketPeserta::where('kelas_pertandingan_id', $kelas->id)
                ->whereIn('unit_id', $unit_ids)
                ->with('player.contingent') // Muat relasi pemain dan kontingennya
                ->get()
                ->groupBy('unit_id');
        }

        // Menyematkan data pemain ke setiap objek pertandingan
        $pertandingan_list->each(function ($pertandingan) use ($pemain_by_unit_id) {
            $pertandingan->pemain_unit_1 = $pemain_by_unit_id->get($pertandingan->unit1_id, collect());
            $pertandingan->pemain_unit_2 = $pemain_by_unit_id->get($pertandingan->unit2_id, collect());
        });

        // Mengelompokkan pertandingan berdasarkan nomor ronde
        $rounds = $pertandingan_list->groupBy('round_number');

        // 1. Ambil SEMUA pemain terverifikasi untuk kelas ini
        $allApprovedPlayers = Player::where('kelas_pertandingan_id', $kelas->id)
            ->where('status', 2) // Status 2 = Approved
            ->with('contingent') // Muat relasi kontingen
            ->orderBy('contingent_id')
            ->orderBy('name')
            ->get();

        // 2. Ambil semua ID pemain yang SUDAH ADA di dalam bracket
        $assigned_player_ids = collect();
        if ($unit_ids->isNotEmpty()) {
            $assigned_player_ids = BracketPeserta::where('kelas_pertandingan_id', $kelas->id)
                ->whereIn('unit_id', $unit_ids)
                ->pluck('player_id');
        }

        // 3. Tentukan pemain yang BELUM DITEMPATKAN dengan memfilter koleksi semua pemain
        $unassignedPlayers = $allApprovedPlayers->whereNotIn('id', $assigned_player_ids);

        return view('admin.bracketShow', [
            'kelas' => $kelas,
            'rounds' => $rounds,
            'allApprovedPlayers' => $allApprovedPlayers, // <-- Kirim semua pemain
            'unassignedPlayers' => $unassignedPlayers,
            'totalRounds' => $rounds->keys()->max() ?? 0
        ]);
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
