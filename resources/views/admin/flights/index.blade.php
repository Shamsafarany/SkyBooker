<x-layout title="Flights" header="Flights">
    <div class="mb-5 pb-3 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-gray-500 text-sm mt-2">
                    Manage all flights in the system
                </p>
            </div>
            <div class="shrink-0 md:self-center">
                <a href="{{ route('admin.flights.create') }}" 
                    class="bg-cyan-800 text-white px-4 py-2.5 rounded-lg hover:bg-cyan-600 transition flex items-center justify-center gap-2 shadow-sm whitespace-nowrap">
                    <i class="fa-solid fa-plus"></i>
                    Add Flight
                </a>
            </div>
        </div>
        <hr class="p-1 mt-4">
        
        <x-stats 
            title="Flights Overview"
            :stats="[
                [
                    'label' => 'Total Flights',
                    'value' => $stats['total'],
                    'icon' => 'fa-regular fa-calendar-check text-cyan-400',
                    'color' => 'text-cyan-700',
                ],
                [
                    'label' => 'Total Airlines',
                    'value' => $stats['total_airlines'],
                    'icon' => 'fa-regular fa-circle-check text-cyan-400',
                    'color' => 'text-cyan-600',
                ],
                [
                    'label' => 'Open for Booking',
                    'value' => $stats['open'],
                    'icon' => 'fa-regular fa-circle-check text-emerald-400',
                    'color' => 'text-emerald-600',
                ],
                [
                    'label' => 'Almost Full',
                    'value' => $stats['closing'],
                    'icon' => 'fa-regular fa-triangle-exclamation text-amber-400',
                    'color' => 'text-amber-600',
                ],
                [
                    'label' => 'Total Revenue',
                    'value' => '$' . number_format($stats['revenue'], 0),
                    'icon' => 'fa-regular fa-dollar-sign text-cyan-400',
                    'color' => 'text-cyan-600',
                ],
            ]"
            :columns="5"
        />
    </div>

    {{-- Grouped by Airline with Collapsible Sections --}}
    <div class="space-y-4">
        @forelse($airlines as $airline)
            <details class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden" 
                    {{ $loop->first ? 'open' : '' }}>
                
                {{-- Airline Header (Click to expand/collapse) --}}
                <summary class="px-6 py-4 bg-cyan-50/50 border-b border-cyan-100 flex items-center justify-between cursor-pointer hover:bg-cyan-50 transition list-none">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-building text-cyan-600"></i>
                            <span class="font-bold text-cyan-900 text-lg">{{ $airline->name }}</span>
                            <span class="text-sm text-gray-500">({{ $airline->code }})</span>
                        </div>
                        <span class="text-sm text-gray-500">
                            {{ $airline->flights->count() }} flights
                        </span>
                        <span class="text-sm text-gray-500">
                            {{ $airline->flights->sum('booked_seats') }}/{{ $airline->flights->sum('total_seats') }} seats
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="text-emerald-600 font-medium">
                            {{ $airline->flights->where('status', 'open')->count() }} open
                        </span>
                        <span class="text-amber-600 font-medium">
                            {{ $airline->flights->where('status', 'closing')->count() }} closing
                        </span>
                        <span class="text-gray-500">
                            {{ $airline->flights->where('status', 'completed')->count() }} completed
                        </span>
                        {{-- Arrow icon that rotates when open --}}
                        <i class="fa-solid fa-chevron-down text-gray-400 ml-2 transition-transform duration-200"></i>
                    </div>
                </summary>

                {{-- Flight Cards Grid --}}
                <div class="p-6">
                    @if($airline->flights->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($airline->flights as $flight)
                                <x-cards.flights-card 
                                    :flight="$flight"
                                    :url="route('admin.flights.show', $flight->id)"
                                />
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <i class="fa-regular fa-calendar-check text-2xl mb-2 block"></i>
                            <p>No flights for this airline yet.</p>
                        </div>
                    @endif
                </div>
            </details>
        @empty
            <div class="text-center py-12 text-gray-500">
                <i class="fa-solid fa-building text-4xl mb-3 block"></i>
                <p>No airlines found.</p>
            </div>
        @endforelse
    </div>
</x-layout>

<script>
    document.querySelectorAll('details').forEach((details) => {
        details.addEventListener('toggle', function() {
            const arrow = this.querySelector('.fa-chevron-down');
            if (arrow) {
                arrow.style.transform = this.open ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        });
    });
</script>