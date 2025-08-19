<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destinasi;

class DestinasiController extends Controller
{
    public function index()
    {
        $destinasis = Destinasi::where('status', 1)->latest()->get();
        return view('destinations', compact('destinasis', ));
    }

    public function show($id)
    {
        $destinasi = Destinasi::findOrFail($id);
        return view('destinations-show', compact('destinasi'));
    }
}
