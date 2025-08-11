<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::create([
            'name' => 'Kejuaraan Pencak Silat',
            'image' => 'poster-kejurcab-pagarnusa.jpg',
            'desc' => 'Pencak silat merupakan seni beladiri tradisional yang memiliki berbagai ketentuan, keselarasan, keseimbangan, keserasian antara wirama, wirasa, dan wiraga. Juga menanamkan sikap berbudi pekerti yang luhur serta pengamalan falsafah Silat. Pagar Nusa ialah sebuah organisasi yang mempunyai tujuan untuk membentuk suatu wadah dibawah naungan Nahdlatul Ulama yang khusus mengembangkan seni bela diri pencak silat. Di sisi lain tumbuh berbagai perguruan pencak silat dengan segala keanekaragamannya berdasarkan segi agama, aqidah, kepercayaan dan jurus – jurus lainnya. Dalam organisasi ini diharuskan mengajarkan materi jurus baku dari PSNU Pagar Nusa sebagai bukti bahwa perguruan tradisional tersebut tergabung dalam PSNU Pagar Nusa.',
            'kategori' => 'Link Drive Ketentuan Tanding: https://drive.google.com/drive/folders/1q-vAkN3uUt6wMcYnMBY5y3kCS28_yezF',
            'berkas' => '- Fotokopi Kartu Keluarga <br>
                        - Kartu Tanda Anggota Pagar Nusa <br>
                        - Biodata Atlet <br>
                        - Formulir Kontingen <br>
                    ',
            'kegiatan' => 'Pertandingan',
            'type' => 'kerjasama'
        ]);
    }
}
