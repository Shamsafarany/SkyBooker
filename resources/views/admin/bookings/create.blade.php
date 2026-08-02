<x-layout title="Add Booking" header="Add Booking">
    <div class="mb-6">
        <a href="{{ route('admin.bookings.index') }}" 
           class="text-cyan-600 hover:text-cyan-800 transition group flex items-center gap-2 mt-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="text-sm font-medium group-hover:underline">Back to Bookings</span>
        </a>
    </div>

    <x-form.form-create
        title="Add New Booking"
        subtitle="Enter the details of the new Booking"
        action="{{ route('admin.bookings.store') }}"
        submitLabel="Create Booking"
        cancelRoute="{{ route('admin.bookings.index') }}"
        icon="fa-plus"
    >
        {{-- Booking Details --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Booking Reference (Auto-generated) --}}
    <x-form.input 
        name="booking_reference"
        label="Booking Reference"
        :value="$bookingReference" 
        icon="fa-ticket"
        readonly
        helper="Auto-generated"
    />
    {{-- User (Customer) --}}
    <x-form.select 
        name="user_id"
        label="Customer"
        icon="fa-user"
        :options="$users->pluck('full_name', 'id')->toArray()"
        required
    />
    {{-- Flight --}}
    <x-form.select 
        name="flight_id"
        label="Flight"
        icon="fa-plane"
        :options="$flights->map(function($flight) {
            return $flight->flight_number . ' (' . $flight->origin->code . ' → ' . $flight->destination->code . ')';
        })->toArray()"
        required
    />

    {{-- Number of Seats --}}
    <x-form.input 
        name="number_of_seats"
        label="Number of Seats"
        type="number"
        value="1"
        min="1"
        icon="fa-chair"
        required
        id="number_of_seats"
        helper="Enter the number of seats to book"
    />

    {{-- Total Price (Auto-calculated) --}}
    <x-form.input 
        name="total_price"
        label="Total Price ($)"
        type="number"
        step="0.01"
        icon="fa-dollar-sign"
        readonly
        id="total_price"
        helper="Auto-calculated from flight price × seats"
    />

    {{-- Booking Date (Auto-set to now) --}}
    <x-form.input 
        name="booking_date"
        label="Booking Date"
        type="datetime-local"
        value="{{ now()->format('Y-m-d\TH:i') }}"
        icon="fa-calendar"
        readonly
    />

    {{-- Status --}}
    <x-form.select 
        name="status"
        label="Status"
        icon="fa-circle-check"
        :options="[
            'pending' => '⏳ Pending',
            'confirmed' => '✅ Confirmed',
            'cancelled' => '❌ Cancelled',
            'completed' => '🏁 Completed',
            'failed' => '❌ Failed',
            'refunded' => '💰 Refunded',
        ]"
        selected="pending"
        required
    />

    {{-- Notes
    <div class="md:col-span-2">
        <x-form.textarea 
            name="notes"
            label="Notes"
            placeholder="Any additional notes about this booking..."
            rows="3"
        />
    </div>

    {{-- Special Requests
    <div class="md:col-span-2">
        <x-form.textarea 
            name="special_requests"
            label="Special Requests"
            placeholder="e.g. Wheelchair assistance, meal preferences, etc."
            rows="3"
        />
    </div> --}}
    </div>
    </x-form.form-create>

    <hr class="my-8">
    <x-form.form-create
        title="Add New Passenger"
        subtitle="Enter the details of the new Passenger"
        action="#"
        submitLabel="Create Passenger"
        cancelRoute="{{ route('admin.bookings.index') }}"
        icon="fa-plus"
    >
        {{-- Personal Information --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- First Name --}}
    <x-form.input 
        name="first_name"
        label="First Name"
        placeholder="e.g. John"
        required
    />

    {{-- Last Name --}}
    <x-form.input 
        name="last_name"
        label="Last Name"
        placeholder="e.g. Doe"
        required
    />

    {{-- Email --}}
    <x-form.input 
        name="email"
        label="Email"
        type="email"
        placeholder="john@example.com"
        required
    />

    {{-- Phone --}}
    <x-form.input 
        name="phone"
        label="Phone"
        placeholder="+1-555-123-4567"
    />

    {{-- Date of Birth --}}
    <x-form.input 
        name="date_of_birth"
        label="Date of Birth"
        type="date"
    />

    {{-- Nationality --}}
    <x-form.input 
        name="nationality"
        label="Nationality"
        placeholder="e.g. USA"
    />

    {{-- Passport Number --}}
    <x-form.input 
        name="passport_number"
        label="Passport Number"
        placeholder="e.g. AB123456"
    />

    {{-- ID Number --}}
    <x-form.input 
        name="id_number"
        label="ID Number"
        placeholder="e.g. 1234-5678-9012"
    />

    {{-- Seat Number --}}
    <x-form.input 
        name="seat_number"
        label="Seat Number"
        placeholder="e.g. 12A"
    />

    {{-- Seat Preference --}}
    <x-form.select 
        name="seat_preference"
        label="Seat Preference"
        :options="[
            'any' => 'Any',
            'window' => 'Window',
            'aisle' => 'Aisle',
            'middle' => 'Middle',
        ]"
        selected="any"
    />

    {{-- Meal Preference --}}
    <x-form.select 
        name="meal_preference"
        label="Meal Preference"
        :options="[
            'standard' => 'Standard',
            'vegetarian' => 'Vegetarian',
            'vegan' => 'Vegan',
            'gluten_free' => 'Gluten Free',
            'kosher' => 'Kosher',
            'halal' => 'Halal',
            'child_meal' => 'Child Meal',
            'none' => 'No Meal',
        ]"
        selected="standard"
    />

    {{-- Special Requests
    <div class="md:col-span-2">
        <x-form.textarea 
            name="special_requests"
            label="Special Requests"
            placeholder="e.g. Wheelchair assistance, extra legroom, etc."
            rows="3"
        />
    </div> --}}

    {{-- Status --}}
    <x-form.select 
        name="status"
        label="Status"
        :options="[
            'pending' => '⏳ Pending',
            'confirmed' => '✅ Confirmed',
            'checked_in' => '🛂 Checked In',
            'boarded' => '🛫 Boarded',
            'cancelled' => '❌ Cancelled',
        ]"
        selected="pending"
    />
</div>
</x-form.form-create>
</x-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const flightSelect = document.getElementById('flight_id');
        const seatsInput = document.getElementById('number_of_seats');
        const totalPriceInput = document.getElementById('total_price');

        // ✅ Get flight prices from the JSON
        const flightPrices = @json($flights->pluck('price', 'id'));

        function calculateTotalPrice() {
            const flightId = flightSelect.value;
            const seats = parseInt(seatsInput.value) || 1;
            
            if (flightId && flightPrices[flightId]) {
                const pricePerSeat = flightPrices[flightId];
                const total = pricePerSeat * seats;
                totalPriceInput.value = total.toFixed(2);
            } else {
                totalPriceInput.value = '';
            }
        }

        // ✅ Calculate on change
        flightSelect.addEventListener('change', calculateTotalPrice);
        seatsInput.addEventListener('input', calculateTotalPrice);

        // ✅ Calculate on page load (for default selected flight)
        // Small delay to ensure DOM is fully loaded
        setTimeout(calculateTotalPrice, 100);
    });
</script>