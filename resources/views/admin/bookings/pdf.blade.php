<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking Receipt - {{ $booking->booking_reference }}</title>

    <style>
        /* Tailwind-inspired utility classes (inline-friendly) */
        .font-sans { font-family: DejaVu Sans, sans-serif; }
        .text-gray-700 { color: #374151; }
        .text-gray-500 { color: #6B7280; }
        .text-indigo-600 { color: #4F46E5; }
        .bg-white { background: #ffffff; }
        .border { border: 1px solid #e5e7eb; }
        .rounded-xl { border-radius: 12px; }
        .p-6 { padding: 24px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-1 { margin-bottom: 4px; }
        .text-lg { font-size: 18px; }
        .text-sm { font-size: 14px; }
        .font-bold { font-weight: bold; }
        .w-40 { width: 160px; display: inline-block; }
        .section { margin-bottom: 28px; }
        .divider { border-bottom: 1px solid #e5e7eb; margin: 12px 0; }
    </style>
</head>

<body class="font-sans bg-white p-6 text-gray-700">

    <div class="border rounded-xl p-6">

        <h1 class="text-indigo-600 text-lg font-bold mb-2">
            Booking Receipt
        </h1>
        <div class="divider"></div>

        <!-- Booking Info -->
        <div class="section">
            <h2 class="text-indigo-600 font-bold mb-2">Booking Information</h2>

            <div class="mb-1">
                <span class="w-40 font-bold">Reference:</span>
                {{ $booking->booking_reference }}
            </div>

            <div class="mb-1">
                <span class="w-40 font-bold">Passenger:</span>
                {{ $booking->user->getFullNameAttribute() }}
            </div>

            <div class="mb-1">
                <span class="w-40 font-bold">Email:</span>
                {{ $booking->user->email }}
            </div>

            <div class="mb-1">
                <span class="w-40 font-bold">Seats:</span>
                {{ $booking->number_of_seats }}
            </div>

            <div class="mb-1">
                <span class="w-40 font-bold">Total Price:</span>
                ${{ number_format($booking->total_price, 2) }}
            </div>
        </div>

        <!-- Flight Info -->
        <div class="section">
            <h2 class="text-indigo-600 font-bold mb-2">Flight Details</h2>

            <div class="mb-1">
                <span class="w-40 font-bold">Flight:</span>
                {{ $booking->flight->flight_number }}
            </div>

            <div class="mb-1">
                <span class="w-40 font-bold">Route:</span>
                {{ $booking->flight->origin->code }} ({{ $booking->flight->origin->city }})
                →
                {{ $booking->flight->destination->code }} ({{ $booking->flight->destination->city }})
            </div>

            <div class="mb-1">
                <span class="w-40 font-bold">Departure:</span>
                {{ \Carbon\Carbon::parse($booking->flight->departure_time)->format('M d, Y H:i') }}
            </div>

            <div class="mb-1">
                <span class="w-40 font-bold">Arrival:</span>
                {{ \Carbon\Carbon::parse($booking->flight->arrival_time)->format('M d, Y H:i') }}
            </div>

            <div class="mb-1">
                <span class="w-40 font-bold">Airline:</span>
                {{ $booking->flight->airline->name ?? 'N/A' }}
            </div>
        </div>

        <div class="text-center text-sm text-gray-500">
            Thank you for choosing SkyBooker.<br>
            This receipt was generated automatically.
        </div>

    </div>

</body>
</html>
