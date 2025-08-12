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
            'slug' => 'kejuaraan-pencak-silat',
            'penyelenggara' => 'Baim Gendang',
            'image' => 'poster-kejurcab-pagarnusa.jpg',
            'desc' => 'Pencak silat merupakan seni beladiri tradisional yang memiliki berbagai ketentuan, keselarasan, keseimbangan, keserasian antara wirama, wirasa, dan wiraga. Juga menanamkan sikap berbudi pekerti yang luhur serta pengamalan falsafah Silat. Pagar Nusa ialah sebuah organisasi yang mempunyai tujuan untuk membentuk suatu wadah dibawah naungan Nahdlatul Ulama yang khusus mengembangkan seni bela diri pencak silat. Di sisi lain tumbuh berbagai perguruan pencak silat dengan segala keanekaragamannya berdasarkan segi agama, aqidah, kepercayaan dan jurus – jurus lainnya. Dalam organisasi ini diharuskan mengajarkan materi jurus baku dari PSNU Pagar Nusa sebagai bukti bahwa perguruan tradisional tersebut tergabung dalam PSNU Pagar Nusa.',
            'kategori' => '<p class="text-dark mb-2">Ketentuan Kategori Tanding: <a href="https: //drive.google.com/drive/folders/1q-vAkN3uUt6wMcYnMBY5y3kCS28_yezF">Link Drive Ketentuan</a>',
            'berkas' => '<ul>
                            <li>Fotokopi Kartu Keluarga</li>
                            <li>Kartu Tanda Anggota Pagar Nusa</li>
                            <li>Biodata Atlet</li>
                            <li>Formulir Kontingen</li>
                        </ul>',
            'kegiatan' => '<p class="text-dark m-0">Pendaftaran</p>
                            <p class="text-muted m-0"><i class="bi bi-geo-alt text-danger pe-2"></i>Online</p>
                            <p class="text-muted "><i class="bi bi-calendar3 text-danger pe-2"></i>7 Agustus 2025 - 23 September 2025</p>
                            <p class="text-dark m-0">Technical Meeting (Gedung PCNU Sidoarjo)</p>
                            <p class="text-muted m-0"><i class="bi bi-geo-alt text-danger pe-2"></i>Jl. Erlangga, Kapasan, Sidokare, Kec. Sidoarjo,
Kabupaten Sidoarjo, Jawa Timur 61214.</p>
                            <p class="text-muted "><i class="bi bi-calendar3 text-danger pe-2"></i>23 September 2025</p>
                            <p class="text-dark m-0">Pelaksaan (Gedung Olahraga Sidoarjo)</p>
                            <p class="text-muted mb-0"><i class="bi bi-geo-alt text-danger pe-2"></i>Jl. Pahlawan, Wismasarinadi, Magersari, Kec. Sidoarjo,
Kabupaten Sidoarjo, Jawa Timur 61213.
</p>
                            <p class="text-muted "><i class="bi bi-calendar3 text-danger pe-2"></i>3 - 5 Oktober 2025</p>',
            'type' => 'kerjasama',
            'harga_contingent' => 0,
            'harga_peserta' => 200000
        ]);

        Event::create([
            'name' => 'Kejuaraan Pencak Silat Polri',
            'slug' => 'kejuaraan-pencak-silat-polri',
            'penyelenggara' => 'Faiz Kentrung',
            'image' => 'event-1.jpg',
            'desc' => 'Pencak silat merupakan seni beladiri tradisional yang memiliki berbagai ketentuan, keselarasan, keseimbangan, keserasian antara wirama, wirasa, dan wiraga. Juga menanamkan sikap berbudi pekerti yang luhur serta pengamalan falsafah Silat. Pagar Nusa ialah sebuah organisasi yang mempunyai tujuan untuk membentuk suatu wadah dibawah naungan Nahdlatul Ulama yang khusus mengembangkan seni bela diri pencak silat. Di sisi lain tumbuh berbagai perguruan pencak silat dengan segala keanekaragamannya berdasarkan segi agama, aqidah, kepercayaan dan jurus – jurus lainnya. Dalam organisasi ini diharuskan mengajarkan materi jurus baku dari PSNU Pagar Nusa sebagai bukti bahwa perguruan tradisional tersebut tergabung dalam PSNU Pagar Nusa.',
            'kategori' => '<p class="text-dark mb-2">Ketentuan Kategori Tanding: <a href="https: //drive.google.com/drive/folders/1q-vAkN3uUt6wMcYnMBY5y3kCS28_yezF">Link Drive Ketentuan</a>',
            'berkas' => '<ul>
                            <li>Fotokopi Kartu Keluarga</li>
                            <li>Kartu Tanda Anggota Pagar Nusa</li>
                            <li>Biodata Atlet</li>
                            <li>Formulir Kontingen</li>
                        </ul>',
            'kegiatan' => '<p class="text-dark m-0">Pendaftaran</p>
                            <p class="text-muted m-0"><i class="bi bi-geo-alt text-danger pe-2"></i>Online</p>
                            <p class="text-muted "><i class="bi bi-calendar3 text-danger pe-2"></i>7 Agustus 2025 - 23 September 2025</p>
                            <p class="text-dark m-0">Technical Meeting (Gedung PCNU Sidoarjo)</p>
                            <p class="text-muted m-0"><i class="bi bi-geo-alt text-danger pe-2"></i>Jl. Erlangga, Kapasan, Sidokare, Kec. Sidoarjo,
Kabupaten Sidoarjo, Jawa Timur 61214.</p>
                            <p class="text-muted "><i class="bi bi-calendar3 text-danger pe-2"></i>23 September 2025</p>
                            <p class="text-dark m-0">Pelaksaan (Gedung Olahraga Sidoarjo)</p>
                            <p class="text-muted mb-0"><i class="bi bi-geo-alt text-danger pe-2"></i>Jl. Pahlawan, Wismasarinadi, Magersari, Kec. Sidoarjo,
Kabupaten Sidoarjo, Jawa Timur 61213.
</p>
                            <p class="text-muted "><i class="bi bi-calendar3 text-danger pe-2"></i>3 - 5 Oktober 2025</p>',
            'type' => 'official',
            'harga_contingent' => 250000,
            'harga_peserta' => 200000
        ]);
    }
}
