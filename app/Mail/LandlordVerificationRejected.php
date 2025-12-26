<?php

namespace App\Mail;

use App\Models\Landlord;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LandlordVerificationRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Landlord $landlord,
        public string $reason
    ) {}

    public function build()
    {
        return $this
            ->subject('Your Landlord Verification Was Rejected')
            ->view('emails.landlord-verification-rejected');
    }
}

