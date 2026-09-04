<x-layout>
    <x-slot:title>Flight {{ $flight->flight_number }}</x-slot:title>

    {{-- Header --}}
    <div class="mb-6 pb-4 border-b border-cyan-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    {{-- Back Button --}}
                    <a href="{{ route('admin.flights.index') }}" 
                        class="text-cyan-900 hover:text-cyan-700 transition p-2 hover:bg-cyan-50 rounded-lg">
                        <i class="fa-solid fa-arrow-left text-lg"></i>
                    </a>
                    
                    {{-- Flight Number --}}
                    <h1 class="text-3xl md:text-4xl font-extrabold text-cyan-900 tracking-tight mb-2">
                        {{ $flight->flight_number }}
                    </h1>
                    
                    {{-- Status Badge --}}
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold ml-1 mb-2
                        {{ $flight['status'] === 'scheduled' ? 'bg-blue-100 text-blue-800 border border-blue-200' : '' }}
                        {{ $flight['status'] === 'open' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : '' }}
                        {{ $flight['status'] === 'closing' ? 'bg-amber-100 text-amber-800 border border-amber-200' : '' }}
                        {{ $flight['status'] === 'completed' ? 'bg-gray-100 text-gray-800 border border-gray-200' : '' }}
                        {{ $flight['status'] === 'cancelled' ? 'bg-rose-100 text-rose-800 border border-rose-200' : '' }}
                        {{ $flight['status'] === 'delayed' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : '' }}
                        {{ $flight['status'] === 'boarding' ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : '' }}
                        {{ $flight['status'] === 'departed' ? 'bg-gray-100 text-cyan-800 border border-cyan-200' : '' }}">
                        <i class="fa-regular 
                            {{ $flight['status'] === 'scheduled' ? 'fa-calendar' : '' }}
                            {{ $flight['status'] === 'open' ? 'fa-circle-check' : '' }}
                            {{ $flight['status'] === 'closing' ? 'fa fa-triangle-exclamation' : '' }}
                            {{ $flight['status'] === 'completed' ? 'fa-flag-checkered' : '' }}
                            {{ $flight['status'] === 'cancelled' ? 'fa-circle-xmark' : '' }}
                            {{ $flight['status'] === 'delayed' ? 'fa-clock' : '' }}
                            {{ $flight['status'] === 'boarding' ? 'fa-person-walking' : '' }}
                            {{ $flight['status'] === 'departed' ? 'fa-plane-departure' : '' }}
                            mr-1"></i>
                        {{ ucfirst($flight->status) }}
                    </span>
                </div>

                {{-- Airline --}}
                <span class="text-sm font-semibold text-cyan-800 px-3 py-1 rounded-full border border-cyan-600 ml-10 space-x-2">
                    <i class="fa-regular fa-building mr-1"></i>
                    {{ $flight->airline->name }}
                </span>
                <p class="text-red-700 mt-5 ml-11">
                    <i class="fa-regular fa-calendar mr-1"></i>
                    {{ date('l, F d, Y', strtotime($flight->departure_date)) }}
                </p>
            </div>
            
            {{-- Action Buttons --}}
            <div class="flex gap-3">
                <a href="{{ route('admin.flights.edit', $flight) }}" class="bg-cyan-800 text-white px-3 py-2 rounded-xl hover:bg-cyan-600 transition flex items-center gap-2 shadow-sm hover:shadow-md">
                    Edit
                </a>
                <form action="{{ route('admin.flights.destroy', $flight) }}" 
                    method="POST" 
                    class="inline"
                    onsubmit="return confirm('Are you sure you want to delete flight {{ $flight->flight_number }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-rose-700 text-white px-3 py-2 rounded-xl hover:bg-rose-600 transition flex items-center gap-2 shadow-sm hover:shadow-md">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>


    {{-- Flight Route Map --}}
    <div class="bg--to-br from-cyan-50 via-cyan-50/50 to-white rounded-2xl shadow-md p-8 mb-8 border bg-white">
        <div class="flex items-center justify-between max-w-3xl mx-auto">
            {{-- Origin --}}
            <div class="text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2 shadow-xl border">
                    <i class="fa-solid fa-plane-departure text-cyan-600 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-cyan-900">{{ $flight->origin->code }}</p>
                <p class="text-sm text-cyan-700">{{ $flight->origin->city }}</p>
                <p class="text-xs text-gray-700 mt-1">{{ $flight->origin->name }}</p>
                <p class="text-sm font-semibold text-cyan-700 mt-4 px-3 py-1 rounded-full border shadow-md">
                    <i class="fa-regular fa-clock mr-1"></i>
                    {{ $flight->departure_time }}
                </p>
            </div>
            
            {{-- Flight Path --}}
            <div class="flex-1 mx-6">
                <div class="relative">
          
                    <div class="border-t-2 border-gray-400 border-dashed"></div>
                    
                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-gray-100 px-3 rounded-full border shadow-xl">
                        <i class="fa-solid fa-plane text-cyan-900 text-lg"></i>
                    </div>

                    <p class="text-center text-s text-cyan-700 mt-9 font-semibold">
                        <i class="fa-regular fa-clock mr-1"></i>
                        {{ $flight->duration }}
                    </p>
                </div>
            </div>
            
            {{-- Destination --}}
            <div class="text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2 shadow-xl border">
                    <i class="fa-solid fa-plane-arrival text-cyan-600 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-cyan-900">{{ $flight->destination->code }}</p>
                <p class="text-sm text-cyan-600">{{ $flight->destination->city }}</p>
                <p class="text-xs text-gray-700 mt-1">{{ $flight->destination->name }}</p>
                <p class="text-sm font-semibold text-cyan-700 mt-4 px-3 py-1 rounded-full border shadow-md">
                    <i class="fa-regular fa-clock mr-1"></i>
                    {{ $flight->arrival_time }}
                </p>
            </div>
        </div>
    </div>

