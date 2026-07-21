<x-layout>
    <x-slot:title>
        Airplanes
    </x-slot:title>
    
    <div class="mb-5 pb-3 border-b border-gray-200">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <x-slot:header>
                Airplanes
            </x-slot:header>
            <p class="text-gray-500 text-sm mt-2">
                Manage all airplanes in the system
            </p>
        </div>
        <div class="flex-shrink-0 md:self-center">
            <a href="{{ route('admin.airports.create') }}" 
            class="bg-purple-800 text-white px-4 py-2.5 rounded-lg hover:bg-purple-900 transition flex items-center justify-center gap-2 shadow-sm whitespace-nowrap">
                <i class="fa-solid fa-plus"></i>
                Add Airplane
            </a>
        </div>
    </div>
</div>

    <section class="bg-white rounded-3xl shadow-xl p-12 border border-blue-100">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($airplanes as $airplane)
                <x-cards.airplane-card 
                    :model="$airplane['model']"
                    :manufacturer="$airplane['manufacturer']"
                    :registration="$airplane['registration']"
                    :capacity="$airplane['capacity']"
                    :year="$airplane['year']"
                    :status="$airplane['status']"
                    :url="route('admin.airplanes.show', $airplane['id'])"
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