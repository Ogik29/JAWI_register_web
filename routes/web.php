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

Route::get('/event/{slug}', [EventController::class, 'registEvent']);

Route::get('/peserta', function () {
    return view('register.registPeserta');
});

Route::get('/kontingen', function () {
    return view('register.registKontingen');
});

Route::get('/datapeserta', function () {
    return view('register.dataPeserta');
});

Route::get('/invoice', function () {
    return view('invoice.invoice');
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
