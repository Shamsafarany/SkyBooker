<x-layout title="Edit Flight {{ $flight->flight_number  }}" header="Edit Flight">
    <div class="mb-6">
        <a href="{{ route('admin.flights.index') }}" 
           class="text-cyan-600 hover:text-cyan-800 transition group flex items-center gap-2 mt-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="text-sm font-medium group-hover:underline">Back to Flights</span>
        </a>
    </div>

    <x-form.form-create
        title="Edit Flight: {{ $flight->flight_number }}"
        subtitle="Edit flight info"
        action="{{ route('admin.flights.update', $flight) }}"
        method="PUT"
        submitLabel="Update"
        cancelRoute="{{ route('admin.flights.show', $flight) }}"
    >
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Flight Number --}}
            <x-form.input 
                name="flight_number"
                label="Flight Number"
                placeholder="e.g. SKY101"
                icon="fa-tag"
                required
                value="{{ old('flight_number', $flight->flight_number) }}"
                readonly
            />

            {{-- Airline --}}
            <x-form.select 
                name="airline_id"
                label="Airline"
                icon="fa-building"
                :options="$airlines->pluck('name', 'id')->toArray()"
                required
                selected="{{ old('airline_id', $flight->airline_id) }}"
            />

            {{-- Origin Airport --}}
            <x-form.select 
                name="origin_airport_id"
                label="Origin"
                icon="fa-plane-departure"
                :options="$airports->pluck('name', 'id')->toArray()"
                required
                selected="{{ old('origin_airport_id', $flight->origin_airport_id) }}"
            />

            {{-- Destination Airport --}}
            <x-form.select 
                name="destination_airport_id"
                label="Destination"
                icon="fa-plane-arrival"
                :options="$airports->pluck('name', 'id')->toArray()"
                required
                selected="{{ old('destination_airport_id', $flight->destination_airport_id) }}"
                selected="{{ old('destination_airport_id', $flight->destination_airport_id) }}"
            />

            {{-- Airplane --}}
            <x-form.select 
                name="airplane_id"
                label="Airplane"
                icon="fa-plane"
                :options="$airplanes->pluck('model', 'id')->toArray()"
                required
                selected="{{ old('airplane_id', $flight->airplane_id) }}"
            />

            {{-- Departure Date --}}
            <x-form.input 
                name="departure_date"
                label="Departure Date"
                type="date"
                icon="fa-calendar"
                required
                :value="old('departure_date', $flight->departure_date?->format('Y-m-d'))"
            />

            {{-- Departure Time --}}
            <x-form.input 
                name="departure_time"
                label="Departure Time"
                type="time"
                icon="fa-clock"
                required
                value="{{ old('departure_time', $flight->departure_time)}}"
            />

            {{-- Arrival Date --}}
            <x-form.input 
                name="arrival_date"
                label="Arrival Date"
                type="date"
                icon="fa-calendar"
                required
                :value="old('arrival_date', $flight->arrival_date?->format('Y-m-d'))"
            />

            {{-- Arrival Time --}}
            <x-form.input 
                name="arrival_time"
                label="Arrival Time"
                type="time"
                icon="fa-clock"
                required
                value="{{ old('arrival_time', $flight->arrival_time )}}"
            />

            {{-- Duration --}}
            <x-form.input 
                name="duration"
                label="Duration"
                placeholder="e.g. 5h 30m"
                icon="fa-clock"
                required
                helper="Format: Xh Ym (e.g. 5h 30m)"
                value="{{ old('duration', $flight->duration) }}"
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
                value="{{ old('price', $flight->price) }}"
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
                value="{{ old('total_seats', $flight->total_seats) }}"
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
                ]"
                selected="scheduled"
                required
                selected="{{ old('status', $flight->status) }}"
            />

            {{-- Booking Deadline --}}
            <x-form.input 
                name="booking_deadline"
                label="Booking Deadline"
                type="datetime-local"
                icon="fa-clock"
                helper="Leave empty if no deadline"
                :value="old('booking_deadline', $flight->booking_deadline?->format('Y-m-d\TH:i'))"
            />
    
        </div>
    </x-form.form-create>
</x-layout>