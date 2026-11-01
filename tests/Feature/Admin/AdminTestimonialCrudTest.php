<?php

use App\Models\Testimonial;
use App\Models\User;

beforeEach(function () {
    $admin = User::factory()->create();
    $admin->role = 'admin';
    $admin->save();

    $this->actingAs($admin);
});

it('lists testimonials', function () {
    Testimonial::create(['name' => 'Andini Pratiwi', 'quote' => 'Pengalaman luar biasa.']);

    $this->get('/admin/testimonials')
        ->assertOk()
        ->assertSee('Andini Pratiwi');
});

it('creates a testimonial', function () {
    $this->post('/admin/testimonials', [
        'name' => 'Rizky Hidayat',
        'role' => 'Camping Enthusiast',
        'location' => 'Parepare',
        'quote' => 'Tenda bersih dan api unggunnya hangat.',
        'rating' => 5,
        'is_active' => '1',
    ])
        ->assertRedirect(route('admin.testimonials.index'))
        ->assertSessionHas('admin_success');

    $this->assertDatabaseHas('testimonials', [
        'name' => 'Rizky Hidayat',
        'location' => 'Parepare',
        'rating' => 5,
    ]);
});

it('validates required fields when creating a testimonial', function () {
    $this->from(route('admin.testimonials.create'))
        ->post('/admin/testimonials', ['name' => '', 'quote' => ''])
        ->assertRedirect(route('admin.testimonials.create'))
        ->assertSessionHasErrors(['name', 'quote']);
});

it('updates a testimonial', function () {
    $testimonial = Testimonial::create([
        'name' => 'Nama Lama',
        'quote' => 'Kutipan lama',
        'rating' => 4,
    ]);

    $this->put("/admin/testimonials/{$testimonial->id}", [
        'name' => 'Nama Baru',
        'quote' => 'Kutipan baru',
        'rating' => 5,
        'is_active' => '1',
    ])
        ->assertRedirect(route('admin.testimonials.edit', $testimonial))
        ->assertSessionHas('admin_success');

    $this->assertDatabaseHas('testimonials', [
        'id' => $testimonial->id,
        'name' => 'Nama Baru',
        'rating' => 5,
    ]);
});

it('toggles the active status of a testimonial', function () {
    $testimonial = Testimonial::create([
        'name' => 'Aktif Dulu',
        'quote' => 'Segera dinonaktifkan',
        'is_active' => true,
    ]);

    $this->patch("/admin/testimonials/{$testimonial->id}/toggle-status")->assertRedirect();

    expect($testimonial->fresh()->is_active)->toBeFalse();
});

it('toggles the featured flag of a testimonial', function () {
    $testimonial = Testimonial::create([
        'name' => 'Biasa Dulu',
        'quote' => 'Segera dihighlight',
        'is_featured' => false,
    ]);

    $this->patch("/admin/testimonials/{$testimonial->id}/toggle-featured")->assertRedirect();

    expect($testimonial->fresh()->is_featured)->toBeTrue();
});

it('deletes a testimonial', function () {
    $testimonial = Testimonial::create(['name' => 'Hapus Saya', 'quote' => 'Sampai jumpa']);

    $this->delete("/admin/testimonials/{$testimonial->id}")
        ->assertRedirect(route('admin.testimonials.index'))
        ->assertSessionHas('admin_success');

    $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
});
