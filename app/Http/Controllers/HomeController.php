<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 6 destinasi aktif terbaru (anggap sebagai "populer")
        $destinasiPopuler = Destinasi::with('kategori')
            ->where('status', 1)
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('destinasiPopuler'));
    }
}
