@props([
    'model',
    'manufacturer',
    'registration',
    'capacity',
    'year',
    'status',
    'url' => '#'
])

@php
    $statusColors = [
        'active' => 'bg-emerald-100/80 text-emerald-700',
        'inactive' => 'bg-rose-100/80 text-rose-700',
        'maintenance' => 'bg-amber-100/80 text-amber-700',
        'retired' => 'bg-gray-100/80 text-gray-700',
    ];
    $statusColor = $statusColors[$status] ?? 'bg-gray-100/80 text-gray-700';
    
    $statusIcon = [
        'active' => 'fa-circle-check pb-2 pt-1',
        'inactive' => 'fa-circle-xmark pb-2 pt-2',
        'maintenance' => 'fa-triangle-exclamation pb-2 pt-2',
        'closed' => 'fa-circle-minus pb-2 pt-2',
    ];
    $statusIconClass = $statusIcon[$status] ?? 'fa-circle';
@endphp

<a href="{{ $url }}" 
   class="group relative block bg-purple-50/80 hover:bg-purple-100/80 backdrop-blur-sm transition-all duration-300 p-6 rounded-2xl shadow-md hover:shadow-2xl border border-purple-200/60 hover:scale-[1.02] active:scale-[0.98]">
    
    {{-- Subtle gradient overlay --}}
    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-purple-200/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
    
    <div class="relative z-10">
        {{-- Header: Icon + Badge --}}
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-2xl bg-white/80 backdrop-blur-sm shadow-sm flex items-center justify-center border border-purple-200/60">
                <i class="fa-solid fa-plane text-purple-800 text-xl"></i>
            </div>
            <span class="px-3 py-1.5 rounded-full text-xs font-semibold tracking-wide bg-purple-100/80 text-purple-800 shadow-sm">
                {{ $registration }}
            </span>
        </div>
        
        {{-- Model --}}
        <h3 class="font-bold text-gray-800 text-lg leading-tight group-hover:text-purple-700 transition-colors duration-200">
            {{ $model }}
        </h3>
        
        {{-- Manufacturer --}}
        <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
            <i class="fa-regular fa-industry mr-1"></i>
            {{ $manufacturer }}
        </p>
        
        {{-- Airplane Details --}}
        <div class="mt-4 space-y-2">
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">
                    <i class="fa-regular fa-users mr-1"></i> Capacity
                </span>
                <span class="font-semibold text-gray-800">{{ $capacity }} seats</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">
                    <i class="fa-regular fa-calendar mr-1"></i> Year
                </span>
                <span class="font-semibold text-gray-800">{{ $year }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">
                    <i class="fa-regular fa-circle-check mr-1"></i> Status
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                    <i class="fa-regular {{ $statusIconClass }} mr-1"></i>
                    {{ ucfirst($status) }}
                </span>
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="mt-5 pt-4 border-t border-purple-200/60 flex items-center justify-between">
            <span class="text-sm font-medium text-purple-800 group-hover:text-purple-800 transition-colors duration-200 flex items-center gap-1">
                View Airplane
                <i class="fa-solid fa-arrow-right text-xs transition-transform duration-300 group-hover:translate-x-1"></i>
            </span>
            <span class="text-xs text-gray-400">
                <i class="fa-regular fa-clock"></i>
                {{ $registration }}
            </span>
        </div>
    </div>
</a>