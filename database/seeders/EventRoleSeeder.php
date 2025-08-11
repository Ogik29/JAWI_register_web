<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventRole;

class EventRoleSeeder extends Seeder
{
    public function run(): void
    {
        EventRole::create([
            'user_id' => 2,
            'event_id' => 1
        ]);
    }
}
