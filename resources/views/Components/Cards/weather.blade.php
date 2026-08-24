@props([
    'city' => null,
    'weather' => null,
    'loading' => false,
    'error' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-md border border-gray-100 p-5 hover:shadow-lg transition duration-200']) }}>
    @if($loading)
        {{-- Loading State --}}
        <div class="flex items-center justify-center py-4">
            <i class="fa-solid fa-spinner fa-spin text-cyan-600 text-2xl"></i>
            <span class="ml-2 text-gray-500">Loading weather...</span>
        </div>
    @elseif($error)
        {{-- Error State --}}
        <div class="text-center py-2">
            <i class="fa-regular fa-circle-exclamation text-amber-500 text-2xl"></i>
            <p class="text-sm text-gray-500 mt-1">{{ $error }}</p>
        </div>
    @elseif($weather && isset($weather['current']))
        {{-- Weather Data --}}
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-sm font-semibold text-gray-700">{{ $city ?? $weather['location']['name'] ?? 'N/A' }}</h4>
                <div class="flex items-end gap-2 mt-1">
                    <span class="text-3xl font-bold text-gray-900">{{ $weather['current']['temp_c'] ?? 'N/A' }}°C</span>
                    <span class="text-sm text-gray-500 mb-1">{{ $weather['current']['condition']['text'] ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="text-right">
                @if(isset($weather['current']['condition']['icon']))
                    <img src="{{ $weather['current']['condition']['icon'] }}" alt="Weather" class="w-16 h-16 -mt-2">
                @else
                    <i class="fa fa-cloud-sun text-cyan-500 text-4xl"></i>
                @endif
            </div>
        </div>
        
        <div class="flex gap-4 mt-3 pt-3 border-t border-gray-100 text-xs text-gray-500">
            @if(isset($weather['current']['humidity']))
                <span><i class="fa fa-droplet mr-1"></i> {{ $weather['current']['humidity'] }}%</span>
            @endif
            @if(isset($weather['current']['wind_kph']))
                <span><i class="fa fa-wind mr-1"></i> {{ $weather['current']['wind_kph'] }} km/h</span>
            @endif
            @if(isset($weather['current']['feelslike_c']))
                <span><i class="fa fa-temperature-three-quarters mr-1"></i> Feels {{ $weather['current']['feelslike_c'] }}°C</span>
            @endif
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-2">
            <i class="fa fa-cloud-sun text-gray-300 text-2xl"></i>
            <p class="text-sm text-gray-400 mt-1">No weather data available</p>
        </div>
    @endif
</div>