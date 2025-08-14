<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Player;
use Illuminate\Database\Seeder;

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
            'player_category_id' => 1,
            'foto_ktp' => 'ktp_budi.jpg',
            'foto_diri' => 'budi.jpg',
            // 'status' => 'Aktif',
            'tgl_lahir' => Carbon::create(2004, 11, 15)
        ]);
    }
}
