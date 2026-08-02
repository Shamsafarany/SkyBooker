<x-layout title="Add Flight" header="Add Flight">
    <div class="mb-6">
        <a href="{{ route('admin.flights.index') }}" 
           class="text-cyan-600 hover:text-cyan-800 transition group flex items-center gap-2 mt-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="text-sm font-medium group-hover:underline">Back to Flights</span>
        </a>
    </div>

    <x-form.form-create
        title="Add New Flight"
        subtitle="Enter the details of the new Flight"
        action="{{ route('admin.flights.store') }}"
        submitLabel="Create Flight"
        cancelRoute="{{ route('admin.flights.index') }}"
        icon="fa-plus"
    >
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Flight Number --}}
            <x-form.input 
                name="flight_number"
                label="Flight Number"
                placeholder="e.g. SKY101"
                icon="fa-tag"
                required
            />

            {{-- Airline --}}
            <x-form.select 
                name="airline_id"
                label="Airline"
                icon="fa-building"
                :options="$airlines->pluck('name', 'id')->toArray()"
                required
            />

            {{-- Origin Airport --}}
            <x-form.select 
                name="origin_airport_id"
                label="Origin"
                icon="fa-plane-departure"
                :options="$airports->pluck('name', 'id')->toArray()"
                required
            />

            {{-- Destination Airport --}}
            <x-form.select 
                name="destination_airport_id"
                label="Destination"
                icon="fa-plane-arrival"
                :options="$airports->pluck('name', 'id')->toArray()"
                required
            />

            {{-- Airplane --}}
            <x-form.select 
                name="airplane_id"
                label="Airplane"
                icon="fa-plane"
                :options="$airplanes->pluck('model', 'id')->toArray()"
                required
            />

            {{-- Departure Date --}}
            <x-form.input 
                name="departure_date"
                label="Departure Date"
                type="date"
                icon="fa-calendar"
                required
            />

            {{-- Departure Time --}}
            <x-form.input 
                name="departure_time"
                label="Departure Time"
                type="time"
                icon="fa-clock"
                required
            />

            {{-- Arrival Date --}}
            <x-form.input 
                name="arrival_date"
                label="Arrival Date"
                type="date"
                icon="fa-calendar"
                required
            />

            {{-- Arrival Time --}}
            <x-form.input 
                name="arrival_time"
                label="Arrival Time"
                type="time"
                icon="fa-clock"
                required
            />

            {{-- Duration --}}
            <x-form.input 
                name="duration"
                label="Duration"
                placeholder="e.g. 5h 30m"
                icon="fa-clock"
                required
                helper="Format: Xh Ym (e.g. 5h 30m)"
            />

            {{-- Price --}}
            <x-form.input 
                name="price"
                label="Price ($)"
                type="number"
                placeholder="e.g. 299.00"
                icon="fa-dollar-sign"
                required
                step="0.01"
                min="0"
            />

            {{-- Total Seats --}}
            <x-form.input 
                name="total_seats"
                label="Total Seats"
                type="number"
                placeholder="e.g. 189"
                icon="fa-chair"
                required
                min="1"
            />

            {{-- Status --}}
            <x-form.select 
                name="status"
                label="Status"
                icon="fa-circle-check"
                :options="[
                    'scheduled' => '📅 Scheduled',
                    'open' => '🟢 Open',
                    'closing' => '🟡 Closing',
                    'completed' => '🏁 Completed',
                    'cancelled' => '❌ Cancelled',
                    'delayed' => '🕐 Delayed',
                    'boarding' => '🚶 Boarding',
                    'departed' => '🛫 Departed',
                ]"
                selected="scheduled"
                required
            />

            {{-- Booking Deadline --}}
            <x-form.input 
                name="booking_deadline"
                label="Booking Deadline"
                type="datetime-local"
                icon="fa-clock"
                helper="Leave empty if no deadline"
            />
    
        </div>
    </x-form.form-create>
</x-layout>