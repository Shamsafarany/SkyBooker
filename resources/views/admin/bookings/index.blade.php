<x-layout title="Bookings" header="Bookings">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <p class="text-gray-500">Manage all flight bookings</p>
        </div>
        <div class="flex gap-3">
            <a href="#" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition text-sm">
                <i class="fa-regular fa-file-excel mr-1"></i> Export
            </a>
        </div>
    </div>

    {{-- Bookings Table --}}
    <div class="bg-white rounded-2xl shadow-md border border-cyan-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-500 uppercase tracking-wider">
                            Booking Ref
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-500 uppercase tracking-wider">
                            Flight
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-500 uppercase tracking-wider">
                            Route
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-500 uppercase tracking-wider">
                            Seats
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-cyan-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr class="border-b border-gray-50 hover:bg-cyan-50/30 transition">
                            {{-- Booking Reference --}}
                            <td class="px-6 py-3 font-mono text-sm text-cyan-600">
                                {{ $booking->booking_reference }}
                            </td>

                            {{-- Customer --}}
                            <td class="px-6 py-3">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $booking->user->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $booking->user->email }}</p>
                                </div>
                            </td>

                            {{-- Flight --}}
                            <td class="px-6 py-3 font-semibold text-gray-900">
                                {{ $booking->flight->flight_number }}
                            </td>

                            {{-- Route --}}
                            <td class="px-6 py-3 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold">{{ $booking->flight->origin->code }}</span>
                                    <i class="fa-solid fa-arrow-right text-gray-300 text-xs"></i>
                                    <span class="font-mono font-bold">{{ $booking->flight->destination->code }}</span>
                                </div>
                                <p class="text-xs text-gray-400">
                                    {{ $booking->flight->departure_date->format('M d, Y') }}
                                </p>
                            </td>

                            {{-- Seats --}}
                            <td class="px-6 py-3 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-cyan-100 text-cyan-700">
                                    <i class="fa-regular fa-chair text-xs"></i>
                                    {{ $booking->number_of_seats }}
                                </span>
                            </td>

                            {{-- Total --}}
                            <td class="px-6 py-3 font-semibold text-cyan-700">
                                ${{ number_format($booking->total_price, 2) }}
                            </td>

                            {{-- Date --}}
                            <td class="px-6 py-3 text-sm text-gray-500">
                                {{ $booking->booking_date->format('M d, Y') }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-3">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $booking->status_color }}">
                                    <i class="fa-regular 
                                        {{ $booking->status === 'confirmed' ? 'fa-circle-check' : '' }}
                                        {{ $booking->status === 'pending' ? 'fa-clock' : '' }}
                                        {{ $booking->status === 'cancelled' ? 'fa-circle-xmark' : '' }}
                                        {{ $booking->status === 'completed' ? 'fa-flag-checkered' : '' }}
                                        mr-1"></i>
                                    {{ $booking->status_label }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- View --}}
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all duration-200"
                                       title="View Booking">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.bookings.edit', $booking->id) }}" 
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-cyan-700 bg-cyan-50 hover:bg-cyan-100 rounded-lg transition-all duration-200"
                                       title="Edit Booking">
                                        <i class="fa-regular fa-pen"></i>
                                    </a>
                                    
                                   
                                    {{-- Delete --}}
                                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-all duration-200"
                                                title="Delete Booking"
                                                onclick="return confirm('Delete this booking?')">
                                            <i class="fa-regular fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <i class="fa-regular fa-calendar-check text-4xl mb-3 block"></i>
                                <p>No bookings found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <span class="text-sm text-gray-500">
                Showing <strong>{{ $bookings->firstItem() ?? 0 }}</strong> to 
                <strong>{{ $bookings->lastItem() ?? 0 }}</strong> of 
                <strong>{{ $bookings->total() }}</strong> bookings
            </span>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Per page:</span>
                <select class="text-sm border-gray-200 rounded-lg focus:border-cyan-500 focus:ring-cyan-500">
                    <option>15</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $bookings->links() }}
        </div>
    </div>
</x-layout>