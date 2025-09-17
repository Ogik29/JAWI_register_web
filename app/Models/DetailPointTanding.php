<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPointTanding extends Model
{
    use HasFactory;

    // Definisikan nama tabel jika tidak mengikuti konvensi Laravel (opsional)
    protected $table = 'detail_point_tanding';

    protected $guarded = ['id'];

    /**
     * Mendapatkan data pertandingan yang memiliki detail poin ini.
     */
    public function pertandingan()
    {
        return $this->belongsTo(Pertandingan::class, 'pertandingan_id', 'id');
    }
}
