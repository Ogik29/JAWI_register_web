<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contingent;

class ContingentSeeder extends Seeder
{
    public function run(): void
    {
        Contingent::create([
            'name' => 'Tim SMA Negeri 1',
            'manajer_name' => 'Manager A',
            'email' => 'manager@sma1.sch.id',
            'no_telp' => '08123456789',
            'user_id' => 2
        ]);
    }
}
