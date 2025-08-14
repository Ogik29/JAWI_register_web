<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlayerCategory;

class PlayerCategorySeeder extends Seeder
{
    public function run(): void
    {
        PlayerCategory::create([
            'filter' => 'Umur',
            'category' => 'Junior',
            'range' => '15-18',
            'type' => 'Tanding',
            'class_category_id' => 1
        ]);
    }
}
