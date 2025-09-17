<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arena extends Model
{
    use HasFactory;
    protected $table = 'arena';
    protected $fillable = ['arena_name'];
    public function pertandingan()
    {
        return $this->hasMany(Pertandingan::class, 'arena_id');
    }
    public function user_arena()
    {
        return $this->hasMany(UserArena::class, 'arena_id');
    }
}
