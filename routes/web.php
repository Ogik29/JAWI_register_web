<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Models\Event;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('home', [
        'data' => Event::all()
    ]);
});

Route::get('/registMain', [AuthController::class, 'index']);
Route::post('/registMain', [AuthController::class, 'register']);
Route::get('/email/verify/{id}', [AuthController::class, 'verifyEmail'])->name('verification.verify-custom'); // Nama harus sama dengan yang di Notifikasi
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
// Route untuk Lupa Password
Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');

Route::get('/event', [EventController::class, 'index']);
// Route::get('/event/{slug}', [EventController::class, 'registEvent']);

Route::middleware('checkRole:3')->group(function () {
    Route::get('/kontingen/{event_id}', [EventController::class, 'registKontingen']);
    Route::post('/kontingen/{event_id}', [EventController::class, 'storeKontingen']);
});

Route::get('{contingent_id}/peserta', [EventController::class, 'pesertaEvent'])->name('peserta.event');
Route::post('/player_store', [EventController::class, 'storePeserta']);


Route::get('/datapeserta', function () {
    return view('register.dataPeserta');
});

Route::get('/superadmin', function () {
    return view('superadmin.index');
});

Route::get('/admin', function () {
    return view('admin.index');
});

Route::get('/invoice/{contingent_id}', [EventController::class, 'show_invoice']);

Route::get('/tanding-pdf', function () {
    $filePath = storage_path('app/public/tanding.pdf');
    return response()->download($filePath, 'Ketentuan-Tanding.pdf');
});
Route::get('/seni-juruspaket-pdf', function () {
    $filePath = storage_path('app/public/seni-juruspaket.pdf');
    return response()->download($filePath, 'Ketentuan-seni-juruspaket.pdf');
});
Route::get('/ketentuankelas-pdf', function () {
    $filePath = storage_path('app/public/ketentuan-kelas.pdf');
    return response()->download($filePath, 'Ketentuan-kelas.pdf');
});
