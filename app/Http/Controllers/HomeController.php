<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\CampingPackage;
use App\Models\GeneralFacilityInfo;
use App\Models\PhotoSpot;

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

        return view('home', compact(
            'aboutSections',
            'photoSpots',
            'generalFacilities',
            'campingPackages'
        ));
    }
}