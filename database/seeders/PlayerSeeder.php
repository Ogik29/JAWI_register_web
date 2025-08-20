<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Player;
use App\Models\RentangUsia;
use App\Models\RentangUsiaEvent;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {

        $rentangUsia = RentangUsia::create([
            'rentang_usia' => '18-25',
        ]);

        RentangUsiaEvent::create([
            'rentang_usia_id' => $rentangUsia->id,
            'event_id' => 1,
        ]);

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
            'kelas_pertandingan_id' => 1,
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
            'kelas_pertandingan_id' => 1,
        ]);
    }
}
