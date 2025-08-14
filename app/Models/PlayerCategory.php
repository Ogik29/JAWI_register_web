<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerCategory extends Model
{
    use HasFactory;

    protected $table = 'player_categories';

    protected $fillable = [
        'filter',
        'category',
        'range',
        'type',
        'class_category_id'
    ];

    public function classCategory()
    {
        return $this->belongsTo(ClassCategory::class, 'class_category_id', 'id');
    }
}
