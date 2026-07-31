@props([
    'booking',
    'url' => '#',
    'editUrl' => '#',
    'deleteUrl' => '#',
    'confirmUrl' => '#',
    'cancelUrl' => '#',
])

@php
    // Status configurations
    $statusConfig = [
        'pending' => [
            'color' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'icon' => 'fa-clock',
            'label' => 'Pending',
        ],
        'confirmed' => [
            'color' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'icon' => 'fa-circle-check',
            'label' => 'Confirmed',
        ],
        'cancelled' => [
            'color' => 'bg-rose-100 text-rose-800 border-rose-200',
            'icon' => 'fa-circle-xmark',
            'label' => 'Cancelled',
        ],
        'completed' => [
            'color' => 'bg-gray-100 text-gray-800 border-gray-200',
            'icon' => 'fa-flag-checkered',
            'label' => 'Completed',
        ],
        'failed' => [
            'color' => 'bg-red-100 text-red-800 border-red-200',
            'icon' => 'fa-circle-exclamation',
            'label' => 'Failed',
        ],
        'refunded' => [
            'color' => 'bg-blue-100 text-blue-800 border-blue-200',
            'icon' => 'fa-rotate-left',
            'label' => 'Refunded',
        ],
    ];

    $status = $statusConfig[$booking->status] ?? $statusConfig['pending'];
    $passengerCount = $booking->passengers->count();
@endphp

<div class="group relative bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-200 hover:border-cyan-200 overflow-hidden">
    
    {{-- Status Badge --}}
    <div class="absolute top-4 right-4 z-10">
        <span class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $status['color'] }} shadow-sm border flex items-center gap-1.5">
            <i class="fa-regular {{ $status['icon'] }}"></i>
            {{ $status['label'] }}
        </span>
    </div>

    <div class="p-6">
        {{-- Header: Booking Reference --}}
        <div class="flex items-start justify-between pr-24">
            <div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-ticket text-cyan-500 text-sm"></i>
                    <span class="text-xs text-gray-400 font-medium">Booking</span>
                </div>
                <h3 class="font-bold text-gray-900 text-lg mt-0.5 font-mono tracking-tight">
                    {{ $booking->booking_reference }}
                </h3>
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="mt-3 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-700 font-bold text-sm border border-cyan-200">
                {{ strtoupper(substr($booking->user->first_name, 0, 1) . substr($booking->user->last_name, 0, 1)) }}
            </div>
            <div>
                <p class="font-semibold text-gray-800 text-sm">{{ $booking->user->full_name }}</p>
                <p class="text-xs text-gray-400">{{ $booking->user->email }}</p>
            </div>
        </div>

        {{-- Divider --}}
        <div class="my-4 border-t border-gray-100"></div>

        {{-- Flight Info --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider">Flight</p>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="font-bold text-gray-800 text-sm">{{ $booking->flight->flight_number }}</span>
                    <span class="text-xs text-gray-400">•</span>
                    <span class="text-sm font-medium text-cyan-600">
                        {{ $booking->flight->origin->code }} → {{ $booking->flight->destination->code }}
                    </span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400 uppercase tracking-wider">Departure</p>
                <p class="font-semibold text-gray-700 text-sm mt-0.5">
                    {{ $booking->flight->departure_date->format('M d, Y') }}
                </p>
                <p class="text-xs text-gray-400">{{ $booking->flight->departure_time }}</p>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="mt-4 flex items-center justify-between bg-cyan-50/50 rounded-xl px-4 py-2.5 border border-cyan-100/50">
            <div class="flex items-center gap-4">
                <div>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider">Seats</p>
                    <p class="font-bold text-gray-800 text-sm">{{ $booking->number_of_seats }}</p>
                </div>
                <div class="w-px h-6 bg-cyan-200"></div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider">Passengers</p>
                    <p class="font-bold text-gray-800 text-sm">{{ $passengerCount }}</p>
                </div>
                <div class="w-px h-6 bg-cyan-200"></div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider">Total</p>
                    <p class="font-bold text-cyan-700 text-sm">${{ number_format($booking->total_price, 2) }}</p>
                </div>
            </div>
            <div>
                <span class="text-xs text-gray-400">
                    <i class="fa-regular fa-calendar mr-1"></i>
                    {{ $booking->booking_date->format('M d') }}
                </span>
            </div>
        </div>

        {{-- Footer: Actions --}}
        <div class="mt-5 pt-4 border-t border-gray-100">
            <div class="flex flex-wrap items-center justify-between gap-2">
                {{-- View Button --}}
                <a href="{{ $url }}" 
                   class="inline-flex items-center gap-1.5 text-sm font-medium text-cyan-600 hover:text-cyan-800 transition group">
                    <i class="fa-regular fa-eye"></i>
                    View Details
                    <i class="fa-solid fa-arrow-right text-xs transition-transform duration-300 group-hover:translate-x-1"></i>
                </a>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-1.5">
                    {{-- Confirm Button (Pending only) --}}
                    @if($booking->status === 'pending')
                        <form action="{{ $confirmUrl }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-all duration-200 hover:shadow-sm"
                                    title="Confirm Booking">
                                <i class="fa-regular fa-check"></i>
                                Confirm
                            </button>
                        </form>
                    @endif

                    {{-- Cancel Button (Not cancelled/completed) --}}
                    @if(!in_array($booking->status, ['cancelled', 'completed']))
                        <form action="{{ $cancelUrl }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-all duration-200 hover:shadow-sm"
                                    title="Cancel Booking"
                                    onclick="return confirm('Cancel this booking?')">
                                <i class="fa-regular fa-xmark"></i>
                                Cancel
                            </button>
                        </form>
                    @endif

                    {{-- Edit Button --}}
                    <a href="{{ $editUrl }}" 
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all duration-200 hover:shadow-sm">
                        <i class="fa-regular fa-pen"></i>
                        Edit
                    </a>

                    {{-- Delete Button --}}
                    <form action="{{ $deleteUrl }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-all duration-200 hover:shadow-sm"
                                title="Delete Booking"
                                onclick="return confirm('Delete this booking?')">
                            <i class="fa-regular fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>