<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Andini Pratiwi',
                'role' => 'Traveler',
                'location' => 'Makassar',
                'quote' => 'Kabut pagi di Dante Pine benar-benar magis. Trekking sunrise-nya terorganisir dan guide-nya ramah. Pasti kembali lagi bersama keluarga.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Rizky Hidayat',
                'role' => 'Camping Enthusiast',
                'location' => 'Parepare',
                'quote' => 'Paket glamping-nya rapi, tenda bersih, dan api unggunnya hangat. Fasilitas kamar mandi juga di atas ekspektasi untuk lokasi pegunungan.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Sari & Keluarga',
                'role' => 'Keluarga',
                'location' => 'Enrekang',
                'quote' => 'Anak-anak senang sekali dengan zip bike dan spot foto jembatan langit. Tempat aman, bersih, dan staf sigap membantu.',
                'rating' => 4,
                'is_featured' => false,
            ],
        ];

        foreach ($testimonials as $index => $testimonial) {
            Testimonial::updateOrCreate(
                ['name' => $testimonial['name']],
                $testimonial + ['sort_order' => $index, 'is_active' => true]
            );
        }
    }
}
