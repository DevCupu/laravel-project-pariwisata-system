<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking) {}

    public function build(): self
    {
        $this->booking->loadMissing(['facility', 'menuItems']);

        return $this
            ->subject('Konfirmasi Booking '.$this->booking->booking_code)
            ->view('emails.booking.confirmed')
            ->with(['booking' => $this->booking]);
    }
}
