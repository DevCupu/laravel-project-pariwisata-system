<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            FacilitySeeder::class,
            AboutSectionSeeder::class,
            ActivitySeeder::class,
            MenuItemSeeder::class,
            PhotoSpotSeeder::class,
            EventSeeder::class,
            GeneralFacilityInfoSeeder::class,
            CampingPackageSeeder::class,
            TestimonialSeeder::class,
        ]);
    }
}
