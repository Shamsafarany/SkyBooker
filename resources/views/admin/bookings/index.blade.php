<x-layout title="Bookings" header="Bookings">
    <div class="flex justify-between items-center mb-6">
        <div>
            <p class="text-gray-700">Manage all flight bookings</p>
        </div>
        <div class="flex gap-3">
            <a href="#" class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700 transition text-sm">
                <i class="fa-regular fa-file-excel mr-1"></i> Export
            </a>
            <a href="{{ route('admin.bookings.create') }}" class="bg-cyan-800 text-white px-4 py-2 rounded-lg hover:bg-cyan-600 transition text-sm">
                <i class="fa-regular fa-plus mr-1"></i> Add Booking
            </a>
        </div>
    </div>
    <hr class="p-1 mt-4">
    <x-stats 
        title="Booking Overview"
        :stats="[
            [
                'label' => 'Total Bookings',
                'value' => $stats['total'],
                'icon' => 'fa-regular fa-calendar-check text-cyan-400',
                'color' => 'text-cyan-700',
            ],
            [
                'label' => 'Confirmed',
                'value' => $stats['confirmed'],
                'icon' => 'fa-regular fa-circle-check text-emerald-400',
                'color' => 'text-emerald-600',
            ],
            [
                'label' => 'Pending',
                'value' => $stats['pending'],
                'icon' => 'fa-regular fa-clock text-yellow-400',
                'color' => 'text-yellow-600',
            ],
            [
                'label' => 'Revenue',
                'value' => '$' . number_format($stats['total_revenue'], 0),
                'icon' => 'fa-regular fa-dollar-sign text-cyan-400',
                'color' => 'text-cyan-600',
            ],
        ]"
        :columns="4"
        class="mb-8"
    />
    <hr class="p-1 mb-3">
    {{-- Bookings Grouped by Flight (Collapsible) --}}
    <div class="space-y-4">
        @forelse($flights as $flight)
            <details class="bg-white rounded-2xl shadow-md border border-cyan-100 overflow-hidden" 
                     {{ $loop->first ? 'open' : '' }}>
                
                {{-- Flight Header (Click to expand/collapse) --}}
                <summary class="px-6 py-4 bg-cyan-50/50 border-b border-cyan-100 flex items-center justify-between cursor-pointer hover:bg-cyan-50 transition list-none">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-plane text-cyan-600"></i>
                            <span class="font-bold text-cyan-900">{{ $flight->flight_number }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <span class="font-medium">{{ $flight->origin->code }}</span>
                            <i class="fa-solid fa-arrow-right text-gray-400 text-xs"></i>
                            <span class="font-medium">{{ $flight->destination->code }}</span>
                            <span class="text-gray-400">•</span>
                            <span class="text-gray-700">{{ $flight->origin->city }} → {{ $flight->destination->city }}</span>
                        </div>
                        <span class="text-gray-400">•</span>
                        <div class="text-sm text-gray-700">
                            
                            <i class="fa-regular fa-calendar mr-1"></i>
                            {{ $flight->departure_date->format('M d, Y') }}
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="text-gray-700">
                            {{ $flight->bookings->count() }} bookings
                        </span>
                        <span class="text-cyan-600 font-semibold">
                            {{ $flight->booked_seats }}/{{ $flight->total_seats }} seats
                        </span>
                        <a href="{{ route('admin.flights.show', $flight->id) }}" 
                           class="text-cyan-600 hover:text-cyan-800 text-sm" 
                           title="View Flight Details"
                           onclick="event.stopPropagation();">
                            <i class="fa-regular fa-eye"></i>
                        </a>
                        <i class="fa-solid fa-chevron-down text-gray-400 ml-2 transition-transform duration-200"></i>
                    </div>
                </summary>

                {{-- Bookings Table --}}
                <div class="p-6">
                    @if($flight->bookings->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Booking Ref</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Seats</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Booking Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-cyan-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($flight->bookings as $booking)
                                        <tr class="border-b border-gray-50 hover:bg-cyan-50/30 transition">
                                            <td class="px-6 py-3 font-mono text-sm text-cyan-600">
                                                {{ $booking->booking_reference }}
                                            </td>
                                            <td class="px-6 py-3">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $booking->user->full_name }}</p>
                                                    <p class="text-xs text-gray-700">{{ $booking->user->email }}</p>
                                                </div>
                                            </td>
                                            <td class="px-6 py-3 text-center">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-cyan-100 text-cyan-700">
                                                    {{ $booking->number_of_seats }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 font-semibold text-cyan-700">
                                                ${{ number_format($booking->total_price, 2) }}
                                            </td>
                                            <td class="px-6 py-3 text-sm text-gray-700">
                                                {{ $booking->booking_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-3">
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $booking->status_color }}">
                                                    <i class="fa-regular 
                                                        {{ $booking->status === 'confirmed' ? 'fa-circle-check' : '' }}
                                                        {{ $booking->status === 'pending' ? 'fa-clock' : '' }}
                                                        {{ $booking->status === 'cancelled' ? 'fa-circle-xmark' : '' }}
                                                        {{ $booking->status === 'completed' ? 'fa-flag-checkered' : '' }}
                                                        mr-1"></i>
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 text-right">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    {{-- View --}}
                                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all duration-200"
                                                       title="View Booking">
                                                        <i class="fa-regular fa-eye"></i>
                                                        View
                                                    </a>
                                                    
                                                    {{-- Edit --}}
                                                    <a href="{{ route('admin.bookings.edit', $booking->id) }}" 
                                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-cyan-700 bg-cyan-50 hover:bg-cyan-100 rounded-lg transition-all duration-200"
                                                       title="Edit Booking">
                                                        Edit
                                                    </a>
                                                    
                                                    {{-- Delete --}}
                                                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-all duration-200"
                                                                title="Delete Booking"
                                                                onclick="return confirm('Delete this booking?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <i class="fa-regular fa-calendar-check text-2xl mb-2 block"></i>
                            <p>No bookings for this flight yet.</p>
                        </div>
                    @endif
                </div>
            </details>
        @empty
            <div class="text-center py-12 text-gray-700">
                <i class="fa-regular fa-calendar-check text-4xl mb-3 block"></i>
                <p>No flights found.</p>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="mt-6">
        {{ $flights->links() }}
    </div>

    {{-- Pagination Info --}}
    <div class="mt-4 text-center text-sm text-gray-700">
        Showing {{ $flights->firstItem() ?? 0 }} to {{ $flights->lastItem() ?? 0 }} of {{ $flights->total() }} flights
    </div>
</x-layout>

<script>
    //Rotate arrow when details opens/closes
    document.querySelectorAll('details').forEach((details) => {
        details.addEventListener('toggle', function() {
            const arrow = this.querySelector('.fa-chevron-down');
            if (arrow) {
                arrow.style.transform = this.open ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        });
    });
</script>