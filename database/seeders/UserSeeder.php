<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            // Data Admin
            [
                'nama_lengkap' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password123'), // Ganti dengan password yang aman
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-15',
                'negara' => 'Indonesia',
                'no_telp' => '081234567890',
                'status' => 1,
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lengkap' => 'Ogik',
                'email' => 'ogik@example.com',
                'password' => Hash::make('password123'), // Ganti dengan password yang aman
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-15',
                'negara' => 'Indonesia',
                'no_telp' => '081234567890',
                'status' => 1,
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lengkap' => 'Leri',
                'email' => 'leri@example.com',
                'password' => Hash::make('password123'), // Ganti dengan password yang aman
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-15',
                'negara' => 'Indonesia',
                'no_telp' => '081234567890',
                'status' => 1,
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lengkap' => 'Baim',
                'email' => 'baim@example.com',
                'password' => Hash::make('password123'), // Ganti dengan password yang aman
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-15',
                'negara' => 'Indonesia',
                'no_telp' => '081234567890',
                'status' => 1,
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lengkap' => 'Faiz',
                'email' => 'faiz@example.com',
                'password' => Hash::make('password123'), // Ganti dengan password yang aman
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-15',
                'negara' => 'Indonesia',
                'no_telp' => '081234567890',
                'status' => 1,
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_lengkap' => 'Faiz',
                'email' => 'faiz@example.com',
                'password' => Hash::make('password123'), // Ganti dengan password yang aman
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-15',
                'negara' => 'Indonesia',
                'no_telp' => '081234567890',
                'status' => 1,
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_lengkap' => 'atmint Jakarta',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'), // Ganti dengan password yang aman
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-15',
                'negara' => 'Indonesia',
                'no_telp' => '081234567890',
                'status' => 1,
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Data Manager 1
            [
                'nama_lengkap' => 'Manager1',
                'email' => 'manager1@example.com',
                'password' => Hash::make('password123'), // Ganti dengan password yang aman
                'alamat' => 'Jl. Sudirman No. 10, Bandung',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1992-05-20',
                'negara' => 'Indonesia',
                'no_telp' => '081234567891',
                'status' => 1,
                'role_id' => 3, // Role ID untuk Manager
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Data Manager 2
            [
                'nama_lengkap' => 'Manager2',
                'email' => 'manager2@example.com',
                'password' => Hash::make('password123'), // Ganti dengan password yang aman
                'alamat' => 'Jl. Gatot Subroto No. 5, Surabaya',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1991-11-30',
                'negara' => 'Indonesia',
                'no_telp' => '081234567892',
                'status' => 1,
                'role_id' => 3, // Role ID untuk Manager
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // [
            //     'nama_lengkap' => 'Mas Feris',
            //     'email' => 'feris@example.com',
            //     'password' => Hash::make('password123'), // Ganti dengan password yang aman
            //     'alamat' => 'Jl. Gatot Kaca No. 5, Surabaya',
            //     'jenis_kelamin' => 'Laki-laki',
            //     'tempat_lahir' => 'Surabaya',
            //     'tanggal_lahir' => '1998-11-30',
            //     'negara' => 'Indonesia',
            //     'no_telp' => '08976464461',
            //     'status' => 1,
            //     'role_id' => 2,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],

            // [
            //     'nama_lengkap' => 'Mas Hoiri',
            //     'email' => 'hoiri@example.com',
            //     'password' => Hash::make('password123'), // Ganti dengan password yang aman
            //     'alamat' => 'Jl. Gatot Subroto No. 10, Surabaya',
            //     'jenis_kelamin' => 'Laki-laki',
            //     'tempat_lahir' => 'Surabaya',
            //     'tanggal_lahir' => '1991-11-20',
            //     'negara' => 'Indonesia',
            //     'no_telp' => '087702426911',
            //     'status' => 1,
            //     'role_id' => 2,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'nama_lengkap' => 'Mas Daemon',
            //     'email' => 'daemon@example.com',
            //     'password' => Hash::make('password123'), // Ganti dengan password yang aman
            //     'alamat' => 'Jl. Gatot Subroto No. 5, Surabaya',
            //     'jenis_kelamin' => 'Laki-laki',
            //     'tempat_lahir' => 'Dragonstone',
            //     'tanggal_lahir' => '1991-11-30',
            //     'negara' => 'Indonesia',
            //     'no_telp' => '081234567892',
            //     'status' => 1,
            //     'role_id' => 3,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'nama_lengkap' => 'Mas-Masan',
            //     'email' => 'mase@example.com',
            //     'password' => Hash::make('password123'), // Ganti dengan password yang aman
            //     'alamat' => 'Jl. Gatot Subroto No. 5, Surabaya',
            //     'jenis_kelamin' => 'Laki-laki',
            //     'tempat_lahir' => 'Surabaya',
            //     'tanggal_lahir' => '1991-11-30',
            //     'negara' => 'Indonesia',
            //     'no_telp' => '081234567892',
            //     'status' => 1,
            //     'role_id' => 3,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
        ]);
    }
}
