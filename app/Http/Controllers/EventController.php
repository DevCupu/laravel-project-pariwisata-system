<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $query = Event::query()->where('is_active', true);

        if ($q = $request->input('q')) {
            $query->where('title', 'like', "%{$q}%");
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        $events = $query->orderByDesc('is_featured')
            ->orderByDesc('start_date')
            ->paginate(9)
            ->withQueryString();

        return view('events.index', [
            'events' => $events,
            'searchQuery' => $q ?? null,
        ]);
    }

    public function show(string $slug): View
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        if (! $event->is_active) {
            abort(404);
        }

        return view('events.show', ['event' => $event]);
    }
}
