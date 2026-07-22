@props([
    'flight_number',
    'airline',
    'origin',
    'origin_city',
    'destination',
    'destination_city',
    'departure_date',
    'departure_time',
    'arrival_time',
    'duration',
    'airplane',
    'price',
    'total_seats',
    'booked_seats',
    'available_seats',
    'status',
    'booking_deadline',
    'url' => '#',
    'editUrl' => '#',
    'deleteUrl' => '#'
])

@php
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
            'icon' => 'fa-triangle-exclamation',
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

<div class="group relative block bg-purple-50/80 hover:bg-purple-100/80 backdrop-blur-sm transition-all duration-300 p-6 rounded-2xl shadow-md hover:shadow-2xl border border-gray-200/60 hover:scale-[1.02] active:scale-[0.98]">
    
    <div class="relative z-10">
        {{-- Header: Flight Number + Status --}}
        <div class="flex items-start justify-between mb-3 p-2">
            <div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-purple-800 text-lg"></i>
                    <span class="font-bold text-gray-900 text-lg group-hover:text-purple-700 transition">
                        {{ $flight_number }}
                    </span>
                </div>
                <div class="flex items-center gap-2 mt-0.5">
                    <i class="fa-solid fa-building text-gray-400 text-xs"></i>
                    <p class="text-xs text-gray-500">{{ $airline }}</p>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold tracking-wide {{ $statusColor }} shadow-sm whitespace-nowrap">
                <i class="fa-regular {{ $statusIcon }} mr-1"></i>
                {{ $statusLabel }}
            </span>
        </div>
        
        {{-- Route: Origin → Destination --}}
        <div class="flex items-center justify-between bg-white rounded-xl px-3 py-2 border border-gray-200/40 mb-3">
            <div class="text-center">
                <p class="font-bold text-gray-800 text-sm">{{ $origin }}</p>
                <p class="text-xs text-gray-500">
                    <i class="fa-regular fa-location-dot mr-0.5"></i>
                    {{ $origin_city }}
                </p>
            </div>
            <div class="flex-1 mx-2">
                <div class="relative">
                    <div class="border-t-2 border-purple-300 border-dashed"></div>
                    <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 bg-white px-1 text-xs text-purple-800">
                        <i class="fa-solid fa-plane"></i>
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 text-center mt-1">
                    <i class="fa-regular fa-clock mr-0.5"></i>
                    {{ $duration }}
                </p>
            </div>
            <div class="text-center">
                <p class="font-bold text-gray-800 text-sm">{{ $destination }}</p>
                <p class="text-xs text-gray-500">
                    <i class="fa-regular fa-location-dot mr-0.5"></i>
                    {{ $destination_city }}
                </p>
            </div>
        </div>
        
        {{-- Key Info: Date, Seats, Price --}}
        <div class="grid grid-cols-3 gap-10 text-xs">
            {{-- Departure Date --}}
            <div>
                <p class="text-gray-500">
                    <i class="fa-regular fa-calendar mr-0.5"></i> Departure
                </p>
                <p class="font-semibold text-gray-800 text-sm">{{ $formattedDate }}</p>
                <p class="text-gray-400 text-[10px]">
                    <i class="fa-regular fa-clock mr-0.5"></i>
                    {{ $departure_time }}
                </p>
            </div>
            
            {{-- Seats --}}
            <div>
                <p class="text-gray-500">
                    <i class="fa-regular fa-chair mr-0.5"></i> Seats
                </p>
                <p class="font-semibold text-gray-800 text-sm">{{ $available_seats }} left</p>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                    <div class="bg-purple-800 h-1.5 rounded-full" style="width: {{ 100 - $bookedPercentage }}%"></div>
                </div>
            </div>
            
            {{-- Price --}}
            <div>
                <p class="text-gray-500">
                    <i class="fa-regular fa-dollar-sign mr-0.5"></i> Price
                </p>
                <p class="font-bold text-purple-700 text-sm">${{ number_format($price, 2) }}</p>
                @if($isBookable)
                    <span class="text-[10px] text-emerald-600 font-medium">
                        <i class="fa-regular fa-circle-check mr-0.5"></i> Available
                    </span>
                @endif
            </div>
        </div>
        
        {{-- Footer: Actions --}}
        <div class="mt-4 pt-3 border-t border-gray-200/60">
            <div class="flex items-center justify-between">
                {{-- View Link --}}
                <a href="{{ $url }}" 
                   class="text-sm font-medium text-purple-800 hover:text-purple-800 transition flex items-center gap-1 group">
                    <i class="fa-regular fa-eye mr-0.5"></i>
                    View Flight
                    <i class="fa-solid fa-arrow-right text-xs transition-transform duration-300 group-hover:translate-x-1"></i>
                </a>
                
                {{-- Action Buttons --}}
                <div class="flex items-center gap-2">
                    {{-- Edit Button --}}
                    <a href="{{ $editUrl }}" 
                       class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all duration-200 hover:shadow-sm">
                        <i class="fa-regular fa-pen text-xs"></i>
                        Edit
                    </a>
                    
                    {{-- Delete Button --}}
                    <button onclick="confirmDelete({{ $flight['id'] ?? 0 }})" 
                            class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-all duration-200 hover:shadow-sm">
                        <i class="fa-regular fa-trash text-xs"></i>
                        Delete
                    </button>
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
        if (confirm('Are you sure you want to delete flight #' + id + '?')) {
            // Submit delete form or redirect
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>