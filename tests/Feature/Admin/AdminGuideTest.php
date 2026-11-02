<?php

use App\Models\User;

it('allows admins to view the usage guide and its screenshots', function () {
    $admin = User::factory()->create();
    $admin->role = 'admin';
    $admin->save();

    $response = $this->actingAs($admin)->get(route('admin.guide'));

    $response
        ->assertOk()
        ->assertSee('Kelola website dengan lebih mudah')
        ->assertSee('Fasilitas Booking')
        ->assertSee('Pesan dari formulir Kontak')
        ->assertSee(route('admin.guide'), false)
        ->assertSee('<a href="'.route('admin.guide').'" class="active">', false)
        ->assertSee('id="mulai"', false)
        ->assertSee('id="akses"', false)
        ->assertSee('id="dashboard"', false)
        ->assertSee('id="konten"', false)
        ->assertSee('id="booking"', false)
        ->assertSee('id="fasilitas"', false)
        ->assertSee('id="kafe"', false)
        ->assertSee('id="akun"', false)
        ->assertSee('id="kendala"', false);

    foreach ([
        'dashboard.png',
        'content-list.png',
        'activity-form.png',
        'bookings.png',
        'booking-facilities.png',
        'general-facilities.png',
        'menu-cafe.png',
        'profile.png',
    ] as $screenshot) {
        expect(file_exists(public_path('images/admin-guide/'.$screenshot)))->toBeTrue();
        $response->assertSee('images/admin-guide/'.$screenshot);
    }
});

it('keeps the usage guide behind the admin middleware', function () {
    $this->get(route('admin.guide'))->assertRedirect('/login');

    $user = User::factory()->create();
    $user->role = 'user';
    $user->save();

    $this->actingAs($user)->get(route('admin.guide'))->assertForbidden();
});
