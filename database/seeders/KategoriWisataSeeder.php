<?php

namespace Database\Seeders;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriWisata;
use Illuminate\Support\Facades\Hash;

class KategoriWisataSeeder extends Seeder
{

    public function run(): void
    {
        $data = ['Pantai', 'Gunung', 'Budaya', 'Kuliner', 'Sejarah'];
        foreach ($data as $nama) {
            KategoriWisata::create(['nama' => $nama]);
        }
    }
}
