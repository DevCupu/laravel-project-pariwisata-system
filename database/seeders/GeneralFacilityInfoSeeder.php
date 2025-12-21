<?php

namespace Database\Seeders;

use App\Models\GeneralFacilityInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GeneralFacilityInfoSeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'Mushola & Tempat Wudhu',
                'icon' => 'fa-solid fa-mosque',
                'description' => 'Mushola berkapasitas 40 orang dengan perlengkapan mukena & sarung cadangan.',
                'availability_note' => 'Buka 24 jam dengan petugas piket.',
            ],
            [
                'name' => 'Kamar Mandi Bersih',
                'icon' => 'fa-solid fa-shower',
                'description' => '12 bilik shower air panas & 8 bilik toilet kering yang dibersihkan setiap 2 jam.',
                'availability_note' => 'Token air panas bisa dibeli di loket utama.',
            ],
            [
                'name' => 'Charging Station & Locker',
                'icon' => 'fa-solid fa-bolt',
                'description' => 'Loker pribadi dengan slot pengisian listrik & USB, aman untuk perangkat Anda.',
                'availability_note' => 'Gratis untuk tamu camping, Rp10k/jam untuk umum.',
            ],
            [
                'name' => 'Pos Medis & Tim Rescue',
                'icon' => 'fa-solid fa-kit-medical',
                'description' => 'Paramedis tersertifikasi dengan peralatan emergency, siap 24 jam.',
                'availability_note' => 'Hubungi nomor darurat di gelang akses Anda.',
            ],
        ];

        foreach ($facilities as $index => $facility) {
            GeneralFacilityInfo::updateOrCreate(
                ['slug' => Str::slug($facility['name'])],
                array_merge($facility, ['sort_order' => $index])
            );
        }
    }
}
