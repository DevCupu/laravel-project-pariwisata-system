<?php

namespace Database\Seeders;

use App\Models\CampingPackage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CampingPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Paket Camp Keluarga',
                'tagline' => '2D1N dengan api unggun & BBQ',
                'price' => 650000,
                'nights' => 1,
                'capacity' => 4,
                'description' => 'Termasuk tenda dome premium, sleeping bag, breakfast lokal, dan sesi api unggun privat.',
                'includes' => [
                    'Tenda keluarga 4 orang',
                    'Lighting dekorasi & bean bag',
                    'Guide lokal & sesi story telling',
                    'BBQ set + bahan bakar',
                ],
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Camp Explorer',
                'tagline' => '3D2N untuk penjelajah aktif',
                'price' => 1200000,
                'nights' => 2,
                'capacity' => 6,
                'description' => 'Khusus komunitas atau tim kecil dengan itinerary trekking dan workshop kopi.',
                'includes' => [
                    '2 malam area premium',
                    'Guide trekking & sunrise hike',
                    'Workshop kopi + alat seduh',
                    'Voucher makanan Rp200k',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Paket Intimate Glamping',
                'tagline' => 'Pengalaman romantis di tengah pinus',
                'price' => 980000,
                'nights' => 1,
                'capacity' => 2,
                'description' => 'Tenda glamping dengan kasur queen, dekorasi bunga, dan candle light dinner.',
                'includes' => [
                    'Kasur queen + linen hotel grade',
                    'Dekorasi lampu gantung',
                    'Dinner set 3 course',
                    'Fotografer 30 menit',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($packages as $index => $package) {
            CampingPackage::updateOrCreate(
                ['slug' => Str::slug($package['name'])],
                array_merge($package, ['sort_order' => $index])
            );
        }
    }
}
