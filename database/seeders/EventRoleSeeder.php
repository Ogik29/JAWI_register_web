<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventRole;

class EventRoleSeeder extends Seeder
{
    public function run(): void
    {
        EventRole::create([
            'user_id' => 6,
            'event_id' => 1
        ]);

        EventRole::create([
            'user_id' => 7,
            'event_id' => 1
        ]);

        EventRole::create([
            'user_id' => 8,
            'event_id' => 2
        ]);
    }
}
