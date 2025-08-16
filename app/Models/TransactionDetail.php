<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $table = 'transaction_detail';

    protected $fillable = [
        'player_invoice_id', 'player_id', 'price'
    ];

    public function playerInvoice()
    {
        return $this->belongsTo(PlayerInvoice::class, 'player_invoice_id', 'id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }
}
