<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contingent extends Model
{
    use HasFactory;

    protected $table = 'contingent';

    protected $fillable = [
        'name', 'manajer_name', 'email', 'no_telp', 'user_id', 'event_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'contingent_id', 'id');
    }

    public function players()
    {
        return $this->hasMany(Player::class, 'contingent_id', 'id');
    }
}
