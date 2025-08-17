<?php

namespace App\Http\Controllers;

use App\Models\JenisPertandingan;
use App\Models\KategoriPertandingan;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        return view('superadmin.dashboard');
    }

    public function tambahEvent()
    {
        $kategori_pertandingan = KategoriPertandingan::all();
        $jenis_pertandingan = JenisPertandingan::all();
        return view('superadmin.tambah_event', compact('kategori_pertandingan', 'jenis_pertandingan'));
    }

    public function kelolaEvent()
    {
        $events = Event::latest()->withCount('kelasPertandingan')->get();
        return view('superadmin.kelola_event', compact('events'));
    }

    public function kelola_admin(){
        return view('superadmin.kelola_admin');
    }

    public function storeEvent(Request $request){
        // 1. VALIDASI DATA (Tidak ada perubahan di sini)
        $validator = Validator::make($request->all(), [
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
            'status' => 'required|in:belum dibuka,sudah dibuka,ditutup',
            'cp' => 'required|string',
            'juknis' => 'string',
            'kelas' => 'required|array|min:1',
            'kelas.*.kategori_id' => 'required|exists:kategori_pertandingan,id',
            'kelas.*.jenis_id' => 'required|exists:jenis_pertandingan,id',
            'kelas.*.nama_kelas' => 'required|string|max:255',
            'kelas.*.rentang_usia' => 'required|string|max:255',
            'kelas.*.gender' => 'required|in:Laki-laki,Perempuan',
            'kelas.*.harga' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $imagePath = null;
        // =================================================================
        // PERUBAHAN DIMULAI DI SINI: HANDLE FILE UPLOAD DENGAN NAMA UNIK
        // =================================================================
        if ($request->hasFile('image')) {
            // 1. Ambil slug dari request untuk nama file
            $slug = $request->slug;
            
            // 2. Ambil ekstensi asli dari file yang di-upload (misal: .jpg, .png)
            $extension = $request->file('image')->getClientOriginalExtension();

            // 3. Buat nama file baru yang unik
            // Format: slug-timestamp.ekstensi
            $imageName = $slug . '-' . time() . '.' . $extension;

            // 4. Simpan file dengan nama baru yang sudah kita buat
            // Gunakan storeAs() untuk menentukan nama file secara manual
            // Path: storage/app/public/event-images/nama-file-unik.jpg
            $imagePath = $request->file('image')->storeAs('event-images', $imageName, 'public');
        }
        // =================================================================
        // PERUBAHAN SELESAI
        // =================================================================

        // 3. BUAT DAN SIMPAN EVENT UTAMA (Tidak ada perubahan di sini)
        $event = new Event();
        $event->name = $request->name;
        $event->slug = $request->slug;
        $event->image = $imagePath; // Simpan path file ke database
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
        
        $event->save();

        // 4. SIMPAN DATA KELAS PERTANDINGAN (Tidak ada perubahan di sini)
        foreach ($request->kelas as $kelasData) {
            $event->kelasPertandingan()->create([
                'kategori_pertandingan_id' => $kelasData['kategori_id'],
                'jenis_pertandingan_id' => $kelasData['jenis_id'],
                'nama_kelas' => $kelasData['nama_kelas'],
                'rentang_usia' => $kelasData['rentang_usia'],
                'gender' => $kelasData['gender'],
                'harga' => $kelasData['harga'],
            ]);
        }

        // 5. REDIRECT (Tidak ada perubahan di sini)
        return redirect()->route('superadmin.kelola_event')->with('success', 'Event baru berhasil ditambahkan!');
    }
}
