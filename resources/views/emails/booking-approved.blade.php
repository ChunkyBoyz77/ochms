@php
    $landlord = $listing->landlord->user;
    $student = $listing->ocs->user;

    // Normalize phone number (Malaysia example)
    $phone = preg_replace('/[^0-9]/', '', $landlord->phone_number);

    $whatsappLink = "https://wa.me/{$phone}?text=" . urlencode(
        "Hi {$landlord->name}, I am {$student->name}. "
        . "My booking request for '{$listing->title}' has been approved."
    );
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Approved</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:30px">

<div style="max-width:600px; margin:auto; background:white; padding:30px; border-radius:8px">

    <h2 style="color:#111">
        🎉 Booking Approved!
    </h2>

    <p>
        Hi <strong>{{ $student->name }}</strong>,
    </p>

    <p>
        Your booking request for the property below has been
        <strong style="color:green">approved</strong>.
    </p>

    <hr>

    <h3>{{ $listing->title }}</h3>
    <p>{{ $listing->address }}</p>
    <p>
        <strong>RM {{ number_format($listing->monthly_rent) }}</strong> / month
    </p>

    <hr>

    <p>
        You may now contact the landlord directly to discuss
        payment, move-in date, and further arrangements.
    </p>

    <p style="text-align:center; margin:30px 0">
        <a href="{{ $whatsappLink }}"
           style="
               display:inline-block;
               background:#25D366;
               color:white;
               padding:14px 22px;
               border-radius:6px;
               text-decoration:none;
               font-weight:bold;
           ">
           <i class="fa-brands fa-whatsapp"></i> Contact Landlord on WhatsApp
        </a>
    </p>

    <p style="font-size:13px; color:#666">
        Landlord: {{ $landlord->name }}<br>
        Phone: {{ $landlord->phone_number }}
    </p>

    <p style="font-size:12px; color:#999; margin-top:30px">
        This platform does not handle payments. All transactions
        are arranged directly with the landlord.
    </p>

</div>

</body>
</html>
