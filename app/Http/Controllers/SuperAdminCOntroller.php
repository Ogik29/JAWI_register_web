<?php

namespace App\Http\Controllers;

use App\Models\JenisPertandingan;
use App\Models\KategoriPertandingan;
use App\Models\Event;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        // Menghitung data untuk kartu statistik
        $totalEvent = Event::count();
        $totalAdmin = User::where('role_id', 2)->count();
        $eventAktif = Event::where('status', 1)->count(); // Status 1 = Dibuka/Aktif
        $eventSelesai = Event::where('status', 2)->count(); // Status 2 = Ditutup/Selesai

        // Mengambil 5 event terbaru untuk ditampilkan di tabel
        $recentEvents = Event::latest()->take(5)->get();

        // Kirim semua data yang dibutuhkan ke view
        return view('superadmin.dashboard', compact(
            'totalEvent',
            'totalAdmin',
            'eventAktif',
            'eventSelesai',
            'recentEvents' // Kirim data event terbaru
        ));
    }

    public function tambahEvent()
    {
        $kategori_pertandingan = KategoriPertandingan::all();
        $jenis_pertandingan = JenisPertandingan::all();
        $daftar_kelas = Kelas::orderBy('nama_kelas')->get();
        // TAMBAHKAN INI: Ambil semua data rentang usia
        $daftar_rentang_usia = DB::table('rentang_usia')->get();

        return view('superadmin.tambah_event', compact('kategori_pertandingan', 'jenis_pertandingan', 'daftar_kelas', 'daftar_rentang_usia'));
    }

    public function kelolaEvent()
    {
        $events = Event::with('kelasPertandingan')
            ->latest()
            ->withCount('kelasPertandingan')
            ->get();

        return view('superadmin.kelola_event', compact('events'));
    }

    public function createAdmin()
    {
        $events = Event::select('id', 'name')->latest()->get();
        return view('superadmin.tambah_admin', compact('events'));
    }

    public function kelola_admin()
    {

        // Ambil semua PENGGUNA dengan role_id = 2, eager load relasi events (jamak)
        $admins = User::with('events')->where('role_id', 2)->latest()->get();
        $events = Event::select('id', 'name')->latest()->get();
        return view('superadmin.kelola_admin', compact('admins', 'events'));
    }

    public function storeAdmin(Request $request)
    {
        // 1. VALIDASI DATA
        // Aturan validasi ini mencakup semua field dari form Anda.
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'negara' => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
            'status' => 'required|boolean',
            'role_id' => 'required|integer', // Validasi hidden field adalah praktik yang baik

            // Validasi untuk relasi Many-to-Many
            'event_ids' => 'required|array|min:1',
            'event_ids.*' => 'exists:events,id', // Memastikan setiap ID event yang dikirim itu valid
        ]);

        // 2. BUAT USER BARU
        // Gunakan User::create untuk membuat record baru di tabel 'users'.
        // Pastikan semua field ini ada di dalam properti '$fillable' di model User Anda.
        $admin = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => bcrypt($request->password), // WAJIB: Selalu hash password!
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'negara' => $request->negara,
            'no_telp' => $request->no_telp,
            'status' => $request->status,
            'role_id' => $request->role_id, // Mengambil nilai dari hidden input (yaitu '2')
        ]);

        // 3. SIMPAN RELASI KE TABEL PIVOT
        // Setelah user berhasil dibuat, kita lampirkan event yang dipilih ke user tersebut.
        // Method sync() adalah cara terbaik untuk mengelola relasi many-to-many.
        $admin->events()->sync($request->event_ids);

        // 4. REDIRECT KEMBALI DENGAN PESAN SUKSES
        // Arahkan pengguna kembali ke halaman daftar admin.
        return redirect()->route('superadmin.kelola_admin')->with('success', 'Admin baru bernama "' . $request->nama_lengkap . '" berhasil ditambahkan.');
    }


    public function editAdmin(User $admin)
    {
        // Eager load relasi events untuk efisiensi
        $admin->load('events');

        // Ambil semua event untuk dropdown
        $events = Event::select('id', 'name')->latest()->get();

        return view('superadmin.edit_admin', compact('admin', 'events'));
    }


    public function updateAdmin(Request $request, User $admin)
    {
        // 1. VALIDASI DATA
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            // Aturan email unik diubah: abaikan email milik user yang sedang diedit
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($admin->id),
            ],
            // Password tidak wajib diisi saat edit. Hanya validasi jika diisi.
            'password' => 'nullable|string|min:8|confirmed',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'negara' => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
            'status' => 'required|boolean',
            'event_ids' => 'required|array|min:1',
            'event_ids.*' => 'exists:events,id',
        ]);

        // 2. KUMPULKAN DATA UNTUK UPDATE
        $updateData = $request->except(['password', 'password_confirmation']);

        // 3. HANYA UPDATE PASSWORD JIKA DIISI
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        // 4. UPDATE DATA USER DI DATABASE
        $admin->update($updateData);

        // 5. SINKRONISASI RELASI DI TABEL PIVOT
        // sync() akan menghapus relasi lama dan menggantinya dengan yang baru dari form
        $admin->events()->sync($request->event_ids);

        // 6. REDIRECT KEMBALI DENGAN PESAN SUKSES
        return redirect()->route('superadmin.kelola_admin')->with('success', 'Data admin "' . $admin->nama_lengkap . '" berhasil diperbarui.');
    }

    public function destroyAdmin(User $admin)
    {
        // 1. HAPUS RELASI DARI TABEL PIVOT
        // Method detach() akan menghapus semua entri untuk user ini di tabel event_user.
        // Ini adalah langkah bersih sebelum menghapus user itu sendiri.
        $admin->events()->detach();

        // 2. HAPUS USER DARI TABEL 'users'
        $admin->delete();

        // 3. REDIRECT KEMBALI DENGAN PESAN SUKSES
        return redirect()->route('superadmin.kelola_admin')->with('success', 'Admin "' . $admin->nama_lengkap . '" berhasil dihapus.');
    }




    public function storeEvent(Request $request)
    {

        // 1. VALIDASI DATA SESUAI STRUKTUR INPUT 'GROUPS'
        $validator = Validator::make($request->all(), [
            // Validasi Event Utama
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:events,slug',
            'image' => 'required|mimes:jpeg,png,jpg,webp|max:2048',
            'desc' => 'required|string',
            'type' => 'required|in:official,non-official',
            'month' => 'required|string|max:100',
            'harga_contingent' => 'required|integer|min:0',
            'total_hadiah' => 'required|integer|min:0',
            'kotaOrKabupaten' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'tgl_mulai_tanding' => 'required|date',
            'tgl_selesai_tanding' => 'required|date|after_or_equal:tgl_mulai_tanding',
            'tgl_batas_pendaftaran' => 'required|date',
            'status' => 'required|in:0,1,2',
            'cp' => 'required|string',
            'juknis' => 'nullable|string',
            'surat_rekom' => 'required|string',

            // Validasi untuk Grup Kelas Pertandingan
            'groups' => 'required|array|min:1',
            'groups.*.rentang_usia_id' => 'required|exists:rentang_usia,id',
            'groups.*.kategori_id' => 'required|exists:kategori_pertandingan,id',
            'groups.*.jenis_id' => 'required|exists:jenis_pertandingan,id',
            'groups.*.gender' => 'required|in:Laki-laki,Perempuan,Campuran',
            'groups.*.harga' => 'required|integer|min:0',
            'groups.*.kelas_ids' => 'required|array|min:1',
            'groups.*.kelas_ids.*' => 'required|exists:kelas,id',
        ], [
            // Custom error messages
            'groups.required' => 'Anda harus menambahkan setidaknya satu grup aturan.',
            'groups.*.rentang_usia_id.required' => 'Anda harus memilih rentang usia untuk setiap grup.',
            'groups.*.kelas_ids.required' => 'Anda harus memilih setidaknya satu kelas untuk setiap grup.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. HANDLE FILE UPLOAD
        $imagePath = null;
        if ($request->hasFile('image')) {
            // 1. Ambil nama file asli dan ekstensinya
            $originalFileName = $request->file('image')->getClientOriginalName();
            $extension = $request->file('image')->getClientOriginalExtension();

            // 2. Buat slug yang bersih dari nama event untuk nama file
            //    Menggunakan Str::slug memastikan tidak ada karakter aneh di nama file.
            $slug = Str::slug($request->name, '-');

            // 3. Gabungkan semuanya untuk membuat nama file yang unik dan deskriptif
            //    Format: slug-event-timestamp-unik.ekstensi
            $imageName = $slug . '-' . time() . '.' . $extension;

            // 4. Simpan file ke disk 'public' di dalam folder 'event-images'
            //    Method storeAs() akan mengembalikan path: 'event-images/nama-file-barunya.jpg'
            $imagePath = $request->file('image')->storeAs('event-images', $imageName, 'public');
        }



        // 3. BUAT DAN SIMPAN EVENT UTAMA
        $event = new Event();
        $event->name = $request->name;
        $event->slug = $request->slug;
        $event->image = $imagePath;
        $event->desc = $request->desc;
        $event->type = $request->type;
        $event->month = $request->month;
        $event->harga_contingent = $request->harga_contingent;
        $event->total_hadiah = $request->total_hadiah;
        $event->kotaOrKabupaten = $request->kotaOrKabupaten;
        $event->lokasi = $request->lokasi;
        $event->tgl_mulai_tanding = $request->tgl_mulai_tanding;
        $event->tgl_selesai_tanding = $request->tgl_selesai_tanding;
        $event->tgl_batas_pendaftaran = $request->tgl_batas_pendaftaran;
        $event->status = $request->status;
        $event->cp = $request->cp;
        $event->juknis = $request->juknis;
        $event->surat_rekom = $request->surat_rekom;
        $event->save();

        // 4. SIMPAN DATA KELAS PERTANDINGAN DENGAN LOGIC BARU
        $kelasPertandinganToInsert = [];

        foreach ($request->groups as $grupData) {
            foreach ($grupData['kelas_ids'] as $kelasId) {
                $kelasPertandinganToInsert[] = [
                    'event_id' => $event->id,
                    'kategori_pertandingan_id' => $grupData['kategori_id'],
                    'jenis_pertandingan_id' => $grupData['jenis_id'],
                    'kelas_id' => $kelasId, // Menggunakan kelas_id
                    'gender' => $grupData['gender'],
                    'harga' => $grupData['harga'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Masukkan semua data dalam satu query untuk efisiensi
        if (!empty($kelasPertandinganToInsert)) {
            DB::table('kelas_pertandingan')->insert($kelasPertandinganToInsert);
        }

        // 5. REDIRECT
        return redirect()->route('superadmin.kelola_event')->with('success', 'Event baru berhasil ditambahkan!');
    }


    public function editEvent(Event $event)
    {
        // 1. Ambil SEMUA kelas pertandingan untuk event ini secara EKSPLISIT.
        $semuaKelasPertandingan = \App\Models\KelasPertandingan::where('event_id', $event->id)
            ->with('kelas') // Eager load relasi 'kelas'
            ->get();

        // 2. Ambil semua data master seperti biasa
        $kategori_pertandingan = KategoriPertandingan::all();
        $jenis_pertandingan = JenisPertandingan::all();
        $daftar_rentang_usia = DB::table('rentang_usia')->get();
        $daftar_kelas = Kelas::orderBy('nama_kelas')->get();

        // =================================================================
        // 3. LOGIKA GROUPING BARU & SEDERHANA (TANPA GROUPKEY MANUAL)
        // =================================================================

        // Gunakan 'groupBy' dari Laravel Collection untuk mengelompokkan secara otomatis.
        // Kunci grupnya adalah kombinasi dari SEMUA aturan yang mendefinisikan sebuah form grup.
        $grupDariDatabase = $semuaKelasPertandingan->groupBy(function ($item) {
            // Lewati data yang korup jika relasi 'kelas' tidak ditemukan
            if (!$item->kelas) {
                return null;
            }
            // Kunci uniknya adalah gabungan semua properti ini.
            return $item->kategori_pertandingan_id . '-' .
                $item->jenis_pertandingan_id . '-' .
                $item->gender . '-' .
                (int)$item->harga . '-' .
                $item->kelas->rentang_usia_id;
        })->filter(); // ->filter() akan menghapus grup 'null' jika ada

        // Sekarang, kita ubah struktur hasil grouping agar sesuai dengan yang dibutuhkan oleh view.
        $eventGroups = [];
        foreach ($grupDariDatabase as $groupItems) {
            // Ambil item pertama sebagai perwakilan, karena semua item di grup ini aturannya sama.
            $representasi = $groupItems->first();

            $eventGroups[] = [
                'rentang_usia_id' => $representasi->kelas->rentang_usia_id,
                'kategori_id'     => $representasi->kategori_pertandingan_id,
                'jenis_id'        => $representasi->jenis_pertandingan_id,
                'gender'          => $representasi->gender,
                'harga'           => $representasi->harga,
                // Ambil SEMUA 'kelas_id' dari item-item di dalam grup ini.
                'kelas_ids'       => $groupItems->pluck('kelas_id')->all(),
            ];
        }

        // 4. Kirim semua data ke view, sama seperti sebelumnya.
        return view('superadmin.edit_event', [
            'event'                 => $event,
            'kategori_pertandingan' => $kategori_pertandingan,
            'jenis_pertandingan'    => $jenis_pertandingan,
            'daftar_rentang_usia'   => $daftar_rentang_usia,
            'daftar_kelas'          => $daftar_kelas,
            'eventGroups'           => $eventGroups,
        ]);
    }


    public function updateEvent(Request $request, Event $event)
    {
        // ... (method index, create, store, edit Anda di sini) ...

        /**
         * Memperbarui data event di database.
         * VERSI YANG SUDAH DIPERBAIKI TOTAL.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \App\Models\Event  $event
         * @return \Illuminate\Http\RedirectResponse
         */
        // 1. VALIDASI DATA SESUAI STRUKTUR BARU
        $validator = Validator::make($request->all(), [
            // Validasi Event Utama
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', Rule::unique('events')->ignore($event->id)],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Nullable saat update
            'desc' => 'required|string',
            'type' => 'required|in:official,non-official',
            'month' => 'required|string|max:100',
            'harga_contingent' => 'required|integer|min:0',
            'total_hadiah' => 'required|integer|min:0',
            'kotaOrKabupaten' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'tgl_mulai_tanding' => 'required|date',
            'tgl_selesai_tanding' => 'required|date|after_or_equal:tgl_mulai_tanding',
            'tgl_batas_pendaftaran' => 'required|date',
            'status' => 'required|in:0,1,2', // Menggunakan angka 0, 1, 2
            'cp' => 'required|string',
            'juknis' => 'nullable|string',
            'surat_rekom' => 'required|string',

            // Validasi untuk "Grup Aturan"
            'groups' => 'required|array|min:1',
            'groups.*.rentang_usia_id' => 'required|exists:rentang_usia,id',
            'groups.*.kategori_id' => 'required|exists:kategori_pertandingan,id',
            'groups.*.jenis_id' => 'required|exists:jenis_pertandingan,id',
            'groups.*.gender' => 'required|in:Laki-laki,Perempuan,Campuran',
            'groups.*.harga' => 'required|integer|min:0',
            'groups.*.kelas_ids' => 'required|array|min:1',
            'groups.*.kelas_ids.*' => 'required|exists:kelas,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. HANDLE FILE UPLOAD (DENGAN LOGIKA HAPUS GAMBAR LAMA)
        $imagePath = $event->image; // Defaultnya adalah gambar yang sudah ada
        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $slug = Str::slug($request->name, '-');
            $imageName = $slug . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = $request->file('image')->storeAs('event-images', $imageName, 'public');
        }

        // 3. UPDATE DATA EVENT UTAMA MENGGUNAKAN mass assignment
        $event->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'image' => $imagePath,
            'desc' => $request->desc,
            'type' => $request->type,
            'month' => $request->month,
            'harga_contingent' => $request->harga_contingent,
            'total_hadiah' => $request->total_hadiah,
            'kotaOrKabupaten' => $request->kotaOrKabupaten,
            'lokasi' => $request->lokasi,
            'tgl_mulai_tanding' => $request->tgl_mulai_tanding,
            'tgl_selesai_tanding' => $request->tgl_selesai_tanding,
            'tgl_batas_pendaftaran' => $request->tgl_batas_pendaftaran,
            'status' => $request->status,
            'cp' => $request->cp,
            'juknis' => $request->juknis,
            'surat_rekom' => $request->surat_rekom,
        ]);

        // =================================================================
        // 4. SINKRONISASI DATA KELAS PERTANDINGAN DENGAN LOGIC BARU
        // =================================================================

        // Hapus semua kelas pertandingan yang lama terkait event ini
        $event->kelasPertandingan()->delete();

        // Siapkan data baru dari "Grup Aturan" untuk di-insert
        $newKelasPertandingan = [];
        foreach ($request->groups as $grupData) {
            foreach ($grupData['kelas_ids'] as $kelasId) {
                $newKelasPertandingan[] = [
                    'event_id' => $event->id,
                    'kategori_pertandingan_id' => $grupData['kategori_id'],
                    'jenis_pertandingan_id' => $grupData['jenis_id'],
                    'kelas_id' => $kelasId, // Menggunakan kelas_id
                    'gender' => $grupData['gender'],
                    'harga' => $grupData['harga'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert semua data baru dalam satu query untuk efisiensi
        if (!empty($newKelasPertandingan)) {
            DB::table('kelas_pertandingan')->insert($newKelasPertandingan);
        }

        // 5. REDIRECT DENGAN PESAN SUKSES
        return redirect()->route('superadmin.kelola_event')->with('success', 'Event berhasil diperbarui!');
    }


    public function destroyEvent(Event $event)
    {
        // 1. HAPUS GAMBAR LAMA DARI STORAGE JIKA ADA
        // Ini mencegah file sampah tertinggal di server.
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        // 2. HAPUS RECORD EVENT DARI DATABASE
        // Jika Anda sudah mengatur onDelete('cascade') di migrasi,
        // semua kelas pertandingan yang terkait akan terhapus secara otomatis.
        $event->delete();

        // 3. REDIRECT KEMBALI DENGAN PESAN SUKSES
        return redirect()->route('superadmin.kelola_event')->with('success', 'Event "' . $event->name . '" berhasil dihapus.');
    }
}
