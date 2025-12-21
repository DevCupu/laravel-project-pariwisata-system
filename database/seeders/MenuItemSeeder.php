<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $menu = [
            ['name' => 'V60 Pine Blend', 'section' => 'signature-coffee', 'type' => 'drink', 'price' => 38000, 'badge' => 'Signature'],
            ['name' => 'Es Kopi Aren Dante', 'section' => 'signature-coffee', 'type' => 'drink', 'price' => 32000],
            ['name' => 'Pine Cone Latte', 'section' => 'specialty', 'type' => 'drink', 'price' => 42000, 'badge' => 'Limited'],
            ['name' => 'Roti Panggang Matcha', 'section' => 'light-bites', 'type' => 'food', 'price' => 28000],
            ['name' => 'Indomie Smokey Camp', 'section' => 'comfort', 'type' => 'food', 'price' => 25000],
            ['name' => 'Paket Sosis Bakar', 'section' => 'comfort', 'type' => 'food', 'price' => 30000],
        ];

        foreach ($menu as $index => $item) {
            MenuItem::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                array_merge([
                    'short_description' => $item['name'],
                    'description' => $item['name'],
                    'sort_order' => $index,
                ], $item)
            );
        }
    }
}
