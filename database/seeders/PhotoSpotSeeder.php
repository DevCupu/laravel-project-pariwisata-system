<?php

namespace Database\Seeders;

use App\Models\PhotoSpot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PhotoSpotSeeder extends Seeder
{
    public function run(): void
    {
        $spots = [
            [
                'title' => 'Deck Panorama Kabut Pagi',
                'location_text' => 'Zona Utara - Bukit Pinus',
                'best_time' => '05.30 - 07.00 WITA',
                'description' => 'Dek kayu dengan foreground hutan pinus dan layer pegunungan, favorit untuk golden hour.',
                'tips' => 'Gunakan lensa sudut lebar 16-35mm dan tripod untuk long exposure kabut.',
                'image_path' => 'photo-spots/deck-panorama.jpg',
                'is_featured' => true,
            ],
            [
                'title' => 'Jembatan Langit Dante',
                'location_text' => 'Gerbang Selatan',
                'best_time' => '08.00 - 10.00 WITA',
                'description' => 'Jembatan gantung dengan view lembah hijau, cocok untuk foto couple.',
                'tips' => 'Bawa filter ND untuk mengurangi cahaya berlebih.',
                'image_path' => 'photo-spots/jembatan-langit.jpg',
            ],
            [
                'title' => 'Batu Raksasa Aurora',
                'location_text' => 'Area Camping B',
                'best_time' => '18.30 - 20.00 WITA',
                'description' => 'Spot foto malam dengan instalasi lampu aurora dan milkyway hunting saat langit cerah.',
                'tips' => 'Disarankan membawa tripod dan penutup lensa anti embun.',
                'image_path' => 'photo-spots/batu-aurora.jpg',
            ],
        ];

        foreach ($spots as $index => $spot) {
            PhotoSpot::updateOrCreate(
                ['slug' => Str::slug($spot['title'])],
                array_merge($spot, ['sort_order' => $index])
            );
        }
    }
}
