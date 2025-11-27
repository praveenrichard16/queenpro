@php
    $customerName = $ticket->customer->name ?? 'Customer';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Support Ticket</title>
</head>
<body style="font-family: 'Segoe UI', sans-serif; background-color:#f5f7fb; margin:0; padding:32px;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;">
    <tr>
        <td style="padding:24px;background:#024550;color:#ffffff;">
            <h2 style="margin:0;font-size:20px;">New Support Ticket</h2>
            <p style="margin:8px 0 0;font-size:14px;opacity:0.85;">Ticket {{ $ticket->ticket_number }}</p>
        </td>
    </tr>
    <tr>
        <td style="padding:24px;">
            <p style="margin:0 0 16px;">Hi Support Team,</p>
            <p style="margin:0 0 16px;">
                A new ticket has been created by <strong>{{ $customerName }}</strong>.
            </p>
            <table cellpadding="0" cellspacing="0" style="width:100%;margin-bottom:24px;">
                <tr>
                    <td style="padding:8px 0;color:#6b7280;font-size:14px;width:120px;">Subject</td>
                    <td style="padding:8px 0;color:#111827;font-size:14px;">{{ $ticket->subject }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#6b7280;font-size:14px;">Priority</td>
                    <td style="padding:8px 0;color:#111827;font-size:14px;">{{ $ticket->priority->label() }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#6b7280;font-size:14px;">Status</td>
                    <td style="padding:8px 0;color:#111827;font-size:14px;">{{ $ticket->status->label() }}</td>
                </tr>
            </table>

            @if($ticket->description)
                <h4 style="margin:0 0 12px;font-size:16px;color:#111827;">Message</h4>
                <p style="margin:0 0 16px;color:#374151;font-size:14px;line-height:1.6;">
                    {!! nl2br(e($ticket->description)) !!}
                </p>
            @endif

            <a href="{{ route('admin.support.tickets.show', $ticket) }}" style="display:inline-block;padding:12px 20px;background:#024550;color:#ffffff;text-decoration:none;border-radius:8px;font-size:14px;">
                View Ticket
            </a>

            <p style="margin:24px 0 0;font-size:12px;color:#9ca3af;">
                You’re receiving this email because ticket notifications are enabled in WowDash Support.
            </p>
        </td>
    </tr>
</table>
</body>
</html>

