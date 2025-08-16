<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerInvoice extends Model
{
    use HasFactory;

    // Nama tabel (opsional, Laravel otomatis deteksi "invoice_players" biasanya)
    protected $table = 'players_invoice';

    // Kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'foto_invoice',
        'total_price',
        'date',
    ];

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'player_invoice_id', 'id');
    }
}
