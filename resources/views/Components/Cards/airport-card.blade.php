@props([
    'name',
    'code',
    'city',
    'country',
    'terminals',
    'status',
    'url' => '#',
    'editUrl' => '#',
    'deleteUrl' => '#',
    'id' => 0
])

@php
    $statusColors = [
        'active' => 'bg-emerald-100/80 text-emerald-700',
        'inactive' => 'bg-rose-100/80 text-rose-700',
        'maintenance' => 'bg-amber-100/80 text-amber-700',
        'closed' => 'bg-gray-100/80 text-gray-700',
    ];
    $statusColor = $statusColors[$status] ?? 'bg-gray-100/80 text-gray-700';
    
    $statusIcon = [
        'active' => 'fa-circle-check',
        'inactive' => 'fa-circle-xmark',
        'maintenance' => 'fa-triangle-exclamation',
        'closed' => 'fa-circle-minus',
    ];
    $statusIconClass = $statusIcon[$status] ?? 'fa-circle';
@endphp

<div class="group relative block bg-purple-50/80 hover:bg-purple-100/80 backdrop-blur-sm transition-all duration-300 p-6 rounded-2xl shadow-md hover:shadow-2xl border border-purple-200/60 hover:scale-[1.02] active:scale-[0.98]">
    
    {{-- Subtle gradient overlay --}}
    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-purple-200/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
    
    <div class="relative z-10">
        {{-- Header: Icon + Badge --}}
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-2xl bg-white/80 backdrop-blur-sm shadow-sm flex items-center justify-center border border-purple-200/60">
                <i class="fa-solid fa-building text-purple-800 text-xl"></i>
            </div>
            <span class="px-3 py-1.5 rounded-full text-xs font-semibold tracking-wide bg-purple-100/80 text-purple-700 shadow-sm">
                {{ $code }}
            </span>
        </div>
        
        {{-- Airport Name --}}
        <h3 class="font-bold text-gray-800 text-lg leading-tight group-hover:text-purple-700 transition-colors duration-200">
            {{ $name }}
        </h3>
        
        {{-- Location --}}
        <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
            <i class="fa-regular fa-location-dot mr-1"></i>
            {{ $city }}, {{ $country }}
        </p>
        
        {{-- Airport Details --}}
        <div class="mt-4 space-y-2.5">
            {{-- Terminals --}}
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500 flex items-center gap-1.5">
                    <i class="fa-regular fa-door-open text-purple-400"></i>
                    Terminals
                </span>
                <span class="font-semibold text-gray-800">{{ $terminals }}</span>
            </div>
            
            {{-- Status --}}
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500 flex items-center gap-1.5">
                    <i class="fa-regular fa-circle-check text-purple-400"></i>
                    Status
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                    <i class="fa-regular {{ $statusIconClass }} mr-1"></i>
                    {{ ucfirst($status) }}
                </span>
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="mt-5 pt-4 border-t border-purple-200/60">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                {{-- Action Buttons --}}
                <div class="flex items-center gap-2">
                    {{-- Edit Button --}}
                    <a href="#" 
                       class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all duration-200 hover:shadow-sm">
                        <i class="fa-regular fa-pen text-xs"></i>
                        Edit
                    </a>
                    
                    {{-- Delete Button --}}
                    <button 
                            class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-all duration-200 hover:shadow-sm">
                        <i class="fa-regular fa-trash text-xs"></i>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

