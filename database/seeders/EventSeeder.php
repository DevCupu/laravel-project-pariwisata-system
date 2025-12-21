<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // ensure storage dir exists
        Storage::disk('public')->makeDirectory('events');

        $samples = [
            [
                'type' => 'event',
                'title' => 'Festival Hutan Pinus 2025',
                'excerpt' => 'Perayaan alam dengan musik akustik dan pasar seni lokal.',
                'content' => '<p>Datang dan nikmati festival di tengah hutan pinus dengan berbagai kegiatan seni, kuliner, dan pertunjukan musik akustik.</p>',
                'start_date' => now()->addDays(14)->toDateString(),
                'end_date' => now()->addDays(15)->toDateString(),
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'type' => 'promo',
                'title' => 'Diskon Paket Camping Musim Hujan',
                'excerpt' => 'Potongan 25% untuk paket camping hingga akhir bulan.',
                'content' => '<p>Dapatkan promo menarik untuk paket camping lengkap dengan tenda dan api unggun.</p>',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addWeeks(2)->toDateString(),
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'type' => 'event',
                'title' => 'Workshop Fotografi Alam',
                'excerpt' => 'Belajar memotret lanskap dan nightscape bersama fotografer lokal.',
                'content' => '<p>Workshop intensif satu hari mempelajari dasar komposisi, exposure, dan teknik memotret di alam.</p>',
                'start_date' => now()->addMonths(1)->toDateString(),
                'end_date' => now()->addMonths(1)->addDay()->toDateString(),
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($samples as $s) {
            $slug = Str::slug($s['title']) . '-' . Str::random(6);
            Event::create(array_merge($s, ['slug' => $slug]));
        }
    }
}
