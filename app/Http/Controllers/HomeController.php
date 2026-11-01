<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\Activity;
use App\Models\CampingPackage;
use App\Models\Event;
use App\Models\GeneralFacilityInfo;
use App\Models\MenuItem;
use App\Models\PhotoSpot;
use App\Models\Testimonial;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $aboutSections = AboutSection::active()
            ->orderBy('display_order')
            ->get();

        $photoSpots = PhotoSpot::active()->ordered()->get();
        $generalFacilities = GeneralFacilityInfo::active()->ordered()->get();
        $campingPackages = CampingPackage::active()->ordered()->get();

        $featuredActivities = Activity::active()
            ->ordered()
            ->orderByDesc('is_featured')
            ->limit(3)
            ->get();

        $menuHighlights = MenuItem::available()
            ->ordered()
            ->whereNotNull('image_path')
            ->limit(4)
            ->get();

        $events = Event::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::today());
            })
            ->orderByDesc('is_featured')
            ->orderBy('start_date')
            ->limit(3)
            ->get();

        $counts = [
            'facilities' => $generalFacilities->count(),
            'packages' => $campingPackages->count(),
            'spots' => $photoSpots->count(),
            'activities' => Activity::active()->count(),
        ];

        $testimonials = Testimonial::active()
            ->ordered()
            ->limit(6)
            ->get();

        return view('home', compact(
            'aboutSections',
            'photoSpots',
            'generalFacilities',
            'campingPackages',
            'featuredActivities',
            'menuHighlights',
            'events',
            'counts',
            'testimonials'
        ));
    }
}
