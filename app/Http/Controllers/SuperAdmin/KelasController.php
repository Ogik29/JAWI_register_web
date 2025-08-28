<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\RentangUsia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar semua kelas.
     */
    public function index()
    {
        // Ambil semua kelas beserta relasi rentangUsia untuk ditampilkan
        $daftarKelas = Kelas::with('rentangUsia')->latest()->paginate(20);
        return view('superadmin.kelas.index', compact('daftarKelas'));
    }

    /**
     * Menampilkan form untuk membuat kelas baru.
     */
    public function create()
    {
        // Ambil semua rentang usia untuk mengisi pilihan di dropdown
        $daftarRentangUsia = RentangUsia::orderBy('id')->get();
        return view('superadmin.kelas.create', compact('daftarRentangUsia'));
    }

    /**
     * Menyimpan kelas baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'rentang_usia_id' => 'required|exists:rentang_usia,id',
        ]);

        Kelas::create($validatedData);

        return redirect()->route('superadmin.kelas.index')->with('success', 'Kelas baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit kelas yang ada.
     */
    public function edit(Kelas $kela) // Route Model Binding
    {
        $daftarRentangUsia = RentangUsia::orderBy('id')->get();
        return view('superadmin.kelas.edit', [
            'kelas' => $kela, // Variabel diubah menjadi 'kelas' agar lebih jelas di view
            'daftarRentangUsia' => $daftarRentangUsia,
        ]);
    }

    /**
     * Memperbarui kelas yang ada di database.
     */
    public function update(Request $request, Kelas $kela)
    {
        $validatedData = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'rentang_usia_id' => 'required|exists:rentang_usia,id',
        ]);

        $kela->update($validatedData);

        return redirect()->route('superadmin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Menghapus kelas dari database.
     */
    public function destroy(Kelas $kela)
    {
        try {
            $kela->delete();
            return redirect()->route('superadmin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
        } catch (\Exception $e) {
            // Menangani error jika kelas tidak bisa dihapus karena masih terkait dengan data lain
            return redirect()->route('superadmin.kelas.index')->with('error', 'Gagal menghapus kelas karena masih terkait dengan data lain.');
        }
    }
}