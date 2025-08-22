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
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class EventController extends Controller
{
    //
    public function index()
    {
        // Eager load the kelasPertandingan relationship to make prices available in the view.
        $events = Event::with('kelasPertandingan')->latest()->get();

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
            'redirect_url' => route('history')
        ]);
    }


    public function pesertaEvent($contingent_id)
    {
        if (Contingent::findOrFail($contingent_id)->status != 1) {
            return redirect('/history')->with('status', 'Tunggu sampai kontingen diverifikasi terlebih dahulu!');
        }

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
                'kelas_pertandingan.harga',
                'kelas.jumlah_pemain'
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

         // 1. VALIDASI DATA DENGAN STRUKTUR BARU
    $validator = Validator::make($request->all(), [
        'registrations' => 'required|array|min:1',
        'registrations.*.kelas_pertandingan_id' => 'required|exists:kelas_pertandingan,id',
        'registrations.*.players' => 'required|array|min:1',
        'registrations.*.players.*.namaLengkap' => 'required|string|max:255',
        'registrations.*.players.*.nik' => 'required|string|digits:16',
        'registrations.*.players.*.jenisKelamin' => 'required|in:Laki-laki,Perempuan',
        'registrations.*.players.*.tanggalLahir' => 'required|date',
        'registrations.*.players.*.uploadKTP' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'registrations.*.players.*.uploadFoto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        'registrations.*.players.*.uploadPersetujuan' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    try {
        DB::beginTransaction();
        $contingentId = $request->input('contingent_id');

        // 2. LOGIKA PENYIMPANAN BARU
        foreach ($request->registrations as $regIndex => $registrationData) {
            
            // Loop untuk setiap pemain di dalam satu pendaftaran kelas
            foreach ($registrationData['players'] as $playerIndex => $playerData) {
                
                $player = new Player(); // Ganti dengan model Atlet Anda
                $player->contingent_id = $contingentId;
                $player->kelas_pertandingan_id = $registrationData['kelas_pertandingan_id']; // ID kelas sama untuk semua pemain di grup ini
                
                // Ambil data dari array 'players'
                $player->name = $playerData['namaLengkap'];
                $player->nik = $playerData['nik'];
                $player->gender = $playerData['jenisKelamin'];
                $player->tgl_lahir = $playerData['tanggalLahir'];
                $player->no_telp = $playerData['noTelepon'] ?? null; // Optional
                $player->email = $playerData['email'] ?? null; // Optional
                
                // Handle File Uploads
                $nik = $playerData['nik'];
                $fileKtp = $request->file("registrations.$regIndex.players.$playerIndex.uploadKTP");
                $fileFoto = $request->file("registrations.$regIndex.players.$playerIndex.uploadFoto");
                $filePersetujuan = $request->file("registrations.$regIndex.players.$playerIndex.uploadPersetujuan");

                if ($fileKtp) {
                    $path = $fileKtp->storeAs('player-documents', "ktp-{$nik}-" . time(), 'public');
                    $player->foto_ktp = $path;
                }
                if ($fileFoto) {
                    $path = $fileFoto->storeAs('player-documents', "foto-{$nik}-" . time(), 'public');
                    $player->foto_diri = $path;
                }
                if ($filePersetujuan) {
                    $path = $filePersetujuan->storeAs('player-documents', "persetujuan-{$nik}-" . time(), 'public');
                    $player->foto_persetujuan_ortu = $path;
                }

                $player->save();
            }
        }

        DB::commit();
        return response()->json(['message' => 'Pendaftaran berhasil!', 'contingent' => $contingentId]);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Gagal menyimpan pendaftaran atlet: ' . $e->getMessage());
        return response()->json(['message' => 'Terjadi kesalahan pada server.'], 500);
    }
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
        return redirect('/history')->with('status', 'Bukti transfer dan data invoice berhasil disimpan!');
    }


    public function show_invoice($contingent_id)
    {
        // 1. Ambil kontingen dan semua pemainnya dengan relasi yang dibutuhkan
        $contingent = Contingent::with('players.kelasPertandingan.kelas')->findOrFail($contingent_id);

        // =================================================================
        // LOGIKA BARU: Mengelompokkan pemain menjadi beberapa pendaftaran terpisah
        // =================================================================
        $invoiceItems = [];
        $totalHarga = 0;

        // Langkah A: Kelompokkan semua pemain berdasarkan kelas_pertandingan_id mereka
        $playersByClass = $contingent->players->groupBy('kelas_pertandingan_id');

        // Langkah B: Proses setiap grup kelas satu per satu
        foreach ($playersByClass as $kelasPertandinganId => $playersInClass) {
            
            // Ambil detail kelas dari pemain pertama (semua sama dalam grup ini)
            $firstPlayer = $playersInClass->first();
            if (!$firstPlayer) continue; // Lewati jika grup kosong

            $classDetails = $firstPlayer->kelasPertandingan;
            $hargaPerPendaftaran = $classDetails->harga;
            // Ambil jumlah pemain yang dibutuhkan per pendaftaran (misal: 1 untuk tunggal, 2 untuk ganda)
            $pemainPerPendaftaran = $classDetails->kelas->jumlah_pemain ?: 1; // Default ke 1 jika null

            // Langkah C: Hitung berapa banyak pendaftaran terpisah yang dibuat untuk kelas ini
            $jumlahPemainTotal = $playersInClass->count();
            // Gunakan ceil() untuk membulatkan ke atas jika ada data ganjil
            $jumlahPendaftaran = ceil($jumlahPemainTotal / $pemainPerPendaftaran); 

            // Ambil semua nama dan ID pemain untuk didistribusikan
            $allPlayerNames = $playersInClass->pluck('name')->all();
            $allPlayerIds = $playersInClass->pluck('id')->all();

            // Langkah D: Buat satu baris invoice untuk setiap pendaftaran
            for ($i = 0; $i < $jumlahPendaftaran; $i++) {
                
                // "Potong" array nama & ID untuk pendaftaran saat ini
                $offset = $i * $pemainPerPendaftaran;
                $pemainUntukItemIni = array_slice($allPlayerNames, $offset, $pemainPerPendaftaran);
                $idUntukItemIni = array_slice($allPlayerIds, $offset, $pemainPerPendaftaran);

                // Buat entri baru di invoice
                $invoiceItems[] = [
                    'nama_kelas' => $classDetails->kelas->nama_kelas,
                    'gender' => $classDetails->gender,
                    'harga_per_pendaftaran' => $hargaPerPendaftaran,
                    'jumlah_pemain' => count($pemainUntukItemIni),
                    'nama_pemain' => $pemainUntukItemIni,
                    'player_ids' => $idUntukItemIni,
                ];

                // Tambahkan harga ke total untuk setiap pendaftaran
                $totalHarga += $hargaPerPendaftaran;
            }
        }

        // 3. Kirim data yang sudah terstruktur dengan benar ke view
        return view('invoice.invoice', [
            'contingent' => $contingent,
            'invoiceItems' => $invoiceItems,
            'totalHarga' => $totalHarga,
        ]);
    }

    // data peserta part
    public function dataPeserta()
    {
        // ambil semua data player dengan relasi2 yg dibutuhkan
        $players = Player::with(['contingent', 'kelasPertandingan.jenisPertandingan', 'kelasPertandingan.kategoriPertandingan'])->latest()->get();

        $contingents = Contingent::orderBy('name')->get();

        $totalContingents = $contingents->count();

        // ambil unique kategori dan class dari data player
        $kategoriPertandingan = $players->pluck('kelasPertandingan.kategoriPertandingan')->unique()->whereNotNull();
        $jenisPertandingan = $players->pluck('kelasPertandingan.jenisPertandingan')->unique()->whereNotNull();
        $kelasPertandingan = $players->pluck('kelasPertandingan.kelas')->unique()->whereNotNull();

        return view('register/datapeserta', compact(
            'players',
            'contingents',
            'totalContingents',
            'kategoriPertandingan',
            'jenisPertandingan',
            'kelasPertandingan'
        ));
    }
}
