<?php

use App\Models\User;

it('redirects guests to the login page', function () {
    $this->get('/admin/dashboard')->assertRedirect('/login');
});

it('forbids non-admin users from accessing the admin panel', function () {
    $user = User::factory()->create();
    $user->role = 'user';
    $user->save();

    $this->actingAs($user)->get('/admin/dashboard')->assertForbidden();
    $this->actingAs($user)->get('/admin/activities')->assertForbidden();
});

it('allows admins to access the dashboard and content pages', function () {
    $admin = User::factory()->create();
    $admin->role = 'admin';
    $admin->save();

    $this->actingAs($admin)->get('/admin/dashboard')->assertOk();
    $this->actingAs($admin)->get('/admin/activities')->assertOk();
});
