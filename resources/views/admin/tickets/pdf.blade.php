<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket {{ $ticket->ticket_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: white;
        }
        .ticket {
            max-width: 600px;
            margin: 0 auto;
            border: 2px solid #0D9488;
            border-radius: 12px;
            padding: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0D9488;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #0D9488;
            font-size: 28px;
            margin: 0;
        }
        .row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .label {
            font-weight: bold;
            color: #6b7280;
            margin-right: 5px;
        }
        .value {
            color: #1f2937;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #9ca3af;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-issued {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-used {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }
        .badge-expired {
            background: #e5e7eb;
            color: #4b5563;
        }
    </style>
</head>
<body>

    <div class="ticket">
        <div class="header">
            <h1>SkyBooker</h1>
            <p style="color: #6b7280; margin: 0;">Passenger Ticket</p>
        </div>

        {{--Ticket Number --}}
        <div class="row">
            <span class="label">Ticket Number</span>
            <span class="value"><strong>{{ $ticket->ticket_number }}</strong></span>
        </div>

        {{--Passenger - Direct from ticket --}}
        <div class="row">
            <span class="label">Passenger</span>
            <span class="value">{{ $ticket->passenger->first_name }} {{ $ticket->passenger->last_name }}</span>
        </div>

        {{--Email - Direct from ticket --}}
        <div class="row">
            <span class="label">Email</span>
            <span class="value">{{ $ticket->passenger->email }}</span>
        </div>

        {{--Airline --}}
        <div class="row">
            <span class="label">Airline</span>
            <span class="value">
                @if($ticket->passenger && $ticket->passenger->booking && $ticket->passenger->booking->flight)
                    {{ $ticket->passenger->booking->flight->airline->name }}
                @else
                    N/A
                @endif
            </span>
        </div>

        {{--Flight Number --}}
        <div class="row">
            <span class="label">Flight</span>
            <span class="value">
                @if($ticket->passenger && $ticket->passenger->booking && $ticket->passenger->booking->flight)
                    {{ $ticket->passenger->booking->flight->flight_number }}
                @else
                    N/A
                @endif
            </span>
        </div>

        {{--Seat Number --}}
        <div class="row">
            <span class="label">Seat</span>
            <span class="value">{{ $ticket->seat_number ?? 'N/A' }}</span>
        </div>

        {{--Class --}}
        <div class="row">
            <span class="label">Class</span>
            <span class="value">{{ ucfirst($ticket->class) }}</span>
        </div>

        {{--Meal Preference --}}
        <div class="row">
            <span class="label">Meal Preference</span>
            <span class="value">{{ ucfirst($ticket->meal_preference ?? 'None') }}</span>
        </div>

        {{--Origin & Destination --}}
        @if($ticket->passenger && $ticket->passenger->booking && $ticket->passenger->booking->flight)
            <div class="row" style="border-bottom: none;">
                <span class="label">Route</span>
                <span class="value">
                    {{ $ticket->passenger->booking->flight->origin->code ?? 'N/A' }} 
                -->
                    {{ $ticket->passenger->booking->flight->destination->code ?? 'N/A' }}
                </span>
            </div>

            <div class="row" style="border-bottom: none;">
                <span class="label">Departure</span>
                <span class="value">
                    {{ date('M d, Y H:i', strtotime($ticket->passenger->booking->flight->departure_date)) }}
                </span>
            </div>
            <div class="row" style="border-bottom: none;">
                <span class="label">Arrival</span>
                <span class="value">
                    {{ date('M d, Y H:i', strtotime($ticket->passenger->booking->flight->arrival_date)) }}
                </span>
            </div>
        @endif

        <div style="text-align: center; margin-top: 20px; padding-top: 15px; border-top: 2px solid #0D9488;">
            <span style="font-size: 14px; color: #0D9488;">
                Valid Boarding Pass
            </span>
        </div>

        <div class="footer">
            Issued: {{ $ticket->issued_at ? date('M d, Y H:i', strtotime($ticket->issued_at)) : 'N/A' }}
        </div>
    </div>

</body>
</html>