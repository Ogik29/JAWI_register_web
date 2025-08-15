<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPertandingan extends Model
{
    use HasFactory;

    protected $table = 'jenis_pertandingan';

    protected $fillable = [
        'nama_jenis'
    ];

    public function kelasPertandingan()
    {
        return $this->hasMany(KelasPertandingan::class, 'jenis_pertandingan_id', 'id');
    }
}
