<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/UnitPemasalanSeni.php
class UnitPemasalanSeni extends Model
{
    use HasFactory;
    protected $table = 'unit_pemasalan_seni';
    protected $fillable = ['unit_id', 'pertandingan_id'];

    function pertandingan()
    {
        return $this->belongsTo(Pertandingan::class, 'pertandingan_id');
    }
}
