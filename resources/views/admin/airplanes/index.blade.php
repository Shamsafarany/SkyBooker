<x-layout title="Airplanes" header="Airplanes">
    <div class="mb-5 pb-3 border-b border-gray-200">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-gray-500 text-sm mt-2">
                Manage all airplanes in the system
            </p>
        </div>
        <div class="shrink-0 md:self-center">
            <a href="{{ route('admin.airplanes.create') }}" 
            class="bg-cyan-800 text-white px-4 py-2.5 rounded-lg hover:bg-cyan-600 transition flex items-center justify-center gap-2 shadow-sm whitespace-nowrap">
                <i class="fa-solid fa-plus"></i>
                Add Airplane
            </a>
        </div>
    </div>
    <hr class="p-1 mt-4">
    {{-- Airplane Specific Stats --}}
    <x-stats 
        title="Fleet Overview"
        :stats="[
                [
                    'label' => 'Total Aircraft',
                    'value' => $stats['total'],
                    'icon' => 'fa fa-plane text-cyan-400',
                    'color' => 'text-cyan-700',
                ],
                [
                    'label' => 'Active',
                    'value' => $stats['active'],
                    'icon' => 'fa fa-circle-check text-emerald-400',
                    'color' => 'text-emerald-600',
                ],
                [
                    'label' => 'In Maintenance',
                    'value' => $stats['maintenance'],
                    'icon' => 'fa fa-triangle-exclamation text-amber-400',
                    'color' => 'text-amber-600',
                ],
                [
                    'label' => 'Total Capacity',
                    'value' => $stats['total_capacity'] . ' seats',
                    'icon' => 'fa fa-users text-blue-400',
                    'color' => 'text-blue-600',
                ],
                [
                    'label' => 'Total Flights',
                    'value' => $stats['total_flights'],
                    'icon' => 'fa fa-calendar-check text-cyan-400',
                    'color' => 'text-cyan-600',
                ],
            ]"
            :columns="5"
        />
</div>

    <section class="bg-white rounded-3xl shadow-xl p-12 border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($airplanes as $airplane)
                <x-cards.airplane-card 
                    :airplane="$airplane"
                    editUrl="{{ route('admin.airplanes.edit', $airplane) }}"
                    deleteUrl="{{ route('admin.airplanes.destroy', $airplane) }}"
                    
                />
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    <i class="fa-solid fa-plane text-4xl mb-3 block"></i>
                    <p>No airplanes found.</p>
                </div>
            @endforelse
        </div>
    </section>
</x-layout>