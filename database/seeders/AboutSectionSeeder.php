<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutSection;

class AboutSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'title' => 'Sejarah Kami',
                'slug' => 'tentang-kami',
                'content' => 'Dante Pine Enrekang dimulai dari komunitas pecinta alam lokal yang ingin membuka akses aman ke hutan pinus. Kini kami mengelola kawasan wisata alam terpadu dengan standar pelayanan resmi.',
                'display_order' => 1,
            ],
            [
                'title' => 'Lokasi & Jam Operasional',
                'slug' => 'lokasi',
                'content' => "Jalur Poros Enrekang - Tana Toraja, buka setiap hari pukul 08.00 - 22.00 WITA. Pengunjung disarankan reservasi terlebih dahulu untuk pengalaman terbaik.",
                'display_order' => 2,
            ],
        ];

        foreach ($sections as $section) {
            AboutSection::updateOrCreate(
                ['slug' => $section['slug']],
                $section + ['is_active' => true]
            );
        }
    }
}
