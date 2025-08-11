<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::create([
            'name' => 'Turnamen Sepak Bola',
            'image' => 'event1.jpg',
            'desc' => 'Kejuaraan sepak bola tahunan.',
            'kategori' => 'Olahraga',
            'berkas' => null,
            'kegiatan' => 'Pertandingan',
            'type' => 'Team'
        ]);
    }
}
