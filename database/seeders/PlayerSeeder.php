<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Player;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        Player::create([
            'name' => 'Budi Santoso',
            'contingent_id' => 1,
            'nik' => '1234567890123456',
            'gender' => 'Male',
            'no_telp' => '08123456789',
            'email' => 'budi@example.com',
            'jenis_pertandingan' => 'Sepak Bola',
            'player_category_id' => 1,
            'foto_ktp' => 'ktp_budi.jpg',
            'foto_diri' => 'budi.jpg',
            'status' => 'Aktif'
        ]);
    }
}
