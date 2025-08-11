<?php

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

Route::get('/registMain', function () {
    return view('register.registMain');
});

Route::get('/event/1', function () {
    return view('register.registEvent');
});

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







