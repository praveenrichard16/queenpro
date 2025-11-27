@php
    use Illuminate\Support\Str;

    $statusLabel = $ticket->status->label();
    $priorityLabel = $ticket->priority->label();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket Update</title>
</head>
<body style="font-family:'Segoe UI',sans-serif;background-color:#f5f7fb;margin:0;padding:32px;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;">
    <tr>
        <td style="padding:24px;background:#024550;color:#ffffff;">
            <h2 style="margin:0;font-size:20px;">Ticket Update</h2>
            <p style="margin:8px 0 0;font-size:14px;opacity:0.85;">Ticket {{ $ticket->ticket_number }}</p>
        </td>
    </tr>
    <tr>
        <td style="padding:24px;">
            <p style="margin:0 0 16px;">
                Hello,
            </p>
            <p style="margin:0 0 16px;">
                Your support ticket <strong>{{ $ticket->ticket_number }}</strong> has been updated by our team.
            </p>

            <table cellpadding="0" cellspacing="0" style="width:100%;margin-bottom:24px;">
                <tr>
                    <td style="padding:8px 0;color:#6b7280;font-size:14px;width:140px;">Subject</td>
                    <td style="padding:8px 0;color:#111827;font-size:14px;">{{ $ticket->subject }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#6b7280;font-size:14px;">Status</td>
                    <td style="padding:8px 0;color:#111827;font-size:14px;">{{ $statusLabel }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#6b7280;font-size:14px;">Priority</td>
                    <td style="padding:8px 0;color:#111827;font-size:14px;">{{ $priorityLabel }}</td>
                </tr>
                @if($latestMessage ?? false)
                <tr>
                    <td style="padding:8px 0;color:#6b7280;font-size:14px;">Latest reply</td>
                    <td style="padding:8px 0;color:#111827;font-size:14px;">
                        {!! nl2br(e(Str::limit($latestMessage->body, 200))) !!}
                    </td>
                </tr>
                @endif
            </table>

            <a href="{{ route('admin.support.tickets.show', $ticket) }}" style="display:inline-block;padding:12px 20px;background:#024550;color:#ffffff;text-decoration:none;border-radius:8px;font-size:14px;">
                View Ticket
            </a>

            <p style="margin:24px 0 0;font-size:12px;color:#9ca3af;">
                Thank you for choosing WowDash Support.
            </p>
        </td>
    </tr>
</table>
</body>
</html>

