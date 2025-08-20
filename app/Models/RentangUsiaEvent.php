<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentangUsiaEvent extends Model
{
    use HasFactory;
    protected $table = 'rentang_usia_event';
    protected $fillable = ['rentang_usia_id', 'event_id'];

    public function rentangUsia()
    {
        return $this->belongsTo(RentangUsia::class, 'rentang_usia_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }
}
