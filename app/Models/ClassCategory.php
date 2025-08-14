<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassCategory extends Model
{
    use HasFactory;

    protected $table = 'class_categories';

    protected $fillable = [
        'name',
        'gender',
        'event_id',
        'jenis_pertandingan'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public function playerCategories()
    {
        return $this->hasMany(PlayerCategory::class, 'class_category_id', 'id');
    }
}
