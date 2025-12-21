<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $activities = Activity::active()
            ->ordered()
            ->when($request->input('category'), fn ($query, $category) => $query->where('category', $category))
            ->get()
            ->groupBy('category');

        $featured = Activity::active()->where('is_featured', true)->ordered()->take(3)->get();

        return view('activities.index', [
            'groupedActivities' => $activities,
            'featuredActivities' => $featured,
        ]);
    }
}
