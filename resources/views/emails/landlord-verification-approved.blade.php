<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; background:#f4f4f4; padding:20px">

<div style="max-width:600px; background:white; padding:20px; border-radius:8px">
    <h2 style="color:#16a34a;">Verification Approved 🎉</h2>

    <p>Hello {{ $landlord->user->name }},</p>

    <p>
        Your landlord verification has been <strong>approved</strong>.
        You now have full access to OCHMS landlord features.
    </p>

    <p>
        You may now list properties and manage bookings.
    </p>

    <p style="margin-top:30px;">
        Regards,<br>
        <strong>OCHMS Admin Team</strong>
    </p>
</div>

</body>
</html>
