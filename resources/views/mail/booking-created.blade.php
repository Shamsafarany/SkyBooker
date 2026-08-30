<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; margin: 20px auto; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <tr>
            <td style="padding: 10px 0;">
                <h1 style="color: #4F46E5; margin: 0; font-size: 24px;">Booking Confirmed!</h1>
                <hr style="border: none; border-top: 2px solid #4F46E5; margin: 15px 0;">
            </td>
        </tr>
        <tr>
            <td style="padding: 5px 0;">
                <p style="font-size: 16px; line-height: 1.5; color: #333; margin: 10px 0;">
                    Dear <strong>{{ $booking->user->getFullnameAttribute() }}</strong>,
                </p>
                <p style="font-size: 16px; line-height: 1.5; color: #333; margin: 10px 0;">
                    Your booking has been confirmed. Here are the details:
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 0;">
                <h3 style="color: #4F46E5; margin: 15px 0 10px 0; font-size: 18px;">Booking Details</h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; font-size: 15px; color: #333;">
                        <strong>Reference:</strong> {{ $booking->booking_reference }}
                    </li>
                    <li style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; font-size: 15px; color: #333;">
                        <strong>Flight:</strong> {{ $booking->flight->flight_number }}
                    </li>
                    <li style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; font-size: 15px; color: #333;">
                        <strong>Seats:</strong> {{ $booking->number_of_seats }}
                    </li>
                    <li style="padding: 8px 0; font-size: 16px; color: #4F46E5; font-weight: bold;">
                        <strong>Total:</strong> ${{ number_format($booking->total_price, 2) }}
                    </li>
                </ul>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 0;">
                <h3 style="color: #4F46E5; margin: 15px 0 10px 0; font-size: 18px;">Flight Details</h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; font-size: 15px; color: #333;">
                        <strong>Route:</strong> 
                        {{ $booking->flight->origin->code }} ({{ $booking->flight->origin->city }}) 
                        → 
                        {{ $booking->flight->destination->code }} ({{ $booking->flight->destination->city }})
                    </li>
                    <li style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; font-size: 15px; color: #333;">
                        <strong>Departure:</strong> 
                        {{ \Carbon\Carbon::parse($booking->flight->departure_time)->format('M d, Y H:i') }}
                    </li>
                    <li style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; font-size: 15px; color: #333;">
                        <strong>Arrival:</strong> 
                        {{ \Carbon\Carbon::parse($booking->flight->arrival_time)->format('M d, Y H:i') }}
                    </li>
                    <li style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; font-size: 15px; color: #333;">
                        <strong>Airline:</strong> {{ $booking->flight->airline->name ?? 'N/A' }}
                    </li>
                    <li style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; font-size: 15px; color: #333;">
                        <strong>Seats:</strong> {{ $booking->number_of_seats }}
                    </li>
                    <li style="padding: 8px 0; font-size: 16px; color: #4F46E5; font-weight: bold;">
                        <strong>Total:</strong> ${{ number_format($booking->total_price, 2) }}
                    </li>
                </ul>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px 0 10px 0; text-align: center;">
                <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                    style="background-color: #4F46E5; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; font-size: 16px;">
                    View Your Booking
                </a>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px 0 10px 0; text-align: center; border-top: 1px solid #e5e7eb; margin-top: 20px;">
                <p style="color:#6b7280; font-size:14px;">
                    The booking PDF is attached.
                </p>
                <p style="font-size: 14px; color: #6B7280; margin: 5px 0;">
                    Thank you for choosing SkyBooker!
                </p>
                <p style="font-size: 12px; color: #9CA3AF; margin: 5px 0;">
                    This is an automated confirmation. Please do not reply to this email.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>