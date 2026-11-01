<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin - use updateOrCreate so seeder is idempotent.
        // 'role' is intentionally not mass assignable, so set it directly after upsert.
        $admin = User::updateOrCreate(
            ['email' => 'admin@wisata.test'],
            [
                'name' => 'Admin Wisata',
                'password' => Hash::make('password'),
            ]
        );

        if ($admin->role !== 'admin') {
            $admin->role = 'admin';
            $admin->save();
        }
    }
}
