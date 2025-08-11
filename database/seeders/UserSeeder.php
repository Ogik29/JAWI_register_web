<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'name' => 'Admin System'
            ],
            [
                'username' => 'manager1',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'name' => 'Manager A'
            ],
            [
                'username' => 'player1',
                'password' => Hash::make('password'),
                'role_id' => 3,
                'name' => 'Player One'
            ],
        ]);
    }
}
