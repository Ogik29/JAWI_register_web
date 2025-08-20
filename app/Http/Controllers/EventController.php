<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Contingent;
use App\Models\JenisPertandingan;
use App\Models\KategoriPertandingan;
use App\Models\Player;
use App\Models\Transaction;
use App\Models\PlayerInvoice;
use App\Models\TransactionDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class EventController extends Controller
{
    //
    public function index()
    {
        $events = Event::orderBy('created_at', 'desc')->get();
        return view('register.registEvent', compact('events'));
    }

    // public function registEvent($slug){
    //     $event = Event::where('slug', $slug)->firstOrFail();
    //     return view('register.registEvent', compact('event'));
    // }

    public function registKontingen($event_id)
    {
        return view('register.registKontingen', [
            'event' => Event::findOrFail($event_id)
        ]);
    }

    public function storeKontingen(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);

        $request->merge([
            'namaManajer' => Auth::user()->nama_lengkap,
            'noTelepon'   => Auth::user()->no_telp,
            'email'       => Auth::user()->email,
        ]);

        $rules = [
            'namaKontingen' => [
                'required',
                'string',
                'max:255',
                Rule::unique('contingent', 'name')->where('event_id', $event_id),
            ],
            'namaManajer'   => 'required|string|max:255',
            'noTelepon'     => 'required|string|max:15',
            'email'         => 'required|email|max:255',
            'user_id'       => 'required|integer|exists:users,id',
            'event_id'      => 'required|integer|exists:events,id',
            'surat_rekomendasi' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        $messages = [
            'namaKontingen.unique' => 'Nama kontingen ini sudah terdaftar di event ini. Silakan gunakan nama lain.',
            'surat_rekomendasi.required' => 'Surat rekomendasi wajib diunggah.',
        ];

        if ($event->harga_contingent > 0) {
            $rules['fotoInvoice'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // upload surat rekomendasi
        $rekomendasiPath = null;
        if ($request->hasFile('surat_rekomendasi')) {
            $file = $request->file('surat_rekomendasi');
            $ext = $file->getClientOriginalExtension();
            $fileName = uniqid('rekomendasi_') . '.' . $ext;
            $rekomendasiPath = $file->storeAs('contingent', $fileName, 'public');
        }

        $contingent = Contingent::create([
            'name'                => $data['namaKontingen'],
            'manajer_name'        => $data['namaManajer'],
            'email'               => $data['email'],
            'no_telp'             => $data['noTelepon'],
            'user_id'             => $data['user_id'],
            'event_id'            => $data['event_id'],
            'surat_rekomendasi'   => $rekomendasiPath, // Simpan path file ke database
        ]);

        $fotoInvoicePath = null;
        if ($event->harga_contingent > 0 && $request->hasFile('fotoInvoice')) {
            $file = $request->file('fotoInvoice');
            $ext = $file->getClientOriginalExtension();
            $fileName = uniqid('invoice_') . '.' . $ext;
            $fotoInvoicePath = $file->storeAs('invoices', $fileName, 'public');
        }

        Transaction::create([
            'contingent_id' => $contingent->id,
            'total'         => 0,
            'date'          => now(),
            'foto_invoice'  => $fotoInvoicePath,
        ]);

        return response()->json([
            'success'      => true,
            'message'      => 'Pendaftaran berhasil! Anda akan dialihkan...',
            'redirect_url' => route('peserta.event', ['contingent_id' => $contingent->id])
        ]);
    }


    public function pesertaEvent($contingent_id)
    {
         // 1. Ambil data dasar (Kontingen dan Event)
        $contingent = Contingent::findOrFail($contingent_id);
        $event = $contingent->event;

        // 2. Ambil semua data master yang dibutuhkan untuk filter di view
        $kategoriPertandingan = KategoriPertandingan::all();
        $jenisPertandingan = JenisPertandingan::all();
        
        // =================================================================
        // PERBAIKAN 1: Mengambil data Rentang Usia yang dibutuhkan oleh view
        // =================================================================
        $rentangUsia = DB::table('rentang_usia')->get();

        // =================================================================
        // PERBAIKAN 2: Mengambil data Kelas dengan JOIN agar lengkap
        // Nama variabel diubah menjadi 'availableClasses' agar sesuai dengan view
        // =================================================================
        $availableClasses = DB::table('kelas_pertandingan')
            ->where('kelas_pertandingan.event_id', $event->id)
            ->join('kelas', 'kelas_pertandingan.kelas_id', '=', 'kelas.id')
            ->select(
                'kelas_pertandingan.id as kelas_pertandingan_id',
                'kelas.nama_kelas',
                'kelas_pertandingan.kategori_pertandingan_id',
                'kelas_pertandingan.jenis_pertandingan_id',
                'kelas.rentang_usia_id', // Data ini krusial untuk filter
                'kelas_pertandingan.gender',
                'kelas_pertandingan.harga'
            )
            ->get();

        // 3. Kirim semua data yang sudah disiapkan ke view
        // Menggunakan array asosiatif agar lebih jelas
        return view('register.registPeserta', [
            'contingent' => $contingent,
            'event' => $event,
            'kategoriPertandingan' => $kategoriPertandingan,
            'jenisPertandingan' => $jenisPertandingan,
            'rentangUsia' => $rentangUsia, // <-- Mengirim variabel $rentangUsia
            'availableClasses' => $availableClasses, // <-- Mengirim data kelas yang sudah lengkap
        ]);
    }

    public function storePeserta(Request $request)
    {

        // return $request->all();

        // 2. Siapkan array untuk menyimpan detail setiap atlet dan total harga
        // $processedAthletesDetails = [];
        // $totalHarga = 0;

        foreach ($request->athletes as $athleteData) {
            $player = new Player();
            $player->name = $athleteData['namaLengkap'];
            $player->contingent_id = $athleteData['contingent_id'];
            $player->nik = $athleteData['nik'];
            $player->no_telp = $athleteData['noTelepon'];
            $player->email = $athleteData['email'];
            $player->gender = $athleteData['jenisKelamin'];
            $player->tgl_lahir = $athleteData['tanggalLahir'];

            $player->kelas_pertandingan_id = $athleteData['kelas_pertandingan_id'];

            $contingent_id = $athleteData['contingent_id'];

            // Upload file dengan uniqid
            if (isset($athleteData['uploadKTP']) && $athleteData['uploadKTP'] instanceof \Illuminate\Http\UploadedFile) {
                $player->foto_ktp = $athleteData['uploadKTP']->storeAs(
                    'uploads/ktp',
                    uniqid() . '.' . $athleteData['uploadKTP']->getClientOriginalExtension(),
                    'public'
                );
            }

            if (isset($athleteData['uploadFoto']) && $athleteData['uploadFoto'] instanceof \Illuminate\Http\UploadedFile) {
                $player->foto_diri = $athleteData['uploadFoto']->storeAs(
                    'uploads/foto',
                    uniqid() . '.' . $athleteData['uploadFoto']->getClientOriginalExtension(),
                    'public'
                );
            }

            if (isset($athleteData['uploadPersetujuan']) && $athleteData['uploadPersetujuan'] instanceof \Illuminate\Http\UploadedFile) {
                $player->foto_persetujuan_ortu = $athleteData['uploadPersetujuan']->storeAs(
                    'uploads/persetujuan',
                    uniqid() . '.' . $athleteData['uploadPersetujuan']->getClientOriginalExtension(),
                    'public'
                );
            }

            $player->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Semua atlet berhasil disimpan',
            'contingent' => $contingent_id
        ]);
    }

    private function uploadImage($file, $path)
    {
        if (!$file) {
            return null;
        }

        $ext = $file->getClientOriginalExtension();
        $fileName = uniqid() . '.' . $ext;

        $storedPath = $file->storeAs($path, $fileName, 'public');

        return $storedPath;
    }


    public function store_invoice(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'total_price'    => 'required|numeric',
            'foto_invoice'   => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // Max 5MB
            'pemain'         => 'required|array',
            'pemain.*.player_id' => 'required|integer|exists:players,id',
            'pemain.*.price' => 'required|numeric',
        ]);

        // 2. Panggil fungsi uploadImage untuk memproses dan menyimpan file
        // Fungsi ini akan mengembalikan path relatif file yang disimpan (cth: 'invoices/namafile.jpg')
        $dbPath = $this->uploadImage($request->file('foto_invoice'), 'invoices');

        // 3. Simpan data ke Model PlayerInvoice
        $invoice = new PlayerInvoice();
        $invoice->foto_invoice = $dbPath; // Gunakan path yang dikembalikan dari fungsi upload
        $invoice->total_price = $request->total_price;
        $invoice->date = now();
        $invoice->save(); // Menyimpan ke database

        // 4. Loop dan simpan data ke Model TransactionDetail
        foreach ($request->pemain as $pemainData) {
            $detail = new TransactionDetail();
            $detail->player_id = $pemainData['player_id'];
            $detail->price = $pemainData['price'];
            $detail->player_invoice_id = $invoice->id;
            $detail->save();
            Player::find($pemainData['player_id'])->update(['status' => 1]);
        }

        // 5. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect('/history')->with('success', 'Bukti transfer dan data invoice berhasil disimpan!');
    }


    public function show_invoice($contingent_id)
    {
        $contingent = Contingent::findOrFail($contingent_id);
        $players = $contingent->players()->where('status', 0)->get();
        $totalHarga = 0;

        if ($players->isEmpty()) {
            return redirect($contingent_id . '/peserta')->with('error', 'Tidak ada pemain yang terdaftar untuk kontingen ini.');
        }
        foreach ($players as $player) {
            $data[] = [
                'player_id' => $player->id,
                'name' => $player->name,
                'nik' => $player->nik,
                'kelas' => $player->kelasPertandingan->kelas->nama_kelas ?? '-',
                'harga' => $player->kelasPertandingan->harga ?? 0
            ];

            $totalHarga += $player->kelasPertandingan->harga ?? 0;
        }


        return view('invoice.invoice', compact('contingent', 'players', 'data', 'totalHarga'));
    }
}
