<x-layout title="Dashboard">
    <section class="bg-white rounded-3xl shadow-xl p-12 border border-gray-100">
        <h1 class="text-4xl font-extrabold text-cyan-800 mb-4">
            Welcome, {{ auth()->user()->getFullNameAttribute()}}!
        </h1>

        <p class="text-gray-600 text-lg max-w-2xl">
            Manage airports, aircrafts, flights, and system settings.
        </p>

        <div class="mt-10 grid md:grid-cols-4 gap-8">

            <a href="{{ route('admin.airports.index') }}" class="bg-cyan-50 hover:bg-cyan-100 transition p-6 rounded-xl shadow-md text-center">
                <i class="fa-solid fa-building text-cyan-800 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-800">Airports</h3>
            </a>

            <a href="{{ route('admin.airplanes.index') }}" class="bg-cyan-50 hover:bg-cyan-100 transition p-6 rounded-xl shadow-md text-center">
                <i class="fa-solid fa-plane text-cyan-800 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-800">Aircrafts</h3>
            </a>

            <a href="{{ route('admin.flights.index') }}" class="bg-cyan-50 hover:bg-cyan-100 transition p-6 rounded-xl shadow-md text-center">
                <i class="fa-solid fa-plane-departure text-cyan-800 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-800">Flights</h3>
            </a>

            <a href="{{ route('admin.bookings.index') }}" class="bg-cyan-50 hover:bg-cyan-100 transition p-6 rounded-xl shadow-md text-center">
                <i class="fa-solid fa-plane-departure text-cyan-800 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-800">Bookings</h3>
            </a>
        </div>
    </section>
    <section class="mt-12 bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-cyan-800 flex items-center gap-2">
            <i class="fas fa-clock"></i> Latest Bookings
        </h2>

        <a href="{{ route('admin.bookings.index') }}"
           class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition">
            <i class="fas fa-eye mr-1"></i> View All
        </a>
    </div>

    @if($latestBookings->isEmpty())
        <div class="text-center py-10">
            <i class="fas fa-inbox fa-4x text-gray-300 mb-3"></i>
            <p class="text-gray-500">No bookings found</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-xl overflow-hidden">
                <thead class="bg-gray-50">
                    <tr class="text-left text-gray-700 text-sm">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Reference</th>
                        <th class="px-4 py-3">Passenger</th>
                        <th class="px-4 py-3">Flight</th>
                        <th class="px-4 py-3">Route</th>
                        <th class="px-4 py-3">Price</th>
                        <th class="px-4 py-3">Passengers</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Booked</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @foreach($latestBookings as $index => $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>

                            <td class="px-4 py-3 font-semibold">
                                {{ $booking['booking_reference'] }}
                            </td>

                            <td class="px-4 py-3">
                                <div>{{ $booking['user_name'] }}</div>
                                <div class="text-gray-500 text-sm">{{ $booking['user_email'] }}</div>
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-2 py-1 bg-gray-800 text-white text-xs rounded">
                                    {{ $booking['flight_number'] }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <span class="font-semibold">{{ $booking['origin'] }}</span>
                                <i class="fas fa-arrow-right mx-1 text-gray-400"></i>
                                <span class="font-semibold">{{ $booking['destination'] }}</span>
                            </td>

                            <td class="px-4 py-3 font-semibold">
                                ${{ number_format($booking['total_price'], 2) }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-2 py-1 bg-gray-200 text-gray-700 text-xs rounded">
                                    {{ $booking['passenger_count'] }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                {!! $booking['status'] !!}
                            </td>

                            <td class="px-4 py-3">
                                <div>{{ $booking['booked_at']->format('M d, Y H:i') }}</div>
                                <div class="text-gray-500 text-sm">{{ $booking['booked_ago'] }}</div>
                            </td>

                            <td class="px-4 py-3">
                                <a href="{{ route('admin.bookings.show', $booking['id']) }}"
                                    class="px-3 py-2 border border-cyan-600 text-cyan-600 rounded-lg hover:bg-cyan-600 hover:text-white transition text-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>

</x-layout>