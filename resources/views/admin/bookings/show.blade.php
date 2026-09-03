<x-layout title="Booking {{ $booking->booking_reference }}" header="Booking Details">
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bookings.index') }}" 
                class="text-cyan-600 hover:text-cyan-800 transition group flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="text-sm font-medium group-hover:underline">Back to Bookings</span>
            </a>
        </div>
    </div>

    {{-- Booking Header --}}
    <div class="bg-white rounded-2xl shadow-xl border  overflow-hidden mb-6">
        <div class="px-6 py-5 bg-gradient from-cyan-50 to-cyan-100/50 border-b flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-ticket text-cyan-600 text-xl"></i>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $booking->booking_reference }}</h1>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $booking->status_color }}">
                        <i class="fa-regular 
                            {{ $booking->status === 'confirmed' ? 'fa-circle-check' : '' }}
                            {{ $booking->status === 'pending' ? 'fa-clock' : '' }}
                            {{ $booking->status === 'cancelled' ? 'fa-circle-xmark' : '' }}
                            {{ $booking->status === 'completed' ? 'fa-flag-checkered' : '' }}
                            {{ $booking->status === 'failed' ? 'fa-circle-exclamation' : '' }}
                            {{ $booking->status === 'refunded' ? 'fa-rotate-left' : '' }}
                            mr-1"></i>
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                <p class="text-gray-500 text-sm mt-1 ml-1">
                    Booked on {{ $booking->booking_date->format('M d, Y h:i A') }}
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.bookings.edit', $booking) }}" 
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all duration-200">
                    Edit
                </a>
                <form action="#" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-all duration-200"
                            onclick="return confirm('Delete this booking?')">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Booking Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider">Seats</p>
                <p class="text-xl font-bold text-gray-900">{{ $booking->number_of_seats }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider">Total Price</p>
                <p class="text-xl font-bold text-cyan-700">${{ number_format($booking->total_price, 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider">Passengers</p>
                <p class="text-xl font-bold text-gray-900">{{ $booking->passengers->count() }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider">Flight</p>
                <p class="text-xl font-bold text-cyan-700">{{ $booking->flight->flight_number }}</p>
            </div>
        </div>
    </div>

    {{-- Two Column Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Customer & Flight Details --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Customer Info --}}
            <div class="bg-white rounded-2xl shadow-xl border overflow-hidden">
                <div class="px-6 py-4 bg-cyan-50/50 border-b border-cyan-100">
                    <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fa-regular fa-user text-cyan-600"></i>
                        Customer Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-700 text-xl font-bold border border-cyan-200">
                            {{ strtoupper(substr($booking->user->first_name, 0, 1) . substr($booking->user->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-lg">{{ $booking->user->full_name }}</p>
                            <p class="text-gray-500">
                                <i class="fa-regular fa-envelope mr-1"></i>
                                {{ $booking->user->email }}
                            </p>
                            @if($booking->user->phone)
                                <p class="text-gray-500">
                                    {{ $booking->user->phone }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Flight Details --}}
            <div class="bg-white rounded-2xl shadow-xl border overflow-hidden">
                <div class="px-6 py-4 bg-cyan-50/50 border-b">
                    <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-plane text-cyan-600"></i>
                        Flight Details
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $booking->flight->origin->code }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->flight->origin->city }}</p>
                            <p class="text-xs text-gray-400">{{ $booking->flight->origin->name }}</p>
                            <p class="text-sm font-semibold text-cyan-700 mt-1">
                                <i class="fa-regular fa-clock mr-1"></i>
                                {{ $booking->flight->departure_time }}
                            </p>
                        </div>
                        <div class="flex-1 mx-4">
                            <div class="relative">
                                <div class="border-t-2 border-gray-400 border-dashed"></div>
                                <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 bg-white px-2">
                                    <i class="fa-solid fa-plane text-cyan-600"></i>
                                </div>
                                <p class="text-center text-xs text-gray-400 mt-5">{{ $booking->flight->duration }}</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $booking->flight->destination->code }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->flight->destination->city }}</p>
                            <p class="text-xs text-gray-400">{{ $booking->flight->destination->name }}</p>
                            <p class="text-sm font-semibold text-cyan-700 mt-1">
                                <i class="fa-regular fa-clock mr-1"></i>
                                {{ $booking->flight->arrival_time }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Airline</p>
                            <p class="font-semibold">{{ $booking->flight->airline->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Aircraft</p>
                            <p class="font-semibold">{{ $booking->flight->airplane->model }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Departure</p>
                            <p class="font-semibold">{{ $booking->flight->departure_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            
            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl shadow-md border border-cyan-100 overflow-hidden">
                <div class="px-6 py-4 bg-cyan-50/50 border-b border-cyan-100">
                    <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fa fa-bolt text-cyan-600"></i>
                        Quick Actions
                    </h2>
                </div>
                <div class="p-4 space-y-2">
                    @if($booking->status === 'pending')
                        <form action="#" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-emerald-600 text-white px-4 py-2.5 rounded-lg hover:bg-emerald-700 transition text-sm font-medium">
                                <i class="fa fa-check mr-1"></i>
                                Confirm Booking
                            </button>
                        </form>
                    @endif

                    @if($booking->status !== 'cancelled' && $booking->status !== 'completed')
                        <form action="#" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-rose-600 text-white px-4 py-2.5 rounded-lg hover:bg-rose-700 transition text-sm font-medium"
                                    onclick="return confirm('Cancel this booking?')">
                                <i class="fa fa-xmark mr-1"></i>
                                Cancel Booking
                            </button>
                        </form>
                    @endif

                    @if($booking->status === 'cancelled' || $booking->status === 'failed')
                        <button class="w-full bg-amber-600 text-white px-4 py-2.5 rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                            <i class="fa-regular fa-rotate-left mr-1"></i>
                            Process Refund
                        </button>
                    @endif
                </div>
            </div>

            {{-- Booking Summary --}}
            <div class="bg-white rounded-2xl shadow-xl border overflow-hidden">
                <div class="px-6 py-4 bg-cyan-50/50 border-b">
                    <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                        Booking Summary
                    </h2>
                </div>
                <div class="p-4 space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Reference</span>
                        <span class="font-mono font-semibold text-cyan-700">{{ $booking->booking_reference }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Status</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $booking->status_color }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Booked On</span>
                        <span class="font-medium">{{ $booking->booking_date->format('M d, Y h:i A') }}</span>
                    </div>
                    @if($booking->confirmed_at)
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">Confirmed On</span>
                            <span class="font-medium">{{ $booking->confirmed_at->format('M d, Y h:i A') }}</span>
                        </div>
                    @endif
                    @if($booking->cancelled_at)
                        <div class="flex justify-between py-2">
                            <span class="text-gray-500">Cancelled On</span>
                            <span class="font-medium text-rose-600">{{ $booking->cancelled_at->format('M d, Y h:i A') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Notes --}}
            @if($booking->notes || $booking->special_requests)
                <div class="bg-white rounded-2xl shadow-md border border-cyan-100 overflow-hidden">
                    <div class="px-6 py-4 bg-cyan-50/50 border-b border-cyan-100">
                        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fa fa-pencil text-cyan-600"></i>
                            Notes
                        </h2>
                    </div>
                    <div class="p-4 space-y-3 text-sm">
                        @if($booking->notes)
                            <div>
                                <p class="text-gray-500 text-xs">Admin Notes</p>
                                <p class="text-gray-700">{{ $booking->notes }}</p>
                            </div>
                        @endif
                        @if($booking->special_requests)
                            <div>
                                <p class="text-gray-500 text-xs">Special Requests</p>
                                <p class="text-gray-700">{{ $booking->special_requests }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Passengers List --}}
    <div class="mt-6 bg-white rounded-2xl shadow-xl border overflow-hidden">
        <div class="px-6 py-4 bg-cyan-50/50 border-b flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-users text-cyan-600"></i>
                Passengers
                <span class="text-sm font-normal text-gray-400">
                    ({{ $booking->passengers->count() }} passengers)
                </span>
            </h2>
            <a href="{{ route('admin.passengers.create', ['booking_id' => $booking->id]) }}" 
            class="text-sm text-cyan-600 hover:text-cyan-800 font-medium transition inline-flex items-center gap-1">
            <i class="fa-regular fa-plus mr-1"></i>
            Add Passenger
            </a>
        </div>

        @if($booking->passengers->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Seat</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Nationality</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-cyan-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->passengers as $passenger)
                            <tr class="border-b border-gray-50 hover:bg-cyan-50/30 transition">
                                <td class="px-6 py-3 font-medium text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-700 text-xs font-bold border border-cyan-200">
                                            {{ strtoupper(substr($passenger->first_name, 0, 1) . substr($passenger->last_name, 0, 1)) }}
                                        </div>
                                        {{ $passenger->getFullName() }}
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-gray-600">{{ $passenger->email ?? 'N/A' }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $passenger->phone ?? 'N/A' }}</td>
                                <td class="px-6 py-3 font-mono font-bold text-purple-700">{{ $passenger->ticket?->seat_number ?? 'N/A' }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $passenger->nationality ?? 'N/A' }}</td>
                                <td class="px-6 py-3">
                                        {{ $passenger->booking->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($passenger->ticket)
                                        <a href="{{ route('admin.tickets.show', $passenger->ticket) }}" 
                                        class="text-cyan-600 hover:text-cyan-600 text-sm mr-2 transition inline-flex items-center gap-1">
                                            <i class="fa-regular fa-eye"></i>
                                            View Ticket
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm">No ticket available</span>
                                    @endif
                                        <a href="{{ route('admin.passengers.edit', $passenger) }}" class="text-cyan-600 hover:text-cyan-800 text-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.passengers.destroy', $passenger) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-rose-500 hover:text-rose-700 text-sm"
                                                    onclick="return confirm('Remove this passenger?')">
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
            <div class="px-6 py-12 text-center text-gray-400">
                <i class="fa fa-users text-3xl mb-3 block"></i>
                <p>No passengers found for this booking.</p>
                <a href="{{ route('admin.passengers.create', ['booking_id' => $booking->id]) }}" 
                class="mt-2 inline-block text-sm text-cyan-600 hover:text-cyan-800 font-medium transition">
                    <i class="fa fa-user-plus mr-1"></i>
                    Add first passenger
                </a>
            </div>
        @endif
    </div>

    {{-- Activities / Timeline --}}
    <div class="mt-6 bg-white rounded-2xl shadow-xl border overflow-hidden">
        <div class="px-6 py-4 bg-cyan-50/50 border-b border-cyan-100">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa fa-clock text-cyan-600"></i>
                Activity Timeline
            </h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                {{-- Booking Created --}}
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-cyan-100 flex items-center justify-center">
                            <i class="fa-regular fa-calendar-plus text-cyan-600 text-sm"></i>
                        </div>
                        <div class="w-px h-full bg-gray-200 mt-1"></div>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Booking Created</p>
                        <p class="text-sm text-gray-500">{{ $booking->created_at->format('M d, Y h:i A') }}</p>
                        <p class="text-xs text-gray-400">Booking reference {{ $booking->booking_reference }} was created</p>
                    </div>
                </div>

                {{-- Booking Confirmed --}}
                @if($booking->confirmed_at)
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                                <i class="fa-regular fa-circle-check text-emerald-600 text-sm"></i>
                            </div>
                            <div class="w-px h-full bg-gray-200 mt-1"></div>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Booking Confirmed</p>
                            <p class="text-sm text-gray-500">{{ $booking->confirmed_at->format('M d, Y h:i A') }}</p>
                            <p class="text-xs text-gray-400">Booking was confirmed</p>
                        </div>
                    </div>
                @endif

                {{-- Booking Cancelled --}}
                @if($booking->cancelled_at)
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center">
                                <i class="fa-regular fa-circle-xmark text-rose-600 text-sm"></i>
                            </div>
                            <div class="w-px h-full bg-gray-200 mt-1"></div>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Booking Cancelled</p>
                            <p class="text-sm text-gray-500">{{ $booking->cancelled_at->format('M d, Y h:i A') }}</p>
                            <p class="text-xs text-gray-400">Booking was cancelled</p>
                        </div>
                    </div>
                @endif

                {{-- Last Updated --}}
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fa fa-pen text-gray-500 text-sm"></i>
                        </div>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Last Updated</p>
                        <p class="text-sm text-gray-500">{{ $booking->updated_at->format('M d, Y h:i A') }}</p>
                        <p class="text-xs text-gray-400">Booking was last modified</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>