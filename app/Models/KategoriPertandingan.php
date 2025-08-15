<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPertandingan extends Model
{
    use HasFactory;

    protected $table = 'kategori_pertandingan';

    protected $fillable = [
        'nama_kategori'
    ];

    public function kelasPertandingan()
    {
        return $this->hasMany(KelasPertandingan::class, 'kategori_pertandingan_id', 'id');
    }
}
