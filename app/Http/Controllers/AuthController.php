<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // Jika menggunakan Validator::make()
use App\Notifications\VerifyEmailWithStatus; // <-- 1. Tambahkan Notifikasi kustom kita
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\alert;

class AuthController extends Controller
{
    public function index()
    {
        return view('register.registMain');
    }


    public function login(Request $request){

       $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')
                ->with('success', 'Login berhasil, selamat datang!');
        }

        return back()->with('error', 'Email atau password salah.');
    }
    



    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'negara' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
        ]);

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'negara' => $request->negara,
            'no_telp' => $request->no_telp,
            'role_id' => 2,
            'status' => 0, // <-- Status awal adalah 0
        ]);

        // 2. Kirim notifikasi kustom kita ke user yang baru dibuat
        $user->notify(new VerifyEmailWithStatus());

        // Ganti redirect ke halaman login atau halaman pemberitahuan
        return redirect('/registMain')->with('status', 'Registrasi berhasil! Link verifikasi telah dikirim ke email Anda. (Jika verifikasi email tidak muncul, coba cek pada folder spam)');
    }

    /**
     * 3. Buat method baru untuk memverifikasi email user.
     */
    public function verifyEmail(Request $request, $id)
    {
        // Pertama, validasi apakah URL memiliki tanda tangan yang valid
        if (! $request->hasValidSignature()) {
            abort(401, 'Link verifikasi tidak valid atau sudah kedaluwarsa.');
        }

        $user = User::findOrFail($id);

        // Cek jika user sudah terverifikasi sebelumnya
        if ($user->status == 1) {
            return redirect('/registMain')->with('status', 'Akun Anda sudah terverifikasi. Silakan login.');
        }

        // Ubah status menjadi 1 (terverifikasi) dan simpan
        $user->status = 1;
        $user->save();

        return redirect('/registMain')->with('status', 'Email berhasil diverifikasi! Anda sekarang bisa login.');
    }
}