<x-stats
    title="Seat Overview"
    :stats="[
        [
            'label' => 'Total Seats',
            'value' => $flight->total_seats,
            'icon' => 'fa-regular fa-circle-check text-cyan-600',
            'color' => 'text-cyan-900',
        ],
        [
            'label' => 'Booked',
            'value' => $flight->booked_seats,
            'icon' => 'fa-regular fa-circle-check text-blue-600',
            'color' => 'text-blue-600',
        ], 
        [
            'label' => 'Available',
            'value' => $flight->available_seats,
            'icon' => 'fa-regular fa-circle-check text-emerald-600',
            'color' => 'text-emerald-600',
        ],
        [
            'label' => 'Price',
            'value' => '$' . number_format($flight->price, 2),
            'icon' => 'fa-regular fa-dollar-sign text-cyan-600',
            'color' => 'text-cyan-700',
        ],
    ]"
    :columns="4"
    class="mb-8"
/>

    {{-- Two Column Layout: Details + Sidebar --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Details --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-xl p-6 border">
                <h2 class="font-semibold text-cyan-800 text-lg mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-cyan-800"></i>
                    Flight Details
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-100 rounded-xl p-4 border shadow-xl">
                        <p class="text-xs text-cyan-700 uppercase tracking-wider font-medium">Flight Number</p>
                        <p class="font-mono font-bold text-cyan-900 text-lg">{{ $flight->flight_number}}</p>
                    </div>
                    <div class="bg-gray-100 rounded-xl p-4 border shadow-xl">
                        <p class="text-xs text-cyan-700 uppercase tracking-wider font-medium">Airline</p>
                        <p class="font-semibold text-gray-900">{{ $flight->airline->name }}</p>
                    </div>
                    <div class="bg-gray-100 rounded-xl p-4 border shadow-xl">
                        <p class="text-xs text-cyan-700 uppercase tracking-wider font-medium">Aircraft</p>
                        <p class="font-semibold text-gray-900">{{ $flight->airplane->model }}</p>
                    </div>
                    <div class="bg-gray-100 rounded-xl p-4 border shadow-xl">
                        <p class="text-xs text-cyan-700 uppercase tracking-wider font-medium">Departure</p>
                        <p class="font-semibold text-gray-900">{{ date('M d, Y', strtotime($flight->departure_date)) }}</p>
                        <p class="text-sm text-cyan-600">{{ $flight->departure_time }}</p>
                    </div>
                    <div class="bg-gray-100 rounded-xl p-4 border shadow-xl">
                        <p class="text-xs text-cyan-700 uppercase tracking-wider font-medium">Arrival</p>
                        <p class="font-semibold text-gray-900">{{ date('M d, Y', strtotime($flight->arrival_date)) }}</p>
                        <p class="text-sm text-cyan-600">{{ $flight->arrival_time }}</p>
                    </div>
                    <div class="bg-gray-100 rounded-xl p-4 border shadow-xl">
                        <p class="text-xs text-cyan-700 uppercase tracking-wider font-medium">Duration</p>
                        <p class="font-semibold text-gray-900">{{ $flight->duration }}</p>
                    </div>
                    <div class="bg-gray-100 rounded-xl p-4 border col-span-1 md:col-span-2 shadow-xl">
                        <p class="text-xs text-cyan-700 uppercase tracking-wider font-medium">Booking Deadline</p>
                        <p class="font-semibold text-gray-900">
                            {{ isset($flight->booking_deadline) ? date('M d, Y h:i A', strtotime($flight->booking_deadline)) : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div>
            {{-- Sidebar --}}
<div class="bg-white rounded-2xl shadow-md border border-cyan-100 overflow-hidden">
    <div class="px-6 py-4 bg-cyan-50/50 border-b border-cyan-100">
        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <i class="fa fa-bolt text-cyan-600"></i>
            Quick Actions
        </h2>
    </div>

    <div class="p-4 space-y-2">

        {{-- SCHEDULED --}}
        @if($flight->status === 'scheduled')
            <form action="{{ route('admin.flights.changeStatus', $flight) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="open">
                <button type="submit" class="w-full bg-emerald-600 text-white px-4 py-2.5 rounded-lg">
                    <i class="fa fa-door-open mr-1"></i> Open Flight
                </button>
            </form>

            <form action="{{ route('admin.flights.changeStatus', $flight) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="w-full bg-rose-600 text-white px-4 py-2.5 rounded-lg"
                        onclick="return confirm('Cancel this flight?')">
                    <i class="fa fa-xmark mr-1"></i> Cancel Flight
                </button>
            </form>

            <form action="{{ route('admin.flights.changeStatus', $flight) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="delayed">
                <button type="submit" class="w-full bg-amber-600 text-white px-4 py-2.5 rounded-lg">
                    <i class="fa fa-clock mr-1"></i> Mark as Delayed
                </button>
            </form>
        @endif


        {{-- OPEN --}}
        @if($flight->status === 'open')
            <form action="{{ route('admin.flights.changeStatus', $flight) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="closing">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2.5 rounded-lg">
                    <i class="fa fa-door-closed mr-1"></i> Start Closing
                </button>
            </form>

            <form action="{{ route('admin.flights.changeStatus', $flight) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="w-full bg-rose-600 text-white px-4 py-2.5 rounded-lg"
                        onclick="return confirm('Cancel this flight?')">
                    <i class="fa fa-xmark mr-1"></i> Cancel Flight
                </button>
            </form>

            <form action="{{ route('admin.flights.changeStatus', $flight) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="delayed">
                <button type="submit" class="w-full bg-amber-600 text-white px-4 py-2.5 rounded-lg">
                    <i class="fa fa-clock mr-1"></i> Mark as Delayed
                </button>
            </form>
        @endif


        {{-- CLOSING --}}
        @if($flight->status === 'closing')
            <form action="{{ route('admin.flights.changeStatus', $flight) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="w-full bg-emerald-600 text-white px-4 py-2.5 rounded-lg">
                    <i class="fa fa-flag-checkered mr-1"></i> Mark as Completed
                </button>
            </form>

            <form action="{{ route('admin.flights.changeStatus', $flight) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="w-full bg-rose-600 text-white px-4 py-2.5 rounded-lg"
                        onclick="return confirm('Cancel this flight?')">
                    <i class="fa fa-xmark mr-1"></i> Cancel Flight
                </button>
            </form>

            <form action="{{ route('admin.flights.changeStatus', $flight) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="delayed">
                <button type="submit" class="w-full bg-amber-600 text-white px-4 py-2.5 rounded-lg">
                    <i class="fa fa-clock mr-1"></i> Mark as Delayed
                </button>
            </form>
        @endif


        {{-- COMPLETED / CANCELLED --}}
        @if(in_array($flight->status, ['completed', 'cancelled']))
            <div class="text-gray-500 text-sm italic">
                No actions available for this flight.
            </div>
        @endif


        {{-- DELAYED --}}
        @if($flight->status === 'delayed')
            <form action="{{ route('admin.flights.changeStatus', $flight) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="open">
                <button type="submit" class="w-full bg-emerald-600 text-white px-4 py-2.5 rounded-lg">
                    <i class="fa fa-door-open mr-1"></i> Reopen Flight
                </button>
            </form>

            <form action="{{ route('admin.flights.changeStatus', $flight) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="closing">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2.5 rounded-lg">
                    <i class="fa fa-door-closed mr-1"></i> Start Closing
                </button>
            </form>

            <form action="{{ route('admin.flights.changeStatus', $flight) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="w-full bg-rose-600 text-white px-4 py-2.5 rounded-lg"
                        onclick="return confirm('Cancel this flight?')">
                    <i class="fa fa-xmark mr-1"></i> Cancel Flight
                </button>
            </form>
        @endif

    </div>
</div>

            {{-- Additional Info --}}
            <div class="mt-4 bg-white rounded-2xl shadow-md p-6 border">
                <h3 class="font-semibold text-cyan-800 text-sm uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fa fa-chart-pie text-cyan-800"></i>
                    Flight Summary
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-cyan-50">
                        <span class="text-cyan-700">Created</span>
                        <span class="font-medium text-gray-700">{{ date('M d, Y', strtotime($flight->created_at ?? now())) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-cyan-50">
                        <span class="text-cyan-700">Last Updated</span>
                        <span class="font-medium text-gray-700">{{ date('M d, Y', strtotime($flight->updated_at ?? now())) }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-cyan-700">Seat Fill Rate</span>
                        <span class="font-medium text-cyan-700">
                            {{ $flight->total_seats > 0 ? round(($flight->booked_seats / $flight->total_seats) * 100) : 0 }}%
                        </span>
                    </div>
                </div>
                {{-- Progress Bar --}}
                <div class="mt-3">
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-cyan-600 h-2 rounded-full" 
                             style="width: {{ $flight['total_seats'] > 0 ? round(($flight->booked_seats / $flight->total_seats) * 100) : 0 }}%">
                        </div>
                    </div>
                    <p class="text-xs text-cyan-600 mt-1 text-right">
                        {{ $flight->booked_seats }} of {{ $flight->total_seats }} seats booked
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                
            </div>
        </div>
            {{-- Origin Weather --}}
                    <x-cards.weather
                        city="{{ $flight->origin->city }}"
                        :weather="$originWeather"
                        class="border-l-4 border-l-cyan-700 shadow-xl mt-4"
                    />
                </div>
    </div>

    {{-- Passenger List --}}
    <div class="mt-8 bg-white rounded-2xl shadow-md border overflow-hidden">
        <div class="px-6 py-4 border-b border-cyan-100 flex items-center justify-between bg-cyan-50/50">
            <h2 class="font-semibold text-cyan-800 flex items-center gap-2">
                <i class="fa-solid fa-users text-cyan-600"></i>
                Passengers
                <span class="text-sm font-normal text-cyan-600">
                    (<span class="text-cyan-700 font-bold">{{ $flight->booked_seats}}</span>/{{ $flight->total_seats }} seats filled)
                </span>
            </h2>
        </div>
        
        @if(isset($passengers) && count($passengers)>0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Seat</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-cyan-700 uppercase tracking-wider">Actions</th>
                            <th class="tracking-wider"></th>
                            <th class="tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($passengers as $passenger)
                            <tr class="border-b border-gray-50 hover:bg-cyan-50/30 transition">
                                <td class="px-6 py-3 font-medium text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-cyan-700 text-xs font-bold border border-cyan-200">
                                            {{ strtoupper(substr($passenger->getFullName(), 0, 1)) }}
                                        </div>
                                        {{ $passenger->getFullName()}}
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-gray-600">{{ $passenger->email}}</td>
                                <td class="px-6 py-3 font-mono font-bold text-cyan-700">{{ $passenger->ticket? $passenger->ticket->seat_number : 'N/A' }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <i class="fa-regular fa-circle-check mr-1"></i>
                                        {{ $passenger->booking->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                {{-- 👁️ VIEW TICKET BUTTON --}}
                                <td class="px-6 py-3 text-right">
                                    @if($passenger->ticket)
                                        <a href="{{ route('admin.tickets.show', $passenger->ticket) }}" 
                                        class="text-cyan-600 hover:text-cyan-600 text-sm mr-2 transition inline-flex items-center gap-1">
                                            <i class="fa-regular fa-eye"></i>
                                            View Ticket
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm">No ticket available</span>
                                    @endif
                                </td>
                            </tr>    
                    </tbody>
                    @endforeach
                    </thead>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center text-cyan-400">
                <i class="fa-solid fa-ticket text-3xl mb-3 block"></i>
                <p>No bookings found for this flight yet.</p>
                <a href="{{ route('admin.bookings.create', ['flight_id' => $flight->id]) }}" 
                class="mt-2 inline-block text-sm text-cyan-600 hover:text-cyan-800 font-medium transition">
                    <i class="fa-regular fa-plus mr-1"></i>
                    Create Booking
                </a>
            </div>
        @endif
    </div>
    {{-- PAGINATION --}}
        <div class="mt-6">
            {{ $passengers->links() }}
        </div>
</x-layout>

{{-- Delete Confirmation Script --}}
@if(isset($passengers) && count($passengers)>0)
<script>
    function confirmDelete() {
        if (confirm('Are you sure you want to delete flight {{ $flight['flight_number'] }}?')) {
            document.getElementById('delete-form-flight').submit();
        }
    }
    function confirmDeletePassenger() {
        if (confirm('Are you sure you want to delete passenger {{ $passenger->getFullName() }}?')) {
            document.getElementById('delete-form-passenger').submit();
        }
    }
</script>

{{-- Hidden Delete Form --}}
<form id="delete-form-passenger" 
        action="{{ route('admin.passengers.destroy', $passenger['id']) }}" 
        method="POST" 
        style="display: none;">
    @csrf
    @method('DELETE')
</form>

<form id="delete-form-flight" 
        action="{{ route('admin.flights.destroy', $flight) }}" 
        method="POST" 
        style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endif