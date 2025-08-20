<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentangUsia extends Model
{
    use HasFactory;
    protected $table = 'rentang_usia';
    protected $fillable = ['rentang_usia'];

    public function rentangUsiaEvents()
    {
        return $this->hasMany(RentangUsiaEvent::class, 'rentang_usia_id', 'id');    
    }
}
