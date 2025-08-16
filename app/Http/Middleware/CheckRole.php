<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // Menggunakan ...$roles untuk menerima satu atau lebih parameter role
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login.
        if (!auth()->check()) {
            return redirect('/')->with('status', 'Tolong Login atau Register terlebih Dahulu.');
        }

        // Cek apakah role_id user TIDAK ADA DI DALAM array $roles yang diizinkan.
        if (!in_array(auth()->user()->role_id, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Cek apakah status user tidak aktif.
        if (auth()->user()->status !== 1) {
            abort(403, 'Akun Anda tidak aktif. Akses ditolak.');
        }

        return $next($request);
    }
}
