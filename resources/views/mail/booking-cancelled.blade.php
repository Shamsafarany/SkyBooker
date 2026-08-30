<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking Cancelled</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f9fafb; padding:20px;">
    <table align="center" cellpadding="0" cellspacing="0" width="100%" 
            style="max-width:600px; background:#ffffff; padding:20px; border-radius:12px;">
        
        <tr>
            <td>
                <h1 style="color:#dc2626; margin-bottom:10px;">Booking Cancelled</h1>
                <p style="color:#374151; font-size:16px;">
                    Dear <strong>{{ $booking->user->getFullNameAttribute() }}</strong>,
                </p>

                <p style="color:#374151; font-size:16px;">
                    Your booking <strong>{{ $booking->booking_reference }}</strong> has been cancelled.
                </p>

                <hr style="border:none; border-top:1px solid #e5e7eb; margin:20px 0;">
            </td>
        </tr>

        <tr>
            <td>
                <h3 style="color:#dc2626; margin-bottom:10px;">Cancelled Booking Details</h3>

                <ul style="padding:0; list-style:none; font-size:15px; color:#374151;">
                    <li style="padding:6px 0;">
                        <strong>Flight:</strong> {{ $booking->flight->flight_number }}
                    </li>
                    <li style="padding:6px 0;">
                        <strong>Route:</strong>
                        {{ $booking->flight->origin->code }} → {{ $booking->flight->destination->code }}
                    </li>
                    <li style="padding:6px 0;">
                        <strong>Seats:</strong> {{ $booking->number_of_seats }}
                    </li>
                    <li style="padding:6px 0;">
                        <strong>Total:</strong> ${{ number_format($booking->total_price, 2) }}
                    </li>
                </ul>
            </td>
        </tr>

        <tr>
            <td style="text-align:center; padding-top:20px;">
                <p style="color:#9ca3af; font-size:12px;">
                    Thank you for choosing SkyBooker.
                </p>
            </td>
        </tr>

    </table>
</body>
</html>
