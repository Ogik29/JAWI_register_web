<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\historyController;
use App\Http\Controllers\SuperAdminController;
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

Route::middleware('checkRole:1,3')->group(function () {
    Route::get('/kontingen/{event_id}', [EventController::class, 'registKontingen']);
    Route::post('/kontingen/{event_id}', [EventController::class, 'storeKontingen']);
    Route::get('/history', [historyController::class, 'index'])->name('history');
    Route::put('/history/contingent/{contingent}', [historyController::class, 'updateContingent'])->name('contingent.update');
    Route::get('/history/player/{player}/edit', [historyController::class, 'editPlayer'])->name('player.edit');
    Route::put('/history/player/{player}', [historyController::class, 'updatePlayer'])->name('player.update');
    Route::delete('/history/player/{player}', [historyController::class, 'destroyPlayer'])->name('player.destroy');
    Route::get('{contingent_id}/peserta', [EventController::class, 'pesertaEvent'])->name('peserta.event');
    Route::post('/player_store', [EventController::class, 'storePeserta']);
    Route::get('/invoice/{contingent_id}', [EventController::class, 'show_invoice'])->name('invoice.show');
    Route::post('/invoice', [EventController::class, 'store_invoice'])->name('invoice.store');
});


Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth', 'checkRole:1']) // ✅ tambahkan middleware di sini
    ->group(function () {
        
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/tambah-event', [SuperAdminController::class, 'tambahEvent'])->name('tambah_event');
        Route::get('/kelola-event', [SuperAdminController::class, 'kelolaEvent'])->name('kelola_event');

        // Rute untuk 'superadmin' saja, bisa diarahkan ke dashboard
        Route::get('/', [SuperAdminController::class, 'dashboard'])->name('index');
        Route::get('/index', function () {
            return view('superadmin.index');
        });

        Route::post('/tambah-event', [SuperAdminController::class, 'storeEvent'])->name('store_event');
        Route::get('event/{event}/edit', [SuperAdminController::class, 'editEvent'])->name('event.edit');
        Route::put('event/{event}', [SuperAdminController::class, 'updateEvent'])->name('event.update');
        Route::delete('event/{event}', [SuperAdminController::class, 'destroyEvent'])->name('event.destroy');

        Route::get('/kelola_admin', [SuperAdminController::class, 'kelola_admin'])->name('kelola_admin');
        
        // Admin CRUD
        Route::get('kelola-admin/create', [SuperAdminController::class, 'createAdmin'])->name('admin.create');
        Route::post('kelola-admin', [SuperAdminController::class, 'storeAdmin'])->name('admin.store');
        Route::get('kelola-admin/{admin}/edit', [SuperAdminController::class, 'editAdmin'])->name('admin.edit');
        Route::put('kelola-admin/{admin}', [SuperAdminController::class, 'updateAdmin'])->name('admin.update');
        Route::delete('kelola-admin/{admin}', [SuperAdminController::class, 'destroyAdmin'])->name('admin.destroy');
    });



Route::middleware('auth')->group(function () {
    Route::get('/datapeserta', function () {
        return view('register.dataPeserta');
    });
});


Route::middleware('checkRole:1,2')->group(function () {
    Route::get('/admin', [adminController::class, 'index'])->name('adminIndex');
    // Rute untuk proses verifikasi
    Route::post('/admin/verify/contingent/{contingent}', [adminController::class, 'verifyContingent'])->name('admin.verify.contingent');
    Route::post('/admin/verify/player/{player}', [adminController::class, 'verifyPlayer'])->name('admin.verify.player');
});


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
