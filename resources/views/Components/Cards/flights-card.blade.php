@props([
    'flight',
    'url' => '#',
    'editUrl' => '#',
    'deleteUrl' => '#',
])
@php
    //Extract data from the flight object
    $flight_number = $flight->flight_number;
    $airline = $flight->airline->name;
    $origin = $flight->origin->code;
    $origin_city = $flight->origin->city;
    $destination = $flight->destination->code;
    $destination_city = $flight->destination->city;
    $departure_date = $flight->departure_date;
    $departure_time = $flight->departure_time;
    $arrival_time = $flight->arrival_time;
    $duration = $flight->duration;
    $airplane = $flight->airplane->model;
    $price = $flight->price;
    $total_seats = $flight->total_seats;
    $booked_seats = $flight->booked_seats;
    $available_seats = $flight->available_seats;
    $status = $flight->status;
    $booking_deadline = $flight->booking_deadline;
    $id = $flight->id;
    
    
    // Admin status configurations
    $statusConfig = [
        'scheduled' => [
            'color' => 'bg-blue-100/80 text-blue-700',
            'icon' => 'fa-calendar',
            'label' => 'Scheduled',
        ],
        'open' => [
            'color' => 'bg-emerald-100/80 text-emerald-700',
            'icon' => 'fa-circle-check',
            'label' => 'Open',
        ],
        'closing' => [
            'color' => 'bg-amber-100/80 text-amber-700',
            'icon' => 'fa fa-triangle-exclamation text-amber-400',
            'label' => 'Closing',
        ],
        'completed' => [
            'color' => 'bg-gray-100/80 text-gray-700',
            'icon' => 'fa-flag-checkered',
            'label' => 'Completed',
        ],
        'cancelled' => [
            'color' => 'bg-rose-100/80 text-rose-700',
            'icon' => 'fa-circle-xmark',
            'label' => 'Cancelled',
        ],
        'delayed' => [
            'color' => 'bg-yellow-100/80 text-yellow-700',
            'icon' => 'fa-clock',
            'label' => 'Delayed',
        ],
        'boarding' => [
            'color' => 'bg-indigo-100/80 text-indigo-700',
            'icon' => 'fa-person-walking',
            'label' => 'Boarding',
        ],
        'departed' => [
            'color' => 'bg-purple-100/80 text-purple-700',
            'icon' => 'fa-plane-departure',
            'label' => 'Departed',
        ],
    ];
    
    $statusColor = $statusConfig[$status]['color'] ?? 'bg-gray-100/80 text-gray-700';
    $statusIcon = $statusConfig[$status]['icon'] ?? 'fa-circle';
    $statusLabel = $statusConfig[$status]['label'] ?? ucfirst($status);
    
    // Calculate booking progress
    $bookedPercentage = $total_seats > 0 ? round(($booked_seats / $total_seats) * 100) : 0;
    
    // Format date for display
    $formattedDate = date('M d, Y', strtotime($departure_date));
    
    // Determine if flight is active for booking
    $isBookable = in_array($status, ['open', 'closing']);
@endphp

