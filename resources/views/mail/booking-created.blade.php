<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
</head>
<body>
    <h1>Booking Confirmed!</h1>
    
    <p>Dear {{ $booking->user->first_name }},</p>
    
    <p>Your booking has been confirmed.</p>
    
    <h3>Booking Details:</h3>
    <ul>
        <li><strong>Reference:</strong> {{ $booking->booking_reference }}</li>
        <li><strong>Flight:</strong> {{ $booking->flight->flight_number }}</li>
        <li><strong>Seats:</strong> {{ $booking->number_of_seats }}</li>
        <li><strong>Total:</strong> ${{ number_format($booking->total_price, 2) }}</li>
    </ul>
    
    <p>Thank you for choosing SkyBooker!</p>
</body>
</html>