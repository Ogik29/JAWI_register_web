<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassCategory;

class ClassCategorySeeder extends Seeder
{
    public function run(): void
    {
        ClassCategory::create([
            'name' => 'U-18',
            'gender' => 'Male',
            'event_id' => 1
        ]);
    }
}
