<?php

namespace Database\Seeders;

use App\Models\Facility as FacilityModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['name' => 'Flying Fox', 'category' => 'wahana', 'capacity' => 10, 'description' => 'Wahana adrenalin dengan jalur sepanjang 150m.'],
            ['name' => 'ATV Adventure', 'category' => 'wahana', 'capacity' => 6, 'description' => 'Paket keliling hutan pinus dengan ATV guide.'],
            ['name' => 'Meja Indoor 1', 'category' => 'cafe', 'capacity' => 3, 'description' => 'Meja kafe indoor untuk 4 orang.'],
            ['name' => 'Meja Indoor 2', 'category' => 'cafe', 'capacity' => 3, 'description' => 'Meja kafe indoor dekat panggung akustik.'],
            ['name' => 'Meja Outdoor 1', 'category' => 'cafe', 'capacity' => 2, 'description' => 'Meja outdoor dengan view bukit.'],
            ['name' => 'Camping Ground A', 'category' => 'camping', 'capacity' => 5, 'description' => 'Area camping dekat sungai, kapasitas tenda besar.'],
            ['name' => 'Camping Ground B', 'category' => 'camping', 'capacity' => 4, 'description' => 'Area camping dengan akses cepat ke fasilitas umum.'],
        ];

        foreach ($facilities as $index => $facility) {
            FacilityModel::updateOrCreate(
                ['slug' => Str::slug($facility['name'])],
                [
                    'name' => $facility['name'],
                    'category' => $facility['category'],
                    'capacity_per_day' => $facility['capacity'],
                    'description' => $facility['description'],
                    'sort_order' => $index,
                    'is_active' => true,
                ]
            );
        }
    }
}
