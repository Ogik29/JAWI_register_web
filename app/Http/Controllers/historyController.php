<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Contingent;
use App\Models\RentangUsia;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use App\Models\JenisPertandingan;
use App\Models\KelasPertandingan;
use App\Http\Controllers\Controller;
use App\Models\KategoriPertandingan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class historyController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $contingents = Contingent::with([
            'event', 
            'user', 
            'players.kelasPertandingan.kelas.rentangUsia',
            'players.kelasPertandingan.kategoriPertandingan',
            'players.kelasPertandingan.jenisPertandingan',
            'players.playerInvoice'
        ])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($contingents as $contingent) {
            $groupedPlayers = [];
            
            $playersByGroup = $contingent->players->groupBy(function ($player) {
                if ($player->playerInvoice) {
                    return 'inv-' . $player->playerInvoice->id;
                }
                return 'noinv-' . $player->kelas_pertandingan_id;
            });

            foreach ($playersByGroup as $groupKey => $playersInGroup) {
                $firstPlayer = $playersInGroup->first();
                if (!$firstPlayer) continue;

                if (str_starts_with($groupKey, 'inv-')) {
                    $invoice = $firstPlayer->playerInvoice;
                    
                    $status = 2; // Default Terverifikasi
                    if ($playersInGroup->contains('status', 3)) $status = 3;
                    elseif ($playersInGroup->contains('status', 0)) $status = 0;
                    elseif ($playersInGroup->contains('status', 1)) $status = 1;

                    $rejectedPlayersWithNotes = $playersInGroup->where('status', 3)->whereNotNull('catatan');

                    $uniqueClasses = $playersInGroup->map(function ($p) {
                        return $p->kelasPertandingan->kelas->nama_kelas ?? 'N/A';
                    })->unique()->implode(', ');

                    $uniqueGenders = $playersInGroup->map(function ($p) {
                        return $p->gender;
                    })->unique()->implode(', ');

                    $groupedPlayers[] = [
                        'player_instances' => $playersInGroup,
                        'player_names' => $playersInGroup->pluck('name')->implode(', '),
                        'nama_kelas' => $uniqueClasses,
                        'gender' => $uniqueGenders,
                        'status' => $status,
                        'rejected_players' => $rejectedPlayersWithNotes,
                        'invoice' => $invoice,
                    ];
                } else {
                    $classDetails = $firstPlayer->kelasPertandingan;
                    if (!$classDetails || !$classDetails->kelas) continue;

                    $pemainPerPendaftaran = $classDetails->kelas->jumlah_pemain ?: 1;
                    $jumlahPendaftaran = ceil($playersInGroup->count() / $pemainPerPendaftaran);

                    for ($i = 0; $i < $jumlahPendaftaran; $i++) {
                        $pemainUntukItemIni = $playersInGroup->slice($i * $pemainPerPendaftaran, $pemainPerPendaftaran);
                        if ($pemainUntukItemIni->isEmpty()) continue;

                        $status = 2; // Default Terverifikasi
                        if ($pemainUntukItemIni->contains('status', 3)) $status = 3;
                        elseif ($pemainUntukItemIni->contains('status', 0)) $status = 0;
                        elseif ($pemainUntukItemIni->contains('status', 1)) $status = 1;

                        $rejectedPlayersWithNotes = $pemainUntukItemIni->where('status', 3)->whereNotNull('catatan');

                        $groupedPlayers[] = [
                            'player_instances' => $pemainUntukItemIni,
                            'player_names' => $pemainUntukItemIni->pluck('name')->implode(', '),
                            'nama_kelas' => $classDetails->kelas->nama_kelas ?? 'N/A',
                            'gender' => $classDetails->gender,
                            'status' => $status,
                            'rejected_players' => $rejectedPlayersWithNotes,
                            'invoice' => null,
                        ];
                    }
                }
            }
            $contingent->displayPlayers = $groupedPlayers;
        }

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
            'surat_rekomendasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'foto_invoice' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.unique' => 'Nama kontingen ini sudah ada di event ini. Gunakan nama lain.',
        ]);

        $contingent->name = $request->name;

        if ($request->hasFile('surat_rekomendasi')) {
            if ($contingent->surat_rekomendasi) {
                Storage::disk('public')->delete($contingent->surat_rekomendasi);
            }
            $contingent->surat_rekomendasi = $request->file('surat_rekomendasi')->store('contingent', 'public');
        }

        if ($request->hasFile('foto_invoice')) {
            $transaction = $contingent->transactions()->first();
            if ($transaction) {
                if ($transaction->foto_invoice) {
                    Storage::disk('public')->delete($transaction->foto_invoice);
                }
                $transaction->foto_invoice = $request->file('foto_invoice')->store('invoices', 'public');
                $transaction->save();
            }
        }

        if ($contingent->status == 2) {
            $contingent->status = 0;
        }

        $contingent->save();
        return redirect()->route('history')->with('status', 'Data kontingen berhasil diperbarui.');
    }

    public function editPlayer(Player $player)
    {
        if ($player->contingent->user_id !== Auth::id()) {
            abort(403);
        }

        $event = $player->contingent->event;
        $kategoriPertandingan = KategoriPertandingan::all();
        $jenisPertandingan = JenisPertandingan::all();
        $rentangUsia = RentangUsia::all();
        $availableClasses = KelasPertandingan::where('kelas_pertandingan.event_id', $event->id)
            ->join('kelas', 'kelas_pertandingan.kelas_id', '=', 'kelas.id')
            ->select('kelas_pertandingan.id as kelas_pertandingan_id', 'kelas.nama_kelas', 'kelas_pertandingan.gender', 'kelas.rentang_usia_id', 'kelas_pertandingan.kategori_pertandingan_id', 'kelas_pertandingan.jenis_pertandingan_id')
            ->get();

        $teammates = Player::where('contingent_id', $player->contingent_id)
            ->where('kelas_pertandingan_id', $player->kelas_pertandingan_id)
            ->where('id', '!=', $player->id)
            ->get();

        return view('historyContingent.editPlayer', compact(
            'player',
            'event',
            'kategoriPertandingan',
            'jenisPertandingan',
            'rentangUsia',
            'availableClasses',
            'teammates'
        ));
    }

    public function updatePlayer(Request $request, Player $player)
    {
        if ($player->contingent->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|size:16',
            'gender' => 'required|string',
            'tgl_lahir' => 'required|date',
            'kelas_pertandingan_id' => 'required|integer|exists:kelas_pertandingan,id',
            'foto_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_diri' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'foto_persetujuan_ortu' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $player->update($request->except(['foto_ktp', 'foto_diri', 'foto_persetujuan_ortu']));

        if ($request->hasFile('foto_ktp')) {
            if ($player->foto_ktp) Storage::disk('public')->delete($player->foto_ktp);
            $player->foto_ktp = $request->file('foto_ktp')->store('player_documents', 'public');
        }

        if ($request->hasFile('foto_diri')) {
            if ($player->foto_diri) Storage::disk('public')->delete($player->foto_diri);
            $player->foto_diri = $request->file('foto_diri')->store('player_documents', 'public');
        }

        if ($request->hasFile('foto_persetujuan_ortu')) {
            if ($player->foto_persetujuan_ortu) Storage::disk('public')->delete($player->foto_persetujuan_ortu);
            $player->foto_persetujuan_ortu = $request->file('foto_persetujuan_ortu')->store('player_documents', 'public');
        }

        if ($player->status == 3) {
            $player->status = 1;
        }

        $player->save();

        $contingent = $player->contingent;
        if ($contingent->status == 2) {
            $contingent->status = 0;
            $contingent->save();
        }

        return redirect()->route('history')->with('status', 'Data peserta berhasil diperbarui.');
    }

    public function destroyPlayer(Player $player)
    {
        if ($player->contingent->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak untuk menghapus peserta ini.');
        }

        if ($player->foto_ktp) Storage::disk('public')->delete($player->foto_ktp);
        if ($player->foto_diri) Storage::disk('public')->delete($player->foto_diri);
        if ($player->foto_persetujuan_ortu) Storage::disk('public')->delete($player->foto_persetujuan_ortu);

        $player->delete();
        return redirect()->route('history')->with('status', 'Data peserta berhasil dihapus.');
    }

    // hapus peserta yang ber-group
    public function destroyRegistration(Request $request)
    {
        // Validasi bahwa kita menerima array ID pemain.
        $request->validate([
            'player_ids' => 'required|array',
            'player_ids.*' => 'exists:players,id' // Memastikan setiap ID valid.
        ]);

        $playerIds = $request->input('player_ids');

        // Ambil semua model Player berdasarkan ID yang diterima.
        $players = Player::whereIn('id', $playerIds)->get();

        // Pastikan semua pemain yang akan dihapus dimiliki oleh user yang sedang login.
        foreach ($players as $player) {
            if ($player->contingent->user_id !== Auth::id()) {
                abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk menghapus salah satu peserta ini.');
            }
        }

        // Lakukan penghapusan (termasuk file dari storage).
        foreach ($players as $player) {
            if ($player->foto_ktp) Storage::disk('public')->delete($player->foto_ktp);
            if ($player->foto_diri) Storage::disk('public')->delete($player->foto_diri);
            if ($player->foto_persetujuan_ortu) Storage::disk('public')->delete($player->foto_persetujuan_ortu);

            $player->delete();
        }

        return redirect()->route('history')->with('status', 'Pendaftaran tim berhasil dihapus.');
    }

    // public function printCard(Player $player)
    // {
    //     if ($player->contingent->user_id !== Auth::id()) {
    //         abort(403, 'Akses ditolak.');
    //     }

    //     if ($player->status != 2) {
    //         return redirect('/history')->with('status', 'Tunggu sampai pemain diverifikasi terlebih dahulu!');
    //     }

    //     // PERBAIKAN UTAMA: Eager Load semua relasi yang dibutuhkan oleh view
    //     $player->load([
    //         'contingent.event',
    //         'kelasPertandingan.kelas.rentangUsia'
    //     ]);

    //     $data = ['player' => $player];

    //     $pdf = Pdf::loadView('pdf.player_card', $data);

    //     // Ukuran kertas custom yang proporsional seperti kartu (misal: 9cm x 14cm)
    //     // Ukuran dalam points (1pt = 1/72 inch). 9cm ≈ 255pt, 14cm ≈ 397pt
    //     $customPaper = array(0, 0, 255, 397);
    //     $pdf->setPaper($customPaper, 'portrait');

    //     return $pdf->stream('kartu-peserta-' . $player->name . '.pdf');
    // }

    public function printCard(Player $player)
    {
        if ($player->contingent->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        if ($player->status != 2) {
            return redirect('/history')->with('status', 'Tunggu sampai pemain diverifikasi terlebih dahulu!');
        }

        // Siapkan data yang akan dikirim ke view PDF
        $data = [
            'player' => $player
        ];

        // Muat view, kirim data, dan buat PDF
        $pdf = Pdf::loadView('pdf.player_card', $data);

        // ukuran kertas seukuran KTP potret
        // Format: [x, y, width, height] dalam points
        $customPaper = array(0, 0, 255, 397);
        $pdf->setPaper($customPaper, 'portrait');

        // Tampilkan PDF di browser
        // Gunakan stream() untuk menampilkan, atau download() untuk mengunduh langsung
        return $pdf->stream('kartu-peserta-' . $player->name . '.pdf');
    }

    public function reuploadPlayerInvoice(Request $request, $invoice_id)
    {
        $request->validate([
            'foto_invoice' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'players' => 'required|array',
            'players.*.name' => 'required|string|max:255',
            'players.*.nik' => 'required|string|size:16',
            'players.*.gender' => 'required|string|in:Laki-laki,Perempuan',
            'players.*.tgl_lahir' => 'required|date',
            'players.*.foto_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'players.*.foto_diri' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'players.*.foto_persetujuan_ortu' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $invoice = \App\Models\PlayerInvoice::with('transactionDetails.player.contingent')->findOrFail($invoice_id);

        $firstDetail = $invoice->transactionDetails->first();
        if (!$firstDetail || !$firstDetail->player || !$firstDetail->player->contingent || $firstDetail->player->contingent->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // 1. Simpan foto bukti transfer baru jika diunggah
        if ($request->hasFile('foto_invoice')) {
            if ($invoice->foto_invoice) {
                Storage::disk('public')->delete($invoice->foto_invoice);
            }
            $path = $request->file('foto_invoice')->store('invoices', 'public');
            $invoice->foto_invoice = $path;
            $invoice->save();
        }

        // 2. Update data diri untuk masing-masing atlet di dalam invoice ini
        $playersInput = $request->input('players');
        foreach ($invoice->transactionDetails as $detail) {
            $player = $detail->player;
            if (!$player || !isset($playersInput[$player->id])) {
                continue;
            }

            $pData = $playersInput[$player->id];

            $fotoKtpPath = $player->foto_ktp;
            if ($request->hasFile("players.{$player->id}.foto_ktp")) {
                if ($player->foto_ktp) Storage::disk('public')->delete($player->foto_ktp);
                $fotoKtpPath = $request->file("players.{$player->id}.foto_ktp")->store('player_documents', 'public');
            }

            $fotoDiriPath = $player->foto_diri;
            if ($request->hasFile("players.{$player->id}.foto_diri")) {
                if ($player->foto_diri) Storage::disk('public')->delete($player->foto_diri);
                $fotoDiriPath = $request->file("players.{$player->id}.foto_diri")->store('player_documents', 'public');
            }

            $fotoIzinPath = $player->foto_persetujuan_ortu;
            if ($request->hasFile("players.{$player->id}.foto_persetujuan_ortu")) {
                if ($player->foto_persetujuan_ortu) Storage::disk('public')->delete($player->foto_persetujuan_ortu);
                $fotoIzinPath = $request->file("players.{$player->id}.foto_persetujuan_ortu")->store('player_documents', 'public');
            }

            $player->update([
                'name' => $pData['name'],
                'nik' => $pData['nik'],
                'gender' => $pData['gender'],
                'tgl_lahir' => $pData['tgl_lahir'],
                'foto_ktp' => $fotoKtpPath,
                'foto_diri' => $fotoDiriPath,
                'foto_persetujuan_ortu' => $fotoIzinPath,
                'status' => 1, // Reset kembali ke pending
                'catatan' => null // Bersihkan catatan penolakan admin
            ]);
        }

        // Kembalikan status kontingen ke pending data verification jika sebelumnya lunas agar divalidasi ulang
        $contingent = $firstDetail->player->contingent;
        if ($contingent->status == 2) {
            $contingent->status = 0;
            $contingent->save();
        }

        return redirect()->route('history')->with('status', 'Pembaruan data pendaftaran & bukti transfer berhasil disimpan! Menunggu verifikasi ulang oleh admin.');
    }

    public function updatePlayerGroup(Request $request)
    {
        $request->validate([
            'players' => 'required|array',
            'players.*.name' => 'required|string|max:255',
            'players.*.nik' => 'required|string|size:16',
            'players.*.gender' => 'required|string|in:Laki-laki,Perempuan',
            'players.*.tgl_lahir' => 'required|date',
            'players.*.foto_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'players.*.foto_diri' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'players.*.foto_persetujuan_ortu' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $playersInput = $request->input('players');
        $firstPlayerId = key($playersInput);
        if (!$firstPlayerId) {
            return back()->with('error', 'Tidak ada data atlet.');
        }

        $firstPlayer = Player::findOrFail($firstPlayerId);
        if ($firstPlayer->contingent->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        foreach ($playersInput as $playerId => $pData) {
            $player = Player::findOrFail($playerId);
            if ($player->contingent->user_id !== Auth::id()) {
                continue;
            }

            $fotoKtpPath = $player->foto_ktp;
            if ($request->hasFile("players.{$player->id}.foto_ktp")) {
                if ($player->foto_ktp) Storage::disk('public')->delete($player->foto_ktp);
                $fotoKtpPath = $request->file("players.{$player->id}.foto_ktp")->store('player_documents', 'public');
            }

            $fotoDiriPath = $player->foto_diri;
            if ($request->hasFile("players.{$player->id}.foto_diri")) {
                if ($player->foto_diri) Storage::disk('public')->delete($player->foto_diri);
                $fotoDiriPath = $request->file("players.{$player->id}.foto_diri")->store('player_documents', 'public');
            }

            $fotoIzinPath = $player->foto_persetujuan_ortu;
            if ($request->hasFile("players.{$player->id}.foto_persetujuan_ortu")) {
                if ($player->foto_persetujuan_ortu) Storage::disk('public')->delete($player->foto_persetujuan_ortu);
                $fotoIzinPath = $request->file("players.{$player->id}.foto_persetujuan_ortu")->store('player_documents', 'public');
            }

            $player->update([
                'name' => $pData['name'],
                'nik' => $pData['nik'],
                'gender' => $pData['gender'],
                'tgl_lahir' => $pData['tgl_lahir'],
                'foto_ktp' => $fotoKtpPath,
                'foto_diri' => $fotoDiriPath,
                'foto_persetujuan_ortu' => $fotoIzinPath,
                'status' => $player->status == 3 ? 1 : $player->status, // Reset ke pending jika ditolak
                'catatan' => null
            ]);
        }

        $contingent = $firstPlayer->contingent;
        if ($contingent->status == 2) {
            $contingent->status = 0;
            $contingent->save();
        }

        return redirect()->route('history')->with('status', 'Data atlet berhasil diperbarui.');
    }
}
