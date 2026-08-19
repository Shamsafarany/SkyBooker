<x-layout title="Create Passenger" header="Create Passenger">
    <div class="mb-6">
        <a href="{{ route('admin.bookings.show', $booking->id) }}" 
           class="text-cyan-600 hover:text-cyan-800 transition group flex items-center gap-2 mt-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="text-sm font-medium group-hover:underline">Back to Booking</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gradient from-cyan-50 to-cyan-100/50 border-b border-cyan-100">
            <h3 class="text-lg font-semibold text-gray-900">Add Passenger</h3>
            <p class="text-sm text-gray-500">Booking: {{ $booking->booking_reference }} - Flight: {{ $booking->flight->flight_number }}</p>
        </div>

        <form action="{{ route('admin.passengers.store') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- First Name --}}
                <x-form.input 
                    name="first_name"
                    label="First Name"
                    placeholder="e.g. John"
                    :value="old('first_name')"
                    required
                />

                {{-- Last Name --}}
                <x-form.input 
                    name="last_name"
                    label="Last Name"
                    placeholder="e.g. Doe"
                    :value="old('last_name')"
                    required
                />

                {{-- Email --}}
                <x-form.input 
                    name="email"
                    label="Email"
                    type="email"
                    placeholder="john@example.com"
                    :value="old('email')"
                    required
                />

                {{-- Phone --}}
                <x-form.input 
                    name="phone"
                    label="Phone"
                    placeholder="+1-555-123-4567"
                    :value="old('phone')"
                />

                {{-- Date of Birth --}}
                <x-form.input 
                    name="date_of_birth"
                    label="Date of Birth"
                    type="date"
                    :value="old('date_of_birth')"
                />

                {{-- Nationality --}}
                <x-form.input 
                    name="nationality"
                    label="Nationality"
                    placeholder="e.g. USA"
                    :value="old('nationality')"
                    required
                />

                {{-- Passport Number --}}
                <x-form.input 
                    name="passport_number"
                    label="Passport Number"
                    placeholder="e.g. AB123456"
                    :value="old('passport_number')"
                    required
                />

                {{-- ID Number --}}
                <x-form.input 
                    name="id_number"
                    label="ID Number"
                    placeholder="e.g. 1234-5678-9012"
                    :value="old('id_number')"
                    required
                />

                {{-- Seat Number --}}
                <x-form.input 
                    name="seat_number"
                    label="Seat Number"
                    placeholder="e.g. 12A"
                    :value="old('seat_number')"
                />

                {{-- Meal Preference --}}
                <x-form.select 
                    name="meal_preference"
                    label="Meal Preference"
                    :options="[
                        'standard' => 'Standard',
                        'full_meal' => 'Full Meal',
                        'sandwitch' => 'Sandwich',
                        'child_meal' => 'Child Meal',
                        'none' => 'No Meal',
                    ]"
                    selected="{{ old('meal_preference', 'standard') }}"
                />

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
                    selected="{{ old('status', 'pending') }}"
                />
            </div>

            {{-- Form Actions --}}
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition text-sm font-medium">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-cyan-600 text-white rounded-xl hover:bg-cyan-700 transition text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    Add Passenger
                </button>
            </div>
        </form>
    </div>
</x-layout>