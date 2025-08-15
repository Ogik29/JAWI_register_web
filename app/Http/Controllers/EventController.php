<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Contingent;
use App\Models\Player;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class EventController extends Controller
{
    //
    public function index()
    {
        $events = Event::all();
        return view('register.registEvent', compact('events'));
    }

    // public function registEvent($slug){
    //     $event = Event::where('slug', $slug)->firstOrFail();
    //     return view('register.registEvent', compact('event'));
    // }

    public function registKontingen($event_id)
    {
        return view('register.registKontingen', [
            'event' => Event::findOrFail($event_id)
        ]);
    }

    public function storeKontingen(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);

// Validasi dasar
$rules = [
    'namaKontingen' => 'required|string|max:255',
    'namaManajer'   => 'required|string|max:255',
    'noTelepon'     => 'required|string|max:15',
    'email'         => 'required|email|max:255',
    'user_id'       => 'required|integer|exists:users,id',
    'event_id'      => 'required|integer|exists:events,id',
];

// Kalau harga > 0, wajib upload foto invoice
if ($event->harga > 0) {
    $rules['fotoInvoice'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
}

$data = $request->validate($rules);

// Simpan data kontingen
$contingent = Contingent::create([
    'name'          => $data['namaKontingen'],
    'manajer_name'  => $data['namaManajer'],
    'email'         => $data['email'],
    'no_telp'       => $data['noTelepon'],
    'user_id'       => $data['user_id'],
    'event_id'      => $data['event_id'],
]);

// Simpan foto invoice jika harga > 0
$fotoInvoicePath = null;
if ($event->harga_contingent > 0 && $request->hasFile('fotoInvoice')) {
    $file     = $request->file('fotoInvoice');
    $ext      = $file->getClientOriginalExtension();
    $fileName = uniqid('invoice_') . '.' . $ext;
    $fotoInvoicePath = $file->storeAs('invoices', $fileName, 'public');
}

// Buat transaksi
$transaction = Transaction::create([
    'contingent_id' => $contingent->id,
    'total'         => 0, // bisa diupdate nanti
    'date'          => now(),
    'foto_invoice'  => $fotoInvoicePath,
]);

return response()->json([
    'success'     => true,
    'contingent'  => $contingent,
    'transaction' => $transaction
]);

    }


    public function pesertaEvent($contingent_id)
    {
        $contingent = Contingent::findOrFail($contingent_id);
        $event = $contingent->event;
        return view('register.registPeserta', compact('contingent', 'event'));
    }

    public function storePeserta(Request $request)
    {

        $totalHarga = 0;
        $contingent = Contingent::findOrFail($request->athletes[0]['contingent_id']);
        $hargaPlayer = $contingent->event->harga_peserta;

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Kontingen berhasil disimpan',
        //     'contingent_id' => $hargaPlayer
        // ]);

        foreach ($request->athletes as $athleteData) {
        $player = new Player();
        $player->name = $athleteData['namaLengkap'];
        $player->contingent_id = $athleteData['contingent_id'];
        $player->nik = $athleteData['nik'];
        $player->no_telp = $athleteData['noTelepon'];
        $player->email = $athleteData['email'];
        $player->gender = $athleteData['jenisKelamin'];
        $player->tgl_lahir = $athleteData['tanggalLahir'];
        $player->player_category_id = $athleteData['player_category_id'];

        

        // Upload file dengan uniqid
        if (isset($athleteData['uploadKTP']) && $athleteData['uploadKTP'] instanceof \Illuminate\Http\UploadedFile) {
            $player->foto_ktp = $athleteData['uploadKTP']->storeAs(
                'uploads/ktp',
                uniqid() . '.' . $athleteData['uploadKTP']->getClientOriginalExtension(),
                'public'
            );
        }

        if (isset($athleteData['uploadFoto']) && $athleteData['uploadFoto'] instanceof \Illuminate\Http\UploadedFile) {
            $player->foto_diri = $athleteData['uploadFoto']->storeAs(
                'uploads/foto',
                uniqid() . '.' . $athleteData['uploadFoto']->getClientOriginalExtension(),
                'public'
            );
        }

        if (isset($athleteData['uploadPersetujuan']) && $athleteData['uploadPersetujuan'] instanceof \Illuminate\Http\UploadedFile) {
            $player->foto_persetujuan_ortu = $athleteData['uploadPersetujuan']->storeAs(
                'uploads/persetujuan',
                uniqid() . '.' . $athleteData['uploadPersetujuan']->getClientOriginalExtension(),
                'public'
            );
        }

        $player->save();
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Semua atlet berhasil disimpan'
    ]);

    }

    private function uploadImage($file, $path)
{
    if (!$file) {
        return null;
    }

    $ext = $file->getClientOriginalExtension();
    $fileName = uniqid() . '.' . $ext;
    $file->move(public_path($path), $fileName);

    return $path . '/' . $fileName; // simpan path relatif
}
}
