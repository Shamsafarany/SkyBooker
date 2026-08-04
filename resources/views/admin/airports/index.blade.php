<x-layout title="Airports" header="Airports">
    <div class="mb-5 pb-3 border-b border-gray-200">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-gray-500 text-sm mt-2">
                Manage all airports in the system
            </p>
        </div>
        
        <div class="shrink-0 md:self-center">
            <a href="{{ route('admin.airports.create') }}" 
               class="bg-cyan-800 text-white px-4 py-2.5 rounded-lg hover:bg-cyan-600 transition flex items-center justify-center gap-2 shadow-sm whitespace-nowrap">
                <i class="fa-solid fa-plus"></i>
                Add Airport
            </a>
        </div>
    </div>
    
    <hr class="p-1 mt-4">
    <x-stats 
            title="Airport Overview"
            :stats="[
                [
                    'label' => 'Total Airports',
                    'value' => $stats['total'],
                    'icon' => 'fa-regular fa-building text-cyan-400',
                    'color' => 'text-cyan-700',
                ],
                [
                    'label' => 'Active',
                    'value' => $stats['active'],
                    'icon' => 'fa-regular fa-circle-check text-emerald-400',
                    'color' => 'text-emerald-600',
                ],
                [
                    'label' => 'In Maintenance',
                    'value' => $stats['maintenance'],
                    'icon' => 'fa-regular fa-triangle-exclamation text-amber-400',
                    'color' => 'text-amber-600',
                ],
                [
                    'label' => 'Total Terminals',
                    'value' => $stats['total_terminals'],
                    'icon' => 'fa-regular fa-door-open text-blue-400',
                    'color' => 'text-blue-600',
                ],
            ]"
            :columns="4"
        />
    
</div>

    <section class="bg-white rounded-3xl shadow-lg p-12 border border-gray-100">
        <div class=" grid md:grid-cols-4 gap-8">
        @forelse($airports as $airport)
            <x-cards.airport-card 
                :airport="$airport"
                editUrl="{{ route('admin.airports.edit', $airport) }}"
            />
        @empty
            <div class="col-span-full text-center py-12 text-gray-500">
                <i class="fa-solid fa-building text-4xl mb-3 block"></i>
                <p>No airports found.</p>
            </div>
        @endforelse
        </div>
    </section>
</x-layout>