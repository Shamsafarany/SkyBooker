<x-layout>
    <x-slot:title>Flight {{ $flight['flight_number'] }}</x-slot:title>

    {{-- Header --}}
    <div class="mb-6 pb-4 border-b border-purple-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    {{-- Back Button --}}
                    <a href="{{ route('admin.flights.index') }}" 
                       class="text-purple-400 hover:text-purple-600 transition p-2 hover:bg-purple-50 rounded-lg">
                        <i class="fa-solid fa-arrow-left text-lg"></i>
                    </a>
                    
                    {{-- Flight Number --}}
                    <h1 class="text-3xl md:text-4xl font-extrabold text-purple-900 tracking-tight">
                        {{ $flight['flight_number'] }}
                    </h1>
                    
                    {{-- Airline --}}
                    <span class="text-sm text-purple-600 font-medium bg-purple-50 px-3 py-1 rounded-full border border-purple-200">
                        <i class="fa-regular fa-building mr-1"></i>
                        {{ $flight['airline'] }}
                    </span>
                    
                    {{-- Status Badge --}}
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold
                        {{ $flight['status'] === 'scheduled' ? 'bg-blue-100 text-blue-800 border border-blue-200' : '' }}
                        {{ $flight['status'] === 'open' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : '' }}
                        {{ $flight['status'] === 'closing' ? 'bg-amber-100 text-amber-800 border border-amber-200' : '' }}
                        {{ $flight['status'] === 'completed' ? 'bg-gray-100 text-gray-800 border border-gray-200' : '' }}
                        {{ $flight['status'] === 'cancelled' ? 'bg-rose-100 text-rose-800 border border-rose-200' : '' }}
                        {{ $flight['status'] === 'delayed' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : '' }}
                        {{ $flight['status'] === 'boarding' ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : '' }}
                        {{ $flight['status'] === 'departed' ? 'bg-purple-100 text-purple-800 border border-purple-200' : '' }}">
                        <i class="fa-regular 
                            {{ $flight['status'] === 'scheduled' ? 'fa-calendar' : '' }}
                            {{ $flight['status'] === 'open' ? 'fa-circle-check' : '' }}
                            {{ $flight['status'] === 'closing' ? 'fa-triangle-exclamation' : '' }}
                            {{ $flight['status'] === 'completed' ? 'fa-flag-checkered' : '' }}
                            {{ $flight['status'] === 'cancelled' ? 'fa-circle-xmark' : '' }}
                            {{ $flight['status'] === 'delayed' ? 'fa-clock' : '' }}
                            {{ $flight['status'] === 'boarding' ? 'fa-person-walking' : '' }}
                            {{ $flight['status'] === 'departed' ? 'fa-plane-departure' : '' }}
                            mr-1"></i>
                        {{ ucfirst($flight['status']) }}
                    </span>
                </div>
                <p class="text-purple-500 mt-1 ml-14">
                    <i class="fa-regular fa-calendar mr-1"></i>
                    {{ date('l, F d, Y', strtotime($flight['departure_date'])) }}
                </p>
            </div>
            
            {{-- Action Buttons --}}
            <div class="flex gap-3">
                <a href="#" class="bg-purple-600 text-white px-5 py-2.5 rounded-xl hover:bg-purple-700 transition flex items-center gap-2 shadow-sm hover:shadow-md">
                    <i class="fa-regular fa-pen"></i>
                    Edit
                </a>
                <button onclick="confirmDelete()" 
                        class="bg-rose-500 text-white px-5 py-2.5 rounded-xl hover:bg-rose-600 transition flex items-center gap-2 shadow-sm hover:shadow-md">
                    <i class="fa-regular fa-trash"></i>
                    Delete
                </button>
            </div>
        </div>
    </div>

    {{-- Flight Route Map --}}
    <div class="bg-gradient-to-br from-purple-50 via-purple-50/50 to-white rounded-2xl shadow-md p-8 mb-8 border border-purple-100">
        <div class="flex items-center justify-between max-w-3xl mx-auto">
            {{-- Origin --}}
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2 shadow-sm border border-purple-200">
                    <i class="fa-solid fa-plane-departure text-purple-600 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-purple-900">{{ $flight['origin'] }}</p>
                <p class="text-sm text-purple-600">{{ $flight['origin_city'] }}</p>
                <p class="text-xs text-purple-400">{{ $flight['origin_name'] ?? $flight['origin'] }}</p>
                <p class="text-sm font-semibold text-purple-700 mt-1 bg-purple-50 px-3 py-1 rounded-full border border-purple-200">
                    <i class="fa-regular fa-clock mr-1"></i>
                    {{ $flight['departure_time'] }}
                </p>
            </div>
            
            {{-- Flight Path --}}
            <div class="flex-1 mx-6">
                <div class="relative">
                    {{-- Dashed line --}}
                    <div class="border-t-2 border-purple-300 border-dashed"></div>
                    
                    {{-- Plane icon --}}
                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-purple-100 px-3 rounded-full border border-purple-200">
                        <i class="fa-solid fa-plane text-purple-600 text-lg"></i>
                    </div>
                    
                    {{-- Duration --}}
                    <p class="text-center text-xs text-purple-400 mt-3">
                        <i class="fa-regular fa-clock mr-1"></i>
                        {{ $flight['duration'] }}
                    </p>
                </div>
            </div>
            
            {{-- Destination --}}
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2 shadow-sm border border-purple-200">
                    <i class="fa-solid fa-plane-arrival text-purple-600 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-purple-900">{{ $flight['destination'] }}</p>
                <p class="text-sm text-purple-600">{{ $flight['destination_city'] }}</p>
                <p class="text-xs text-purple-400">{{ $flight['destination_name'] ?? $flight['destination'] }}</p>
                <p class="text-sm font-semibold text-purple-700 mt-1 bg-purple-50 px-3 py-1 rounded-full border border-purple-200">
                    <i class="fa-regular fa-clock mr-1"></i>
                    {{ $flight['arrival_time'] }}
                </p>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-5 border border-purple-100 hover:shadow-md transition">
            <p class="text-xs text-purple-500 uppercase tracking-wider">
                <i class="fa-regular fa-chair mr-1"></i>
                Total Seats
            </p>
            <p class="text-2xl font-bold text-purple-900">{{ $flight['total_seats'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-purple-100 hover:shadow-md transition">
            <p class="text-xs text-purple-500 uppercase tracking-wider">
                <i class="fa-regular fa-user-check mr-1"></i>
                Booked
            </p>
            <p class="text-2xl font-bold text-blue-600">{{ $flight['booked_seats'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-purple-100 hover:shadow-md transition">
            <p class="text-xs text-purple-500 uppercase tracking-wider">
                <i class="fa-regular fa-user-plus mr-1"></i>
                Available
            </p>
            <p class="text-2xl font-bold text-emerald-600">{{ $flight['available_seats'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-purple-100 hover:shadow-md transition">
            <p class="text-xs text-purple-500 uppercase tracking-wider">
                <i class="fa-regular fa-dollar-sign mr-1"></i>
                Price
            </p>
            <p class="text-2xl font-bold text-purple-700">${{ number_format($flight['price'], 2) }}</p>
        </div>
    </div>

    {{-- Two Column Layout: Details + Sidebar --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Details --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-md p-6 border border-purple-100">
                <h2 class="font-semibold text-purple-800 text-lg mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-purple-600"></i>
                    Flight Details
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-purple-50/50 rounded-xl p-4 border border-purple-100">
                        <p class="text-xs text-purple-500 uppercase tracking-wider font-medium">Flight Number</p>
                        <p class="font-mono font-bold text-purple-900 text-lg">{{ $flight['flight_number'] }}</p>
                    </div>
                    <div class="bg-purple-50/50 rounded-xl p-4 border border-purple-100">
                        <p class="text-xs text-purple-500 uppercase tracking-wider font-medium">Airline</p>
                        <p class="font-semibold text-gray-900">{{ $flight['airline'] }}</p>
                    </div>
                    <div class="bg-purple-50/50 rounded-xl p-4 border border-purple-100">
                        <p class="text-xs text-purple-500 uppercase tracking-wider font-medium">Aircraft</p>
                        <p class="font-semibold text-gray-900">{{ $flight['airplane'] }}</p>
                    </div>
                    <div class="bg-purple-50/50 rounded-xl p-4 border border-purple-100">
                        <p class="text-xs text-purple-500 uppercase tracking-wider font-medium">Departure</p>
                        <p class="font-semibold text-gray-900">{{ date('M d, Y', strtotime($flight['departure_date'])) }}</p>
                        <p class="text-sm text-purple-600">{{ $flight['departure_time'] }}</p>
                    </div>
                    <div class="bg-purple-50/50 rounded-xl p-4 border border-purple-100">
                        <p class="text-xs text-purple-500 uppercase tracking-wider font-medium">Arrival</p>
                        <p class="font-semibold text-gray-900">{{ date('M d, Y', strtotime($flight['departure_date'])) }}</p>
                        <p class="text-sm text-purple-600">{{ $flight['arrival_time'] }}</p>
                    </div>
                    <div class="bg-purple-50/50 rounded-xl p-4 border border-purple-100">
                        <p class="text-xs text-purple-500 uppercase tracking-wider font-medium">Duration</p>
                        <p class="font-semibold text-gray-900">{{ $flight['duration'] }}</p>
                    </div>
                    <div class="bg-purple-50/50 rounded-xl p-4 border border-purple-100 col-span-1 md:col-span-2">
                        <p class="text-xs text-purple-500 uppercase tracking-wider font-medium">Booking Deadline</p>
                        <p class="font-semibold text-gray-900">
                            {{ isset($flight['booking_deadline']) ? date('M d, Y h:i A', strtotime($flight['booking_deadline'])) : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div>
            <div class="bg-white rounded-2xl shadow-md p-6 border border-purple-100">
                <h3 class="font-semibold text-purple-800 text-sm uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-purple-600"></i>
                    Quick Actions
                </h3>
                <div class="space-y-3">
                    <button class="w-full bg-emerald-600 text-white px-4 py-2.5 rounded-xl hover:bg-emerald-700 transition text-sm font-medium shadow-sm hover:shadow-md">
                        <i class="fa-regular fa-circle-check mr-1"></i>
                        Mark as Open
                    </button>
                    <button class="w-full bg-amber-600 text-white px-4 py-2.5 rounded-xl hover:bg-amber-700 transition text-sm font-medium shadow-sm hover:shadow-md">
                        <i class="fa-regular fa-triangle-exclamation mr-1"></i>
                        Mark as Closing
                    </button>
                    <button class="w-full bg-yellow-600 text-white px-4 py-2.5 rounded-xl hover:bg-yellow-700 transition text-sm font-medium shadow-sm hover:shadow-md">
                        <i class="fa-regular fa-clock mr-1"></i>
                        Mark as Delayed
                    </button>
                    <button class="w-full bg-rose-600 text-white px-4 py-2.5 rounded-xl hover:bg-rose-700 transition text-sm font-medium shadow-sm hover:shadow-md">
                        <i class="fa-regular fa-circle-xmark mr-1"></i>
                        Cancel Flight
                    </button>
                    <button class="w-full bg-gray-600 text-white px-4 py-2.5 rounded-xl hover:bg-gray-700 transition text-sm font-medium shadow-sm hover:shadow-md">
                        <i class="fa-regular fa-flag-checkered mr-1"></i>
                        Mark as Completed
                    </button>
                </div>
            </div>

            {{-- Additional Info --}}
            <div class="mt-4 bg-white rounded-2xl shadow-md p-6 border border-purple-100">
                <h3 class="font-semibold text-purple-800 text-sm uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fa-regular fa-chart-pie text-purple-600"></i>
                    Flight Summary
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-purple-50">
                        <span class="text-purple-500">Created</span>
                        <span class="font-medium text-gray-700">{{ date('M d, Y', strtotime($flight['created_at'] ?? now())) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-purple-50">
                        <span class="text-purple-500">Last Updated</span>
                        <span class="font-medium text-gray-700">{{ date('M d, Y', strtotime($flight['updated_at'] ?? now())) }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-purple-500">Seat Fill Rate</span>
                        <span class="font-medium text-purple-700">
                            {{ $flight['total_seats'] > 0 ? round(($flight['booked_seats'] / $flight['total_seats']) * 100) : 0 }}%
                        </span>
                    </div>
                </div>
                
                {{-- Progress Bar --}}
                <div class="mt-3">
                    <div class="w-full bg-purple-100 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" 
                             style="width: {{ $flight['total_seats'] > 0 ? round(($flight['booked_seats'] / $flight['total_seats']) * 100) : 0 }}%">
                        </div>
                    </div>
                    <p class="text-xs text-purple-400 mt-1 text-right">
                        {{ $flight['booked_seats'] }} of {{ $flight['total_seats'] }} seats booked
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Passenger List --}}
    <div class="mt-8 bg-white rounded-2xl shadow-md border border-purple-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-purple-100 flex items-center justify-between bg-purple-50/50">
            <h2 class="font-semibold text-purple-800 flex items-center gap-2">
                <i class="fa-solid fa-users text-purple-600"></i>
                Passengers
                <span class="text-sm font-normal text-purple-400">
                    ({{ $flight['booked_seats'] }}/{{ $flight['total_seats'] }} seats filled)
                </span>
            </h2>
            <button class="text-sm text-purple-600 hover:text-purple-800 font-medium transition bg-white px-4 py-1.5 rounded-lg border border-purple-200 hover:bg-purple-50">
                <i class="fa-regular fa-user-plus mr-1"></i>
                Add Passenger
            </button>
        </div>
        
        @if(isset($flight['passengers']) && count($flight['passengers']) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-purple-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-purple-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-purple-500 uppercase tracking-wider">Seat</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-purple-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-purple-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($flight['passengers'] as $passenger)
                            <tr class="border-b border-gray-50 hover:bg-purple-50/30 transition">
                                <td class="px-6 py-3 font-medium text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-700 text-xs font-bold border border-purple-200">
                                            {{ strtoupper(substr($passenger['name'], 0, 1)) }}
                                        </div>
                                        {{ $passenger['name'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-gray-600">{{ $passenger['email'] }}</td>
                                <td class="px-6 py-3 font-mono font-bold text-purple-700">{{ $passenger['seat'] }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <i class="fa-regular fa-circle-check mr-1"></i>
                                        Confirmed
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <a href="#" class="text-purple-600 hover:text-purple-800 text-sm mr-2 transition">
                                        <i class="fa-regular fa-pen"></i>
                                    </a>
                                    <a href="#" class="text-rose-500 hover:text-rose-700 text-sm transition">
                                        <i class="fa-regular fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center text-purple-300">
                <i class="fa-solid fa-users text-3xl mb-3 block"></i>
                <p>No passengers booked on this flight yet.</p>
                <button class="mt-2 text-sm text-purple-600 hover:text-purple-800 font-medium transition">
                    <i class="fa-regular fa-user-plus mr-1"></i>
                    Add first passenger
                </button>
            </div>
        @endif
    </div>
</x-layout>

{{-- Delete Confirmation Script --}}
<script>
    function confirmDelete() {
        if (confirm('Are you sure you want to delete flight {{ $flight['flight_number'] }}?')) {
            document.getElementById('delete-form').submit();
        }
    }
</script>

{{-- Hidden Delete Form --}}
<form id="delete-form" 
      action="{{ route('admin.flights.destroy', $flight['id']) }}" 
      method="POST" 
      style="display: none;">
    @csrf
    @method('DELETE')
</form>