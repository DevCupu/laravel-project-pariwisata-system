<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Booking;
use App\Models\CampingPackage;
use App\Models\Event;
use App\Models\GeneralFacilityInfo;
use App\Models\MenuItem;
use App\Models\PhotoSpot;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'activities' => Activity::count(),
            'activities_active' => Activity::where('is_active', true)->count(),
            'camping_packages' => CampingPackage::count(),
            'events' => Event::count(),
            'photo_spots' => PhotoSpot::count(),
            'menu_items' => MenuItem::count(),
            'general_facilities' => GeneralFacilityInfo::count(),
            'bookings_pending' => Booking::where('status', 'pending')->count(),
            'bookings_total' => Booking::count(),
        ];

        $recentBookings = Booking::with('facility')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $statusCounts = [
            'pending' => 0,
            'confirmed' => 0,
            'cancelled' => 0,
            'completed' => 0,
        ];

        foreach (Booking::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status') as $status => $total) {
            if (array_key_exists($status, $statusCounts)) {
                $statusCounts[$status] = (int) $total;
            }
        }

        $bookingsPerDay = Booking::where('created_at', '>=', Carbon::today()->subDays(6))
            ->get(['created_at'])
            ->countBy(fn (Booking $booking) => Carbon::parse($booking->created_at)->format('Y-m-d'));

        $bookingTrend = collect(range(6, 0))->map(function (int $offset) use ($bookingsPerDay) {
            $date = Carbon::today()->subDays($offset);

            return [
                'day' => $date->isoFormat('ddd'),
                'date' => $date->isoFormat('d MMM'),
                'count' => $bookingsPerDay->get($date->format('Y-m-d'), 0),
            ];
        });

        $upcomingBookings = Booking::whereNotNull('visit_date')
            ->whereDate('visit_date', '>=', Carbon::today())
            ->orderBy('visit_date')
            ->limit(5)
            ->get(['id', 'booking_code', 'name', 'guests', 'visit_date', 'status']);

        return view('admin.dashboard', compact(
            'counts',
            'recentBookings',
            'statusCounts',
            'bookingTrend',
            'upcomingBookings'
        ));
    }
}
