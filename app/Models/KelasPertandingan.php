<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasPertandingan extends Model
{
    use HasFactory;

    protected $table = 'kelas_pertandingan';

    protected $fillable = [
        'kelas_id',
        'harga',
        'gender',
        'kategori_pertandingan_id',
        'event_id',
        'jenis_pertandingan_id'
    ];

    public function kategoriPertandingan()
    {
        return $this->belongsTo(KategoriPertandingan::class, 'kategori_pertandingan_id', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    public function jenisPertandingan()
    {
        return $this->belongsTo(JenisPertandingan::class, 'jenis_pertandingan_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public function players()
    {
        return $this->hasMany(Player::class, 'kelas_pertandingan_id', 'id');
    }
}
