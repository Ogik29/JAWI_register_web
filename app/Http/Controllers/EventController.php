<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Contingent;
use App\Models\Player;
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

    // sadsdasdsadada ==================================


        $athletes = $request->input('athletes', []);

    $pesertaList = [];

    foreach ($athletes as $index => $athlete) {
        $fotoKtp  = $this->uploadImage($request->file("athletes.$index.foto_ktp"), 'uploads/foto_ktp');
        $fotoDiri = $this->uploadImage($request->file("athletes.$index.foto_diri"), 'uploads/foto_diri');
        $fotoPersetujuanOrtu = $this->uploadImage($request->file("athletes.$index.foto_persetujuan_ortu"), 'uploads/foto_ortu');

        $peserta = Player::create([
            'name'                  => $athlete['namaLengkap'] ?? null,
            'contingent_id'         => $athlete['contingent_id'] ?? null,
            'nik'                   => $athlete['nik'] ?? null,
            'player_category_id'    => $athlete['player_category_id'] ?? null,
            'gender'                => $athlete['jenisKelamin'] ?? null,
            'no_telp'               => $athlete['noTelepon'] ?? null,
            'email'                 => $athlete['email'] ?? null,
            'tgl_lahir'             => $athlete['tanggalLahir'] ?? null,
            'foto_ktp'              => $fotoKtp,
            'foto_diri'             => $fotoDiri,
            'foto_persetujuan_ortu' => $fotoPersetujuanOrtu,
        ]);

        $pesertaList[] = $peserta;
    }

    return response()->json([
        'success' => true,
        'message' => 'Data peserta berhasil disimpan.',
        'data'    => [
            'athletes' => $pesertaList
        ]
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
