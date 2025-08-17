<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            // EventSeeder::class,
            // ClassCategorySeeder::class,
            // PlayerCategorySeeder::class,
            // ContingentSeeder::class,
            // KategoriPertandinganSeeder::class,
            // JenisPertandinganSeeder::class,
            // KelasPertandinganSeeder::class,
            // PlayerSeeder::class,
            // TransactionSeeder::class,
            // TransactionDetailSeeder::class,
            // EventRoleSeeder::class,
        ]);
    }
}
