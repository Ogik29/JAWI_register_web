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
            'gender' => 'laki-laki',
            'no_telp' => '08123456789',
            'email' => 'budi@example.com',
            'player_category_id' => 1,
            'foto_ktp' => 'ktp_budi.jpg',
            'foto_diri' => 'budi.jpg',
            // 'status' => 'Aktif',
            'tgl_lahir' => Carbon::create(2004, 11, 15),
            'kelas_pertandingan_id' => 1
        ]);

        Player::create([
            'name' => 'Messi',
            'contingent_id' => 1,
            'nik' => '123456789012933829',
            'gender' => 'laki-laki',
            'no_telp' => '08123456789',
            'email' => 'messi@example.com',
            'player_category_id' => 1,
            'foto_ktp' => 'ktp_budi.jpg',
            'foto_diri' => 'budi.jpg',
            'status' => 1,
            'tgl_lahir' => Carbon::create(1987, 11, 15),
            'kelas_pertandingan_id' => 1
        ]);
    }
}
