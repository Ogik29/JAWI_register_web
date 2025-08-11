<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

     protected $table = 'events';

    protected $fillable = [
        'name', 'image', 'desc', 'kategori', 'berkas', 'kegiatan', 'type'
    ];

    public function classCategories()
    {
        return $this->hasMany(ClassCategory::class, 'event_id', 'id');
    }

    public function eventRoles()
    {
        return $this->hasMany(EventRole::class, 'event_id', 'id');
    }
}
