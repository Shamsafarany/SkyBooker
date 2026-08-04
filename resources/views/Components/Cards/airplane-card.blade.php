@props([
    'airplane',
    'url' => '#',
    'editUrl' => '#',
    'deleteUrl' => '#',
])

@php
    // Extract data from the airplane object
    $model = $airplane->model;
    $manufacturer = $airplane->manufacturer;
    $registration = $airplane->registration;
    $capacity = $airplane->capacity;
    $year = $airplane->year;
    $status = $airplane->status;
    $id = $airplane->id;

    $statusColors = [
        'active' => 'bg-emerald-100/80 text-emerald-700',
        'inactive' => 'bg-rose-100/80 text-rose-700',
        'maintenance' => 'bg-amber-100/80 text-amber-700',
        'retired' => 'bg-gray-100/80 text-gray-700',
    ];
    $statusColor = $statusColors[$status] ?? 'bg-gray-100/80 text-gray-700';
    
    $statusIcon = [
        'active' => 'fa-circle-check',
        'inactive' => 'fa-circle-xmark',
        'maintenance' => 'fa-triangle-exclamation',
        'retired' => 'fa-circle-minus',
    ];
    $statusIconClass = $statusIcon[$status] ?? 'fa-circle';
@endphp

<div class="group relative block bg-gray-50/80 hover:bg-gray-50/80 backdrop-blur-sm transition-all duration-300 p-6 rounded-2xl shadow-md hover:shadow-2xl border border-gray-200/60 hover:scale-[1.02] active:scale-[0.98]">
    
    {{-- Subtle gradient overlay --}}
    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-cyan-200/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
    
    <div class="relative z-10">
        {{-- Header: Icon + Badge --}}
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-2xl bg-white/80 backdrop-blur-sm shadow-sm flex items-center justify-center border border-cyan-200/60">
                <i class="fa-solid fa-plane text-cyan-600 text-xl"></i>
            </div>
            <span class="px-3 py-1.5 rounded-full text-xs font-semibold tracking-wide bg-cyan-100/80 text-cyan-700 shadow-sm">
                {{ $registration }}
            </span>
        </div>
        
        {{-- Model --}}
        <h3 class="font-bold text-gray-800 text-lg leading-tight group-hover:text-cyan-700 transition-colors duration-200">
            {{ $model }}
        </h3>
        
        {{-- Manufacturer --}}
        <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
            <i class="fa-regular fa-building mr-1"></i>
            {{ $manufacturer }}
        </p>
        
        {{-- Airplane Details --}}
        <div class="mt-4 space-y-2.5">
            {{-- Capacity --}}
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500 flex items-center gap-1.5">
                    <i class="fa-regular fa-users text-cyan-400"></i>
                    Capacity
                </span>
                <span class="font-semibold text-gray-800">{{ $capacity }} seats</span>
            </div>
            
            {{-- Year --}}
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500 flex items-center gap-1.5">
                    <i class="fa-regular fa-calendar text-cyan-400"></i>
                    Year
                </span>
                <span class="font-semibold text-gray-800">{{ $year }}</span>
            </div>
            
            {{-- Status --}}
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500 flex items-center gap-1.5">
                    <i class="fa-regular fa-circle-check text-cyan-400"></i>
                    Status
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                    <i class="fa-regular {{ $statusIconClass }} mr-1"></i>
                    {{ ucfirst($status) }}
                </span>
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="mt-5 pt-4 border-t border-cyan-200/60">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                {{-- Right Side: Registration + Actions --}}
                <div class="flex items-center gap-3">
                    {{-- Registration Span --}}
                    <span class="text-xs text-gray-400 flex items-center gap-1">
                        {{ $registration }}
                    </span>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-2">
                        {{-- Edit Button --}}
                        <a href="{{ $editUrl }}" 
                           class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all duration-200 hover:shadow-sm">
                            <i class="fa-regular fa-pen text-xs"></i>
                            Edit
                        </a>
                        
                        {{-- Delete Button --}}
                        <button onclick="confirmDelete({{ $id }})" 
                                class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-all duration-200 hover:shadow-sm">
                            <i class="fa-regular fa-trash text-xs"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Script --}}
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this airplane?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>