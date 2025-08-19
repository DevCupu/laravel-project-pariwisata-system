<?php

namespace Database\Seeders;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Wisata',
            'email' => 'admin@wisata.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
