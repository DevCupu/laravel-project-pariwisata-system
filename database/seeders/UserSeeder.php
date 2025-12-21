<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin - use updateOrCreate so seeder is idempotent
        User::updateOrCreate(
            ['email' => 'admin@wisata.test'],
            [
                'name' => 'Admin Wisata',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }
}
