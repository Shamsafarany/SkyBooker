<x-layout title="Flights" header="Flights">
    <div class="mb-5 pb-3 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-gray-500 text-sm mt-2">
                    Manage all flights in the system
                </p>
            </div>
            <div class="flex-shrink-0 md:self-center">
                <a href="{{ route('admin.flights.create') }}" 
                    class="bg-purple-800 text-white px-4 py-2.5 rounded-lg hover:bg-purple-900 transition flex items-center justify-center gap-2 shadow-sm whitespace-nowrap">
                    <i class="fa-solid fa-plus"></i>
                    Add Flight
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Summary --}}
    <section class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-sm text-gray-500">Total Flights</p>
            <p class="text-2xl font-bold text-gray-900">{{ count($flights) }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-sm text-gray-500">Open for Booking</p>
            <p class="text-2xl font-bold text-emerald-600">
                {{ collect($flights)->where('status', 'open')->count() }}
            </p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-sm text-gray-500">Almost Full</p>
            <p class="text-2xl font-bold text-amber-600">
                {{ collect($flights)->where('status', 'closing')->count() }}
            </p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-sm text-gray-500">Completed</p>
            <p class="text-2xl font-bold text-gray-600">
                {{ collect($flights)->where('status', 'completed')->count() }}
            </p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-sm text-gray-500">Total Revenue</p>
            <p class="text-2xl font-bold text-purple-600">
                ${{ number_format(collect($flights)->sum('price'), 2) }}
            </p>
        </div>
    </section>

    <section class="bg-white rounded-3xl shadow-xl p-12 border border-purple-100">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($flights as $flight)
                <x-cards.flights-card 
                    :flight_number="$flight['flight_number']"
                    :airline="$flight['airline']"
                    :origin="$flight['origin']"
                    :origin_city="$flight['origin_city']"
                    :destination="$flight['destination']"
                    :destination_city="$flight['destination_city']"
                    :departure_date="$flight['departure_date']"
                    :departure_time="$flight['departure_time']"
                    :arrival_time="$flight['arrival_time']"
                    :duration="$flight['duration']"
                    :airplane="$flight['airplane']"
                    :price="$flight['price']"
                    :total_seats="$flight['total_seats']"
                    :booked_seats="$flight['booked_seats']"
                    :available_seats="$flight['available_seats']"
                    :status="$flight['status']"
                    :booking_deadline="$flight['booking_deadline'] ?? null"
                    :url="route('admin.flights.show', $flight['id'])"
                />
                
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    <i class="fa-solid fa-calendar-check text-4xl mb-3 block"></i>
                    <p>No flights found.</p>
                    <a href="{{ route('admin.flights.create') }}" class="text-purple-600 hover:text-purple-800 mt-2 inline-block">
                        Create your first flight →
                    </a>
                </div>
            @endforelse
        </div>
    </section>
</x-layout>