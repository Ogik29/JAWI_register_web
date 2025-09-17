<?php

namespace App\Models;

use App\Models\DetailPointTanding;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pertandingan extends Model
{
    use HasFactory;

    protected $table = 'pertandingan';

    /**
     * Menggunakan $guarded = [] adalah cara cepat untuk mengizinkan semua field diisi.
     * Ini aman selama Anda melakukan validasi yang benar di controller.
     */
    protected $guarded = ['id'];

    // --- Relasi-relasi yang dibutuhkan ---

    public function kelasPertandingan()
    {
        return $this->belongsTo(KelasPertandingan::class, 'kelas_pertandingan_id');
    }

    // Kita tidak lagi menggunakan relasi ini, tapi tidak apa-apa jika masih ada.
    // public function player1() ...
    // public function player2() ...
    // public function winner() ...

    public function nextMatch()
    {
        return $this->belongsTo(Pertandingan::class, 'next_match_id');
    }

    public function arena()
    {
        return $this->belongsTo(Arena::class, 'arena_id');
    }

    public function getPemainUnit1Attribute()
    {
        return BracketPeserta::where('kelas_pertandingan_id', $this->kelas_pertandingan_id)
            ->where('unit_id', $this->unit1_id)
            ->with('player.contingent') // Eager load relasi dari BracketPeserta
            ->get();
    }

    /**
     * [SOLUSI] Membuat 'kolom' virtual bernama 'pemainUnit2'.
     */
    public function getPemainUnit2Attribute()
    {
        return BracketPeserta::where('kelas_pertandingan_id', $this->kelas_pertandingan_id)
            ->where('unit_id', $this->unit2_id)
            ->with('player.contingent') // Eager load relasi dari BracketPeserta
            ->get();
    }

    public function detailPointTanding()
    {
        return $this->hasOne(DetailPointTanding::class, 'pertandingan_id', 'id');
    }

       public function winner()
    {
        return $this->belongsTo(Player::class, 'winner_id');
    }
}
