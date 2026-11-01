<?php

use App\Models\User;
use Database\Seeders\UserSeeder;

it('seeds the admin account with the admin role', function () {
    $this->seed(UserSeeder::class);

    $admin = User::where('email', 'admin@wisata.test')->first();

    expect($admin)->not->toBeNull()
        ->and($admin->role)->toBe('admin');
});

it('keeps the seeder idempotent without downgrading the role', function () {
    $this->seed(UserSeeder::class);
    $this->seed(UserSeeder::class);

    expect(User::where('email', 'admin@wisata.test')->count())->toBe(1)
        ->and(User::where('email', 'admin@wisata.test')->first()->role)->toBe('admin');
});
