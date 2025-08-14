<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // Jika menggunakan Validator::make()
use App\Notifications\VerifyEmailWithStatus; // <-- 1. Tambahkan Notifikasi kustom kita
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use DB;
use Carbon\Carbon;
use Mail;

use function Laravel\Prompts\alert;

class AuthController extends Controller
{
    public function index()
    {
        return view('register.registMain');
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['status'] = 1; // hanya user dengan status 1 yang bisa login

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')
                ->with('success', 'Login berhasil, selamat datang!');
        }

        return back()->with('error', 'Email, password salah, atau akun Anda belum aktif.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil logout.');
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
        return redirect('/registMain')->with('status', 'Registrasi berhasil! Link verifikasi telah dikirim ke email Anda. (Jika pesan verifikasi email tidak muncul, coba cek pada folder spam anda)');
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
            return redirect('/')->with('status', 'Akun Anda sudah terverifikasi. Silakan login.');
        }

        // Ubah status menjadi 1 (terverifikasi) dan simpan
        $user->status = 1;
        $user->save();

        return redirect('/')->with('status', 'Email berhasil diverifikasi! Anda sekarang bisa login.');
    }

    // menampilkan view untuk mengirim link reset password
    public function showLinkRequestForm()
    {
        return view('forgotPassword.email');
    }

    // mengirim link reset password ke email user
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // menggunakan broker 'users' bawaan Laravel
        $status = Password::sendResetLink($request->only('email'));

        return $status == Password::RESET_LINK_SENT ? back()->with(['status' => __($status)]) : back()->withErrors(['email' => __($status)]);
    }

    // Menampilkan halaman form untuk mereset password.
    public function showResetForm(Request $request, $token = null)
    {
        return view('forgotPassword.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    // memproses reset password
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Menggunakan broker 'users' bawaan Laravel untuk mereset password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect('/')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
