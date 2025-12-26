<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; background:#f4f4f4; padding:20px">

<div style="max-width:600px; background:white; padding:20px; border-radius:8px">
    <h2 style="color:#dc2626;">Verification Rejected ❌</h2>

    <p>Hello {{ $landlord->user->name }},</p>

    <p>
        Unfortunately, your landlord verification was <strong>rejected</strong>.
    </p>

    <p><strong>Reason:</strong></p>

    <blockquote style="background:#fef2f2; padding:12px; border-left:4px solid #dc2626">
        {{ $reason }}
    </blockquote>

    <p>
        You may update your information and resubmit your application.
    </p>

    <p style="margin-top:30px;">
        Regards,<br>
        <strong>OCHMS Admin Team</strong>
    </p>
</div>

</body>
</html>