<div class="group relative block bg-gray-50/80 hover:bg-gray-100/40 backdrop-blur-sm transition-all duration-300 p-6 rounded-2xl shadow-md hover:shadow-2xl border border-gray-200/60 hover:scale-[1.02] active:scale-[0.98]">
    
    <div class="relative z-10">
        {{-- Header: Flight Number + Status --}}
        <div class="flex items-start justify-between mb-3 p-2">
            <div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-cyan-800 text-lg"></i>
                    <span class="font-bold text-gray-900 text-lg group-hover:text-cyan-700 transition">
                        {{ $flight_number }}
                    </span>
                </div>
                <div class="flex items-center gap-2 mt-0.5">
                    <p class="text-xs text-gray-500">{{ $airline }}</p>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold tracking-wide {{ $statusColor }} shadow-sm whitespace-nowrap">
                <i class="fa {{ $statusIcon }} mr-1"></i>
                {{ $statusLabel }}
            </span>
        </div>
        
        {{-- Route: Origin → Destination --}}
        <div class="flex items-center justify-between bg-white rounded-xl px-3 py-3 border border-gray-200/40 mb-5 shadow-md">
            <div class="text-center px-1">
                <p class="font-bold text-cyan-800 text-sm">{{ $origin }}</p>
                <p class="text-xs text-gray-500">
                    {{ $origin_city }}
                </p>
            </div>
            <div class="flex-1 mx-2">
                <div class="relative">
                    <div class="border-t-2 border-gray-300 border-dashed"></div>
                    <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 bg-white px-1 text-xs text-black-800">
                        <i class="fa-solid fa-plane"></i>
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 text-center mt-1">
                    <i class="fa-regular fa-clock mr-0.5"></i>
                    {{ $duration }}
                </p>
            </div>
            <div class="text-center px-1">
                <p class="font-bold text-cyan-800 text-sm">{{ $destination }}</p>
                <p class="text-xs text-gray-500">
                    {{ $destination_city }}
                </p>
            </div>
        </div>
        
        {{-- Key Info: Date, Seats, Price --}}
        <div class="grid grid-cols-3 gap-10 text-xs">
            {{-- Departure Date --}}
            <div>
                <p class="text-gray-500">
                    Departure
                </p>
                <p class="font-semibold text-cyan-800 text-sm">{{ $formattedDate }}</p>
                <p class="text-gray-400 text-[10px]">
                    <i class="fa-regular fa-clock mr-0.5"></i>
                    {{ $departure_time }}
                </p>
            </div>
            
            {{-- Seats --}}
            <div>
                <p class="text-gray-500">
                    Seats
                </p>
                <p class="font-semibold text-cyan-800 text-sm">{{ $available_seats }} left</p>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                    <div class="bg-cyan-800 h-1.5 rounded-full" style="width: {{ 100 - $bookedPercentage }}%"></div>
                </div>
            </div>
            
            {{-- Price --}}
            <div>
                <p class="text-gray-500">
                    <i class="fa-regular fa-dollar-sign mr-0.5"></i> Price
                </p>
                <p class="font-bold text-cyan-700 text-sm">${{ number_format($price, 2) }}</p>
            </div>
        </div>
        
        {{-- Footer: Actions --}}
        <div class="mt-4 pt-3 border-t border-gray-200/60">
            <div class="flex items-center justify-between">
                {{-- View Link --}}
                <a href="{{ $url }}" 
                   class="text-sm font-medium text-cyan-800 hover:text-cyan-800 transition flex items-center gap-1 group">
                    <i class="fa-regular fa-eye mr-0.5"></i>
                    View Flight
                    <i class="fa-solid fa-arrow-right text-xs transition-transform duration-300 group-hover:translate-x-1"></i>
                </a>
                
                {{-- Action Buttons --}}
                <div class="flex items-center gap-2">
                    {{-- Edit Button --}}
                    <a href="{{ $editUrl }}" 
                       class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all duration-200 hover:shadow-sm">
                        Edit
                    </a>
                    
                    {{-- Delete Button --}}
                    <form method="POST" action="{{ $deleteUrl }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-rose-700
                                transition-all duration-200"
                                onclick="return confirm('Are you sure you want to delete this flight?')">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            
            {{-- Booking Deadline --}}
            @if($booking_deadline && $status !== 'completed' && $status !== 'cancelled')
                <div class="mt-2 text-[10px] text-gray-400 flex items-center gap-1">
                    <i class="fa-regular fa-clock mr-0.5"></i>
                    Book by {{ date('M d, Y', strtotime($booking_deadline)) }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Delete Confirmation Script --}}
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete flight ' + id + '?')) {
            // Submit delete form or redirect
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>