<?php

namespace App\Mail;

use App\Models\Listing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Listing $listing;
    public ?string $reason;

    public function __construct(Listing $listing, ?string $reason = null)
    {
        $this->listing = $listing;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Your Booking Request Was Rejected')
            ->view('emails.booking-rejected');
    }
}