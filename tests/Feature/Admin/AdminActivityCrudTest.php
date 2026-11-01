<?php

use App\Models\Activity;
use App\Models\User;

beforeEach(function () {
    $admin = User::factory()->create();
    $admin->role = 'admin';
    $admin->save();

    $this->actingAs($admin);
});

it('lists activities', function () {
    Activity::create(['title' => 'Trekking Hutan Pinus', 'slug' => 'trekking-hutan-pinus', 'category' => 'Outdoor']);

    $this->get('/admin/activities')
        ->assertOk()
        ->assertSee('Trekking Hutan Pinus');
});

it('creates an activity with a generated slug', function () {
    $this->post('/admin/activities', [
        'title' => 'Trekking Pine',
        'category' => 'Outdoor',
        'short_description' => 'Jelajah hutan pinus',
        'is_active' => '1',
    ])
        ->assertRedirect(route('admin.activities.index'))
        ->assertSessionHas('admin_success');

    $this->assertDatabaseHas('activities', [
        'title' => 'Trekking Pine',
        'slug' => 'trekking-pine',
        'category' => 'Outdoor',
    ]);
});

it('validates required fields when creating an activity', function () {
    $this->from(route('admin.activities.create'))
        ->post('/admin/activities', ['title' => ''])
        ->assertRedirect(route('admin.activities.create'))
        ->assertSessionHasErrors('title');
});

it('updates an activity and regenerates the slug from the title', function () {
    $activity = Activity::create([
        'title' => 'Nama Lama',
        'slug' => 'nama-lama',
        'category' => 'Umum',
    ]);

    $this->put("/admin/activities/{$activity->id}", [
        'title' => 'Nama Baru',
        'category' => 'Outdoor',
    ])
        ->assertRedirect(route('admin.activities.edit', $activity))
        ->assertSessionHas('admin_success');

    $this->assertDatabaseHas('activities', [
        'id' => $activity->id,
        'title' => 'Nama Baru',
        'slug' => 'nama-baru',
    ]);
});

it('generates a unique slug when the base slug already exists', function () {
    Activity::create(['title' => 'Trekking Pine', 'slug' => 'trekking-pine', 'category' => 'Outdoor']);

    $this->post('/admin/activities', [
        'title' => 'Trekking Pine',
        'category' => 'Outdoor',
    ])->assertRedirect(route('admin.activities.index'));

    $this->assertDatabaseHas('activities', ['slug' => 'trekking-pine-1']);
});

it('toggles the active status of an activity', function () {
    $activity = Activity::create([
        'title' => 'Aktif Dulu',
        'slug' => 'aktif-dulu',
        'category' => 'Umum',
        'is_active' => true,
    ]);

    $this->patch("/admin/activities/{$activity->id}/toggle-status")->assertRedirect();

    expect($activity->fresh()->is_active)->toBeFalse();
});

it('deletes an activity', function () {
    $activity = Activity::create(['title' => 'Hapus Saya', 'slug' => 'hapus-saya', 'category' => 'Umum']);

    $this->delete("/admin/activities/{$activity->id}")
        ->assertRedirect(route('admin.activities.index'))
        ->assertSessionHas('admin_success');

    $this->assertDatabaseMissing('activities', ['id' => $activity->id]);
});
