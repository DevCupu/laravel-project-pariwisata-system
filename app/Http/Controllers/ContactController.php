<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormSubmitted;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

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
                ->send(new ContactFormSubmitted($data));
        } catch (\Throwable $e) {
            Log::error('Contact form mail failed: '.$e->getMessage());

            return back()
                ->withInput($data)
                ->with('error', 'Pesan belum berhasil dikirim. Silakan coba lagi atau hubungi kami melalui WhatsApp.');
        }

        return back()->with('success', 'Pesan Anda sudah masuk ke antrean dan akan segera diproses.');
    }
}
