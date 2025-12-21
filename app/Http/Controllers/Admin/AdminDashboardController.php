<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\CampingPackage;
use App\Models\Event;
use App\Models\PhotoSpot;
use App\Models\MenuItem;
use App\Models\Booking;
use App\Models\GeneralFacilityInfo;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'activities' => Activity::count(),
            'camping_packages' => CampingPackage::count(),
            'events' => Event::count(),
            'photo_spots' => PhotoSpot::count(),
            'menu_items' => MenuItem::count(),
            'general_facilities' => GeneralFacilityInfo::count(),
            'bookings_pending' => Booking::where('status', 'pending')->count(),
            'bookings_total' => Booking::count(),
        ];

        $recentBookings = Booking::orderByDesc('created_at')->limit(6)->get();

        return view('admin.dashboard', compact('counts', 'recentBookings'));
    }
}
