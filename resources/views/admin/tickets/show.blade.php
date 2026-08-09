<x-layout>
    <x-slot:title>Ticket {{ $ticket->ticket_number }}</x-slot:title>

    <div class="max-w-4xl mx-auto">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ url()->previous() }}" class="text-cyan-600 hover:text-cyan-800 transition">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back
            </a>
        </div>

        {{-- Ticket Card --}}
        <div class="bg-white rounded-2xl shadow-xl border overflow-hidden">
            {{-- Ticket Header --}}
            <div class="bg-gradient from-cyan-600 to-cyan-800 px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-cyan-800 text-sm uppercase tracking-wider">Boarding Pass</p>
                        <h1 class="text-3xl font-bold text-white">{{ $ticket->ticket_number }}</h1>
                    </div>
                    <div class="text-right">
                        <p class="text-cyan-600 text-sm">Status</p>
                        <p class="px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $ticket->passenger->booking->status }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Ticket Body --}}
            <div class="p-8">
                {{-- Route --}}
                <div class="flex items-center justify-between bg-gray-50 rounded-xl p-6 mb-6 shadow-lg">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-gray-900">{{ $ticket->passenger->booking->flight->origin->code }}</p>
                        <p class="text-sm text-gray-500">{{ $ticket->passenger->booking->flight->origin->city }}</p>
                    </div>
                    <div class="flex-1 mx-4">
                        <div class="relative">
                            <div class="border-t-2 border-gray-300 border-dashed"></div>
                            <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 bg-white px-2 shadow-md rounded-full">
                                <i class="fa-solid fa-plane text-cyan-700"></i>
                            </div>
                            <p class="text-center text-xs text-gray-400 mt-4">{{ $ticket->passenger->booking->flight->duration }}</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-gray-900">{{ $ticket->passenger->booking->flight->destination->code }}</p>
                        <p class="text-sm text-gray-500">{{ $ticket->passenger->booking->flight->destination->city }}</p>
                    </div>
                </div>

                {{-- Passenger Info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Passenger</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $ticket->passenger->getFullName() }}</p>
                        <p class="text-sm text-gray-500">{{ $ticket->passenger->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Flight</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $ticket->passenger->booking->flight->flight_number }}</p>
                        <p class="text-sm text-gray-500">{{ $ticket->passenger->booking->flight->airline->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Seat</p>
                        <p class="text-lg font-semibold text-cyan-700">{{ $ticket->seat_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Class</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $ticket->class }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Meal</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $ticket->meal_preference }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Booking Reference</p>
                        <a href="{{ route('admin.bookings.show', $ticket->passenger->booking) }}" class="text-lg font-mono text-cyan-600 hover:text-cyan-500 transition">{{ $ticket->passenger->booking->booking_reference }}</a>
                    </div>
                </div>

                {{-- Dates --}}
                <div class="mt-6 pt-6 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Departure</p>
                        <p class="font-semibold text-gray-900">
                            {{ $ticket->passenger->booking->flight->departure_date->format('M d, Y') }}
                        </p>
                        <p class="text-sm text-cyan-600">{{ $ticket->passenger->booking->flight->departure_time }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Arrival</p>
                        <p class="font-semibold text-gray-900">
                            {{ $ticket->passenger->booking->flight->arrival_date->format('M d, Y') }}
                        </p>
                        <p class="text-sm text-cyan-600">{{ $ticket->passenger->booking->flight->arrival_time }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Issued At</p>
                        <p class="font-semibold text-gray-900">
                            {{ $ticket->issued_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Ticket Footer --}}
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 flex justify-between items-center">
                <span class="text-xs text-gray-400">Ticket #{{ $ticket->ticket_number }}</span>
                <div class="flex gap-2">
                    <button onclick="window.print()" class="text-cyan-600 hover:text-cyan-800 text-sm transition">
                        <i class="fa-regular fa-print mr-1"></i> Print
                    </button>
                    <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-cyan-600 hover:text-cyan-800 text-sm transition">
                        <i class="fa-regular fa-rotate-right mr-1"></i> Refresh
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>