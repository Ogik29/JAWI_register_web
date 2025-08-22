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
        $contingents = Contingent::with(['event', 'user', 'players.kelasPertandingan.kelas'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // =================================================================
        // LOGIKA BARU: Mengelompokkan pemain untuk setiap kontingen
        // =================================================================
        foreach ($contingents as $contingent) {
            $groupedPlayers = [];

            // Kelompokkan pemain berdasarkan kelas_pertandingan_id mereka
            $playersByClass = $contingent->players->groupBy('kelas_pertandingan_id');

            foreach ($playersByClass as $playersInClass) {
                $firstPlayer = $playersInClass->first();
                if (!$firstPlayer || !$firstPlayer->kelasPertandingan || !$firstPlayer->kelasPertandingan->kelas) continue;

                $classDetails = $firstPlayer->kelasPertandingan;
                $pemainPerPendaftaran = $classDetails->kelas->jumlah_pemain ?: 1;
                $jumlahPendaftaran = ceil($playersInClass->count() / $pemainPerPendaftaran);

                // Buat "irisan" dari koleksi pemain untuk setiap pendaftaran
                for ($i = 0; $i < $jumlahPendaftaran; $i++) {
                    $pemainUntukItemIni = $playersInClass->slice($i * $pemainPerPendaftaran, $pemainPerPendaftaran);

                    if ($pemainUntukItemIni->isEmpty()) continue;

                    // Tentukan status grup (prioritas: Ditolak > Belum Bayar > Pending > Terverifikasi)
                    $status = 2; // Default Terverifikasi
                    if ($pemainUntukItemIni->contains('status', 3)) $status = 3; // Ditolak
                    elseif ($pemainUntukItemIni->contains('status', 0)) $status = 0; // Belum Bayar
                    elseif ($pemainUntukItemIni->contains('status', 1)) $status = 1; // Pending

                    // Cari pemain yang memiliki catatan (jika ada) untuk menampilkan ikon info
                    $playerWithNote = $pemainUntukItemIni->firstWhere('status', 3);

                    $groupedPlayers[] = [
                        'player_instances' => $pemainUntukItemIni,
                        'player_names' => $pemainUntukItemIni->pluck('name')->implode(', '),
                        'nama_kelas' => $classDetails->kelas->nama_kelas ?? 'N/A',
                        'gender' => $classDetails->gender,
                        'status' => $status,
                        'note_player' => $playerWithNote, // Kirim instance player yang punya catatan
                    ];
                }
            }
            // Tambahkan hasil pengelompokan ke objek kontingen
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
            // Add validation for the new optional file uploads
            'surat_rekomendasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'foto_invoice' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.unique' => 'Nama kontingen ini sudah ada di event ini. Gunakan nama lain.',
        ]);

        // Update name
        $contingent->name = $request->name;

        // Handle Surat Rekomendasi upload
        if ($request->hasFile('surat_rekomendasi')) {
            // Delete old file if it exists
            if ($contingent->surat_rekomendasi) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($contingent->surat_rekomendasi);
            }
            // Store the new file
            $contingent->surat_rekomendasi = $request->file('surat_rekomendasi')->store('contingent', 'public');
        }

        // Handle Foto Invoice upload
        if ($request->hasFile('foto_invoice')) {
            $transaction = $contingent->transactions()->first();
            if ($transaction) {
                // Delete old file if it exists
                if ($transaction->foto_invoice) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($transaction->foto_invoice);
                }
                // Store the new file and update the transaction
                $transaction->foto_invoice = $request->file('foto_invoice')->store('invoices', 'public');
                $transaction->save();
            }
        }

        // Jika statusnya 'ditolak' (2), ubah kembali menjadi 'pending' (0)
        if ($contingent->status == 2) {
            $contingent->status = 0;
        }

        $contingent->save();

        return redirect()->route('history')->with('status', 'Data kontingen berhasil diperbarui.');
    }

    public function editPlayer(Player $player)
    {
        // Otorisasi: pastikan user pemilik kontingen yang mengakses
        if ($player->contingent->user_id !== Auth::id()) {
            abort(403);
        }

        $event = $player->contingent->event;

        // Ambil semua data master untuk filter, sama seperti di halaman registrasi
        $kategoriPertandingan = KategoriPertandingan::all();
        $jenisPertandingan = JenisPertandingan::all();
        $rentangUsia = RentangUsia::all();

        // [FIX] Query untuk mengambil kelas yang tersedia dengan JOIN
        $availableClasses = KelasPertandingan::where('kelas_pertandingan.event_id', $event->id)
            ->join('kelas', 'kelas_pertandingan.kelas_id', '=', 'kelas.id')
            ->select(
                'kelas_pertandingan.id as kelas_pertandingan_id',
                'kelas.nama_kelas', // Mengambil nama_kelas dari tabel 'kelas'
                'kelas_pertandingan.gender',
                'kelas.rentang_usia_id',
                'kelas_pertandingan.kategori_pertandingan_id',
                'kelas_pertandingan.jenis_pertandingan_id'
            )
            ->get();

        // =================================================================
        // LOGIKA BARU: Cari rekan satu tim
        // =================================================================
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
            'teammates' // <-- Kirim data teammates ke view
        ));
    }

    public function updatePlayer(Request $request, Player $player)
    {
        // Otorisasi
        if ($player->contingent->user_id !== Auth::id()) {
            abort(403);
        }

        // Validasi, tambahkan kelas_pertandingan_id
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

        // Update data teks
        $player->update($request->except(['foto_ktp', 'foto_diri', 'foto_persetujuan_ortu']));

        // Handle file uploads (jika ada file baru)
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

        // Jika statusnya Ditolak (3), ubah kembali menjadi Pending (1) setelah diedit
        if ($player->status == 3) {
            $player->status = 1;
        }

        $player->save();

        // Jika kontingen induknya ditolak (2), ubah juga statusnya menjadi pending (0)
        $contingent = $player->contingent;
        if ($contingent->status == 2) {
            $contingent->status = 0;
            $contingent->save();
        }

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
        $customPaper = array(0, 0, 153.00, 242.60);
        $pdf->setPaper($customPaper, 'portrait');

        // Tampilkan PDF di browser
        // Gunakan stream() untuk menampilkan, atau download() untuk mengunduh langsung
        return $pdf->stream('kartu-peserta-' . $player->name . '.pdf');
    }
}
