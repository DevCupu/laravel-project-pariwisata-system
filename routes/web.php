<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\AboutSectionController as AdminAboutSectionController;
use App\Http\Controllers\Admin\ActivityController as AdminActivityController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\CampingPackageController as AdminCampingPackageController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Admin\GeneralFacilityInfoController as AdminGeneralFacilityInfoController;
use App\Http\Controllers\Admin\MenuItemController as AdminMenuItemController;
use App\Http\Controllers\Admin\PhotoSpotController as AdminPhotoSpotController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CafeMenuController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/beranda', [HomeController::class, 'index'])->name('home');
Route::get('/aktivitas', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/menu-kafe', [CafeMenuController::class, 'index'])->name('menu.index');
Route::get('/booking', [BookingController::class, 'create'])->name('booking.form');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/status', [BookingController::class, 'status'])->name('booking.status');
Route::get('/booking/availability', [BookingController::class, 'availability'])->name('booking.availability');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
Route::get('/kontak', [App\Http\Controllers\ContactController::class, 'index'])->name('contact.form');
Route::post('/kontak', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

// Admin routes
Route::middleware(['auth', IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin can edit their own profile inside admin area
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create', [AdminBookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [AdminBookingController::class, 'store'])->name('bookings.store');
        Route::post('/bookings/bulk', [AdminBookingController::class, 'bulk'])->name('bookings.bulk');
        Route::get('/bookings/{booking}/edit', [AdminBookingController::class, 'edit'])->name('bookings.edit');
        Route::put('/bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');
        Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
        Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');

        Route::patch('/facilities/{facility}/toggle-status', [AdminFacilityController::class, 'toggleStatus'])->name('facilities.toggle');
        Route::resource('/facilities', AdminFacilityController::class)->except(['show']);
        Route::resource('/about-sections', AdminAboutSectionController::class)->except(['show']);
        Route::patch('/activities/{activity}/toggle-status', [AdminActivityController::class, 'toggleStatus'])->name('activities.toggle');
        Route::patch('/activities/{activity}/toggle-featured', [AdminActivityController::class, 'toggleFeatured'])->name('activities.featured');
        Route::resource('/activities', AdminActivityController::class)->except(['show']);
        Route::patch('/menu-items/{menuItem}/toggle-availability', [AdminMenuItemController::class, 'toggleAvailability'])->name('menu-items.toggle');
        Route::resource('/menu-items', AdminMenuItemController::class)->except(['show']);
        Route::patch('/photo-spots/{photoSpot}/toggle-status', [AdminPhotoSpotController::class, 'toggleStatus'])->name('photo-spots.toggle-status');
        Route::patch('/photo-spots/{photoSpot}/toggle-featured', [AdminPhotoSpotController::class, 'toggleFeatured'])->name('photo-spots.toggle-featured');
        Route::resource('/photo-spots', AdminPhotoSpotController::class)
            ->parameters(['photo-spots' => 'photoSpot'])
            ->except(['show']);
        Route::patch('/camping-packages/{campingPackage}/toggle-status', [AdminCampingPackageController::class, 'toggleStatus'])->name('camping-packages.toggle-status');
        Route::patch('/camping-packages/{campingPackage}/toggle-featured', [AdminCampingPackageController::class, 'toggleFeatured'])->name('camping-packages.toggle-featured');
        Route::resource('/camping-packages', AdminCampingPackageController::class)
            ->parameters(['camping-packages' => 'campingPackage'])
            ->except(['show']);
        Route::patch('/general-facilities/{generalFacility}/toggle-status', [AdminGeneralFacilityInfoController::class, 'toggleStatus'])->name('general-facilities.toggle-status');
        Route::resource('/general-facilities', AdminGeneralFacilityInfoController::class)
            ->parameters(['general-facilities' => 'generalFacility'])
            ->except(['show']);
        Route::patch('/events/{event}/toggle-status', [AdminEventController::class, 'toggleStatus'])->name('events.toggle-status');
        Route::patch('/events/{event}/toggle-featured', [AdminEventController::class, 'toggleFeatured'])->name('events.toggle-featured');
        Route::resource('/events', AdminEventController::class)
            ->parameters(['events' => 'event'])
            ->except(['show']);
        Route::patch('/testimonials/{testimonial}/toggle-status', [AdminTestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');
        Route::patch('/testimonials/{testimonial}/toggle-featured', [AdminTestimonialController::class, 'toggleFeatured'])->name('testimonials.toggle-featured');
        Route::resource('/testimonials', AdminTestimonialController::class)->except(['show']);
    });

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard redirect based on role
Route::get('/dashboard', function () {
    $user = Auth::user();

    return redirect()->route(
        $user->role === 'admin' ? 'admin.dashboard' : 'home'
    );
})->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';
