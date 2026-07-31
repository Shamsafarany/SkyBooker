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
                    class="bg-cyan-800 text-white px-4 py-2.5 rounded-lg hover:bg-cyan-900 transition flex items-center justify-center gap-2 shadow-sm whitespace-nowrap">
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
                'value' => count($flights),
                'icon' => 'fa-regular fa-calendar-check text-cyan-400',
                'color' => 'text-cyan-700',
            ],
            [
                'label' => 'Open for Booking',
                'value' => collect($flights)->where('status', 'open')->count(),
                'icon' => 'fa-regular fa-circle-check text-emerald-400',
                'color' => 'text-emerald-600',
            ],
            [
                'label' => 'Almost Full',
                'value' => collect($flights)->where('status', 'closing')->count(),
                'icon' => 'fa-regular fa-triangle-exclamation text-amber-400',
                'color' => 'text-amber-600',
            ],
            [
                'label' => 'Completed',
                'value' => collect($flights)->where('status', 'completed')->count(),
                'icon' => 'fa-regular fa-triangle-exclamation text-blue-400',
                'color' => 'text-blue-600',
            ],
            [
                'label' => 'Total Revenue',
                'value' => '$' . number_format(collect($flights)->sum('price'), 0),
                'icon' => 'fa-regular fa-dollar-sign text-cyan-400',
                'color' => 'text-cyan-600',
            ],
        ]"
        :columns="5"
    />
    </div>
    

    <section class="bg-white rounded-3xl shadow-xl p-12 border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
            @forelse($flights as $flight)
                <x-cards.flights-card 
                    :flight="$flight"
                    :url="route('admin.flights.show', $flight['id'])"
                />
                
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    <i class="fa-solid fa-calendar-check text-4xl mb-3 block"></i>
                    <p>No flights found.</p>
                    <a href="{{ route('admin.flights.create') }}" class="text-cyan-600 hover:text-cyan-800 mt-2 inline-block">
                        Create your first flight →
                    </a>
                </div>
            @endforelse
        </div>
    </section>
</x-layout>