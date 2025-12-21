<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('Permintaan Booking ' . $this->booking->booking_code . ' diterima')
            ->view('emails.booking.submitted')
            ->with(['booking' => $this->booking]);
    }
}
