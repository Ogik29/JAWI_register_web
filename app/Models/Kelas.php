<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $table = 'kelas';
    protected $fillable = ['nama_kelas', 'rentang_usia_id'];

    public function rentangUsia()
    {
        return $this->belongsTo(RentangUsia::class, 'rentang_usia_id', 'id');
    }
}
