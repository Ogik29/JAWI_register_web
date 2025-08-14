<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Contingent;

use Illuminate\Http\Request;

class EventController extends Controller
{
    //
    public function index()
    {
        return view('register.registEvent');
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
        // Validasi dan simpan data kontingen
        $data = $request->validate([
            'namaKontingen' => 'required|string|max:255',
            'namaManajer' => 'required|string|max:255',
            'noTelepon' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'user_id' => 'required|integer|exists:users,id',
            'event_id' => 'required|integer|exists:events,id'
        ]);

        // Simpan data kontingen ke database
        Contingent::create([
            'name' => $data['namaKontingen'],
            'manajer_name' => $data['namaManajer'],
            'email' => $data['email'],
            'no_telp' => $data['noTelepon'],
            'user_id' => $data['user_id'],
            'event_id' => $data['event_id']
        ]);

        return response()->json([
            'success' => true,
            'data' => $data
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
        // Validasi dan simpan data peserta
        $data = $request->validate([
            'contingent_id' => 'required|integer|exists:contingents,id',
            'email' => 'required|email|max:255',
            'jenisKelamin' => 'required|string|max:10',
            'nama' => 'required|string|max:255',
            'tanggalLahir' => 'required|date',
            'kelas' => 'required|string|max:50',
            'uploadKTP' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'uploadFoto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'upload_Persetujuan' => 'required|file|mimes:jpg,jpeg,png|max:2048'
        ]);

        return $request->all();
    }
}
