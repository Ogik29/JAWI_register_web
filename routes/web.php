<?php

use App\Models\Event;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BracketController;
use App\Http\Controllers\historyController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SuperAdmin\KelasController;

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
})->name('home');

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
    Route::get('/edit-profil-manager/{id}', [AuthController::class, 'edit'])->name('user.edit.manager');
    Route::put('/update-profil-manager/{id}', [AuthController::class, 'update'])->name('user.update.manager');
    Route::get('/kontingen/{event_id}', [EventController::class, 'registKontingen']);
    Route::post('/kontingen/{event_id}', [EventController::class, 'storeKontingen']);
    Route::get('/history', [historyController::class, 'index'])->name('history');
    Route::put('/history/contingent/{contingent}', [historyController::class, 'updateContingent'])->name('contingent.update');
    Route::get('/history/player/{player}/edit', [historyController::class, 'editPlayer'])->name('player.edit');
    Route::put('/history/player/{player}', [historyController::class, 'updatePlayer'])->name('player.update');
    Route::delete('/history/player/{player}', [historyController::class, 'destroyPlayer'])->name('player.destroy');
    Route::delete('/history/registration/destroy', [HistoryController::class, 'destroyRegistration'])->name('registration.destroy');
    Route::get('/history/player/{player}/print-card', [historyController::class, 'printCard'])->name('player.print.card');
    Route::get('{contingent_id}/peserta', [EventController::class, 'pesertaEvent'])->name('peserta.event');
    Route::post('/player_store', [EventController::class, 'storePeserta']);
    Route::get('/invoice/{contingent_id}', [EventController::class, 'show_invoice'])->name('invoice.show');
    Route::post('/invoice', [EventController::class, 'store_invoice'])->name('invoice.store');
    Route::get('/invoiceContingent/{contingent_id}', [EventController::class, 'show_invoice_contingent'])->name('invoiceContingent.show');
    Route::post('/invoice/contingent/store', [EventController::class, 'store_invoice_contingent'])->name('invoice.contingent.store');
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


        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
        Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::get('/kelas/{kela}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
        Route::put('/kelas/{kela}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{kela}', [KelasController::class, 'destroy'])->name('kelas.destroy');
    });



Route::middleware('auth')->group(function () {
    Route::get('/datapeserta', [EventController::class, 'dataPeserta']);
});


Route::middleware('checkRole:1,2')->group(function () {
    Route::get('/admin', [adminController::class, 'index'])->name('adminIndex');
    // Rute untuk proses verifikasi
    Route::post('/admin/verify/contingent/{contingent}', [adminController::class, 'verifyContingent'])->name('admin.verify.contingent');
    Route::post('/admin/verify/player/{player}', [adminController::class, 'verifyPlayer'])->name('admin.verify.player');
    // ROUTE BARU UNTUK EXPORT
    Route::get('/events/{event}/export-approved', [\App\Http\Controllers\adminController::class, 'exportApprovedParticipants'])
        ->name('admin.events.export-approved');
    // Menampilkan halaman bracket untuk kelas pertandingan tertentu
    Route::get('/bracket/{kelas}', [BracketController::class, 'show'])->name('bracket.show');
    // Aksi untuk meng-generate / mengundi (draw) bracket
    Route::post('/bracket/{kelas}/generate', [BracketController::class, 'generate'])->name('bracket.generate');
    // Aksi untuk memindahkan posisi player (untuk drag-and-drop)
    Route::post('/bracket/update-position', [BracketController::class, 'updatePosition'])->name('bracket.updatePosition');
    // Aksi untuk update skor dan menentukan pemenang
    Route::post('/bracket/update-match/{pertandingan}', [BracketController::class, 'updateMatch'])->name('bracket.updateMatch');
    // PENTING: Tambahkan route ini untuk menangani update posisi pemain via AJAX
Route::post('/bracket/update-position', [BracketController::class, 'updatePosition'])->name('bracket.updatePosition');
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
