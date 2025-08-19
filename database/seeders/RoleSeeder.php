<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['name' => 'super admin'], // id role 1
            ['name' => 'admin'], // id role 2
            ['name' => 'manager'], // id role 3
        ]);
    }
}
