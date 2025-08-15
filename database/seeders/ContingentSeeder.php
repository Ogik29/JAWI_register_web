<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contingent;

class ContingentSeeder extends Seeder
{
    public function run(): void
    {
        Contingent::create([
            'name' => 'Perguruan Pencak Silat Nusantara',
            'manajer_name' => 'Manager1',
            'email' => 'manager1@example.com',
            'no_telp' => '081234567891',
            'user_id' => 7,
            'event_id' => 1
        ]);

        Contingent::create([
            'name' => 'Merah Putih One for All',
            'manajer_name' => 'Manager1',
            'email' => 'manager1@example.com',
            'no_telp' => '081234567891',
            'user_id' => 7,
            'event_id' => 2
        ]);

        Contingent::create([
            'name' => 'Horeg Moker',
            'manajer_name' => 'Manager2',
            'email' => 'manager2@example.com',
            'no_telp' => '081234567892',
            'user_id' => 8,
            'event_id' => 1
        ]);
    }
}
