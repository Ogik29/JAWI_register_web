<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Player extends Model
{
    use HasFactory;

    protected $table = 'players';

    protected $fillable = [
        'name',
        'contingent_id',
        'nik',
        'gender',
        'no_telp',
        'email',
        'player_category_id',
        'foto_ktp',
        'foto_diri',
        'foto_persetujuan_ortu',
        'status',
        'tgl_lahir',
        'kelas_pertandingan_id',
        'catatan',
        'rentang_usia_id'
    ];

    public function contingent()
    {
        return $this->belongsTo(Contingent::class, 'contingent_id', 'id');
    }

    public function playerCategory()
    {
        return $this->belongsTo(PlayerCategory::class, 'player_category_id', 'id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'player_id', 'id');
    }

    public function kelasPertandingan()
    {
        return $this->belongsTo(KelasPertandingan::class, 'kelas_pertandingan_id', 'id');
    }

    public function playerInvoice()
    {
        return $this->hasOneThrough(
            PlayerInvoice::class,       // The final model we want to access
            TransactionDetail::class,   // The intermediate model/table
            'player_id',                // Foreign key on TransactionDetail table...
            'id',                       // Foreign key on PlayerInvoice table...
            'id',                       // Local key on Player table...
            'player_invoice_id'         // Local key on TransactionDetail table.
        );

    }



     // === RELASI BARU UNTUK BRACKET ===

    /**
     * Relasi: Mendapatkan semua pertandingan di mana pemain ini berada di slot 1.
     */
    public function matchesAsPlayer1()
    {
        return $this->hasMany(Pertandingan::class, 'player1_id');
    }

    /**
     * Relasi: Mendapatkan semua pertandingan di mana pemain ini berada di slot 2.
     */
    public function matchesAsPlayer2()
    {
        return $this->hasMany(Pertandingan::class, 'player2_id');
    }

    /**
     * Relasi: Mendapatkan semua pertandingan yang dimenangkan oleh pemain ini.
     */
    public function wonMatches()
    {
        return $this->hasMany(Pertandingan::class, 'winner_id');
    }

    /**
     * (Opsional) Method untuk menggabungkan semua pertandingan yang melibatkan pemain ini.
     */
    public function getAllMatches()
    {
        $matches1 = $this->matchesAsPlayer1()->get();
        $matches2 = $this->matchesAsPlayer2()->get();

        return $matches1->merge($matches2);
    }



    // relasi untuk bracket

  
}
