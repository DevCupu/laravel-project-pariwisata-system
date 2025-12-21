<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\View\View;

class CafeMenuController extends Controller
{
    public function index(): View
    {
        $items = MenuItem::available()->ordered()->get()->groupBy('section');

        return view('menu.index', [
            'menuSections' => $items,
        ]);
    }
}
