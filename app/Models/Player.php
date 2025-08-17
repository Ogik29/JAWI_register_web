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
        'catatan'
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
}
