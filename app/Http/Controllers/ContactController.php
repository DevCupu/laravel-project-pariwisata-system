<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormSubmitted;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        // Send email to site admin (from MAIL_FROM_ADDRESS)
        try {
            Mail::to(config('mail.from.address'))
                ->queue(new ContactFormSubmitted($data));
        } catch (\Exception $e) {
            // If mail fails, continue and show friendly message
            Log::error('Contact form mail failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Pesan Anda telah terkirim. Kami akan menghubungi Anda segera.');
    }
}
