<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventRole;

class EventRoleSeeder extends Seeder
{
    public function run(): void
    {
        EventRole::create([
            'user_id' => 4,
            'event_id' => 1,
            'type' => "CP"
        ]);

        EventRole::create([
            'user_id' => 5,
            'event_id' => 1,
            'type' => "CP"
        ]);

        EventRole::create([
            'user_id' => 6,
            'event_id' => 2,
            'type' => "CP"
        ]);

        EventRole::create([
            'user_id' => 7,
            'event_id' => 2,
            'type' => "CP"
        ]);
    }
}
