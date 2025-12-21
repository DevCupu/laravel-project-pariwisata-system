<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            [
                'title' => 'Zip Bike Adventure',
                'category' => 'adventure',
                'short_description' => 'Sepeda gantung melintasi lembah pinus.',
                'duration_minutes' => 15,
                'price_from' => 75000,
                'min_age' => 10,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Sunrise Trekking',
                'category' => 'nature',
                'short_description' => 'Pendakian ringan menyambut matahari terbit.',
                'duration_minutes' => 120,
                'price_from' => 50000,
                'min_age' => 8,
                'is_active' => true,
            ],
            [
                'title' => 'Workshop Kopi Nusantara',
                'category' => 'experience',
                'short_description' => 'Belajar roasting dan brewing bersama barista.',
                'duration_minutes' => 60,
                'price_from' => 65000,
                'min_age' => 12,
                'is_active' => true,
            ],
        ];

        foreach ($activities as $index => $activity) {
            Activity::updateOrCreate(
                ['slug' => Str::slug($activity['title'])],
                array_merge([
                    'description' => $activity['short_description'],
                    'sort_order' => $index,
                ], $activity)
            );
        }
    }
}
