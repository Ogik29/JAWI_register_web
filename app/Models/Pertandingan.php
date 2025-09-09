<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pertandingan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'pertandingan';

    /**
     * Atribut yang bisa diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'kelas_pertandingan_id',
        'round_number',
        'match_number',
        'unit1_id',
        'unit2_id',
        'score1',
        'score2',
        'winner_id',
        'next_match_id',
        'status',
    ];

    /**
     * Relasi: Satu pertandingan MILIK SATU kelas_pertandingan.
     * Menggunakan method belongsTo.
     */
    public function kelasPertandingan(): BelongsTo
    {
        return $this->belongsTo(KelasPertandingan::class, 'kelas_pertandingan_id');
    }

    /**
     * Relasi: Satu pertandingan MEMILIKI SATU pemain di slot 1.
     */
    // public function player1(): BelongsTo
    // {
    //     return $this->belongsTo(Player::class, 'player1_id');
    // }

    // /**
    //  * Relasi: Satu pertandingan MEMILIKI SATU pemain di slot 2.
    //  */
    // public function player2(): BelongsTo
    // {
    //     return $this->belongsTo(Player::class, 'player2_id');
    // }

    /**
     * Relasi: Satu pertandingan MEMILIKI SATU pemain sebagai pemenang.
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'winner_id');
    }
    
    /**
     * Relasi: Pemenang dari pertandingan ini akan maju ke pertandingan berikutnya.
     */
    public function nextMatch(): BelongsTo
    {
        return $this->belongsTo(Pertandingan::class, 'next_match_id');
    }

    /**
     * Relasi (Invers): Pertandingan ini diisi oleh pemenang dari pertandingan sebelumnya.
     * Berguna untuk menelusuri bracket ke belakang.
     */
    public function previousMatches()
    {
        return $this->hasMany(Pertandingan::class, 'next_match_id');
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

}