@props(['title' => 'Overview', 'stats' => [], 'columns' => 4])

@php
    $colClasses = [
        2 => 'md:grid-cols-2',
        3 => 'md:grid-cols-3',
        4 => 'md:grid-cols-4',
        5 => 'md:grid-cols-5',
        6 => 'md:grid-cols-6',
    ][$columns] ?? 'md:grid-cols-4';
@endphp

@if(count($stats) > 0)
    <section>
        @if($title)
            <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $title }}</h2>
        @endif
        
        <div {{ $attributes->merge(['class' => "grid grid-cols-1 sm:grid-cols-2 {$colClasses} gap-4"]) }}>
            @foreach($stats as $stat)
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                    <p class="text-sm text-gray-500 flex items-center gap-1.5">
                        @if(isset($stat['icon']))
                            <i class="{{ $stat['icon'] }}"></i>
                        @endif
                        {{ $stat['label'] }}
                    </p>
                    <p class="text-2xl font-bold {{ $stat['color'] ?? 'text-gray-900' }}">
                        {{ $stat['value'] }}
                    </p>
                    @if(isset($stat['subtext']))
                        <p class="text-xs text-gray-400 mt-1">{{ $stat['subtext'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
@endif