<?php

namespace App\Mail;

use App\Models\Landlord;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LandlordVerificationApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Landlord $landlord) {}

    public function build()
    {
        return $this
            ->subject('Your Landlord Verification Has Been Approved')
            ->view('emails.landlord-verification-approved');
    }
}
