<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Request Rejected</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f9fafb; padding:20px;">

<div style="max-width:600px; margin:auto; background:#ffffff; border-radius:8px; padding:24px;">

    <h2 style="color:#dc2626;">Booking Request Rejected</h2>

    <p>Hello {{ $listing->ocs->user->name }},</p>

    <p>
        Unfortunately, your booking request for the following property has been rejected:
    </p>

    <div style="background:#f3f4f6; padding:16px; border-radius:6px; margin:16px 0;">
        <strong>{{ $listing->title }}</strong><br>
        {{ $listing->address }}<br>
        <strong>Landlord:</strong> {{ $listing->landlord->user->name }}
    </div>

    @if($reason)
        <p><strong>Reason provided:</strong></p>
        <p style="background:#fef2f2; padding:12px; border-left:4px solid #dc2626;">
            {{ $reason }}
        </p>
    @endif

    <p>
        You may now browse other listings and submit a new booking request.
    </p>

    <a href="{{ route('ocs.listings.browse') }}"
       style="display:inline-block; margin-top:16px;
              background:#111827; color:white;
              padding:12px 20px; border-radius:6px;
              text-decoration:none;">
        Browse Listings
    </a>

    <p style="margin-top:24px; font-size:12px; color:#6b7280;">
        This is an automated message from OCHMS.
    </p>

</div>

</body>
</html>
