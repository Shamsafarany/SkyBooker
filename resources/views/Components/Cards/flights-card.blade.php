@props([
    'flight_number',
    'airline',
    'origin',
    'origin_city',
    'destination',
    'destination_city',
    'departure_time',
    'arrival_time',
    'duration',
    'airplane',
    'price',
    'status',
    'available_seats',
    'url' => '#'
])

@php
    $statusColors = [
        'on_time' => 'bg-emerald-100/80 text-emerald-700',
        'boarding' => 'bg-blue-100/80 text-blue-700',
        'departed' => 'bg-indigo-100/80 text-indigo-700',
        'delayed' => 'bg-amber-100/80 text-amber-700',
        'cancelled' => 'bg-rose-100/80 text-rose-700',
        'arrived' => 'bg-teal-100/80 text-teal-700',
    ];
    $statusColor = $statusColors[$status] ?? 'bg-gray-100/80 text-gray-700';
    
    $statusIcon = [
        'on_time' => 'fa-circle-check',
        'boarding' => 'fa-person-walking',
        'departed' => 'fa-plane-departure',
        'delayed' => 'fa-clock',
        'cancelled' => 'fa-circle-xmark',
        'arrived' => 'fa-plane-arrival',
    ];
    $statusIconClass = $statusIcon[$status] ?? 'fa-circle';
@endphp

<a href="{{ $url }}" 
   class="group relative block bg-purple-50/80 hover:bg-purple-100/80 backdrop-blur-sm transition-all duration-300 p-6 rounded-2xl shadow-md hover:shadow-2xl border border-purple-200/60 hover:scale-[1.02] active:scale-[0.98]">
    
    {{-- Subtle gradient overlay --}}
    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-purple-200/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
    
    <div class="relative z-10">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-2xl bg-white/80 backdrop-blur-sm shadow-sm flex items-center justify-center border border-purple-200/60">
                <i class="fa-solid fa-calendar-check text-purple-800 text-xl"></i>
            </div>
            <span class="px-3 py-1.5 rounded-full text-xs font-semibold tracking-wide {{ $statusColor }} shadow-sm">
                <i class="fa-regular {{ $statusIconClass }} mr-1"></i>
                {{ ucfirst(str_replace('_', ' ', $status)) }}
            </span>
        </div>
        
        <h3 class="font-bold text-gray-800 text-lg leading-tight group-hover:text-purple-700 transition-colors duration-200">
            {{ $flight_number }}
        </h3>
        <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
            <i class="fa-regular fa-building mr-1"></i>
            {{ $airline }}
        </p>
        
        <div class="mt-3 p-3 bg-white/60 rounded-xl border border-purple-200/40">
            <div class="flex items-center justify-between">
                <div class="text-center">
                    <p class="font-bold text-gray-800">{{ $origin }}</p>
                    <p class="text-xs text-gray-500">{{ $origin_city }}</p>
                </div>
                <div class="flex-1 mx-2">
                    <div class="relative">
                        <div class="border-t-2 border-purple-300 border-dashed"></div>
                        <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 bg-white px-1 text-xs text-purple-800">
                            <i class="fa-solid fa-plane"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 text-center mt-1">{{ $duration }}</p>
                </div>
                <div class="text-center">
                    <p class="font-bold text-gray-800">{{ $destination }}</p>
                    <p class="text-xs text-gray-500">{{ $destination_city }}</p>
                </div>
            </div>
        </div>
        
        <div class="mt-4 space-y-2">
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">
                    <i class="fa-regular fa-clock mr-1"></i> Departure
                </span>
                <span class="font-semibold text-gray-800">{{ $departure_time }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">
                    <i class="fa-regular fa-clock mr-1"></i> Arrival
                </span>
                <span class="font-semibold text-gray-800">{{ $arrival_time }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">
                    <i class="fa-regular fa-plane mr-1"></i> Aircraft
                </span>
                <span class="font-semibold text-gray-800">{{ $airplane }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">
                    <i class="fa-regular fa-ticket mr-1"></i> Available
                </span>
                <span class="font-semibold text-gray-800">{{ $available_seats }} seats</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">
                    <i class="fa-regular fa-dollar-sign mr-1"></i> Price
                </span>
                <span class="font-bold text-purple-700 text-base">${{ number_format($price, 2) }}</span>
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="mt-5 pt-4 border-t border-purple-200/60 flex items-center justify-between">
            <span class="text-sm font-medium text-purple-800 group-hover:text-purple-800 transition-colors duration-200 flex items-center gap-1">
                View Flight
                <i class="fa-solid fa-arrow-right text-xs transition-transform duration-300 group-hover:translate-x-1"></i>
            </span>
            <span class="text-xs text-gray-400">
                <i class="fa-regular fa-clock"></i>
                {{ $flight_number }}
            </span>
        </div>
    </div>
</a>