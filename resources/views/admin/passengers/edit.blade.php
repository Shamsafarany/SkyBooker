{{-- resources/views/admin/passengers/edit.blade.php --}}

<x-layout title="Edit Passenger" header="Edit Passenger">
    <div class="mb-6">
        <a href="{{ route('admin.bookings.show', $passenger->booking_id) }}" 
           class="text-cyan-600 hover:text-cyan-800 transition group flex items-center gap-2 mt-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="text-sm font-medium group-hover:underline">Back to Booking</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gradient from-cyan-50 to-cyan-100/50 border-b border-cyan-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Edit Passenger</h3>
                <p class="text-sm text-gray-500">Booking: {{ $passenger->booking->booking_reference }} - Flight: {{ $passenger->booking->flight->flight_number }}</p>
            </div>
            @if($passenger->ticket)
                <span class="text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full border border-emerald-200">
                    <i class="fa-regular fa-ticket mr-1"></i>
                    Ticket: {{ $passenger->ticket->ticket_number }}
                </span>
            @endif
        </div>

        <form action="{{ route('admin.passengers.update', $passenger) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- First Name --}}
                <x-form.input 
                    name="first_name"
                    label="First Name"
                    placeholder="e.g. John"
                    :value="old('first_name', $passenger->first_name)"
                    required
                />

                {{-- Last Name --}}
                <x-form.input 
                    name="last_name"
                    label="Last Name"
                    placeholder="e.g. Doe"
                    :value="old('last_name', $passenger->last_name)"
                    required
                />

                {{-- Email --}}
                <x-form.input 
                    name="email"
                    label="Email"
                    type="email"
                    placeholder="john@example.com"
                    :value="old('email', $passenger->email)"
                    required
                />

                {{-- Phone --}}
                <x-form.input 
                    name="phone"
                    label="Phone"
                    placeholder="+1-555-123-4567"
                    :value="old('phone', $passenger->phone)"
                />

                {{-- Date of Birth --}}
                <x-form.input 
                    name="date_of_birth"
                    label="Date of Birth"
                    type="date"
                    :value="old('date_of_birth', $passenger->date_of_birth ? date('Y-m-d', strtotime($passenger->date_of_birth)) : '')"
                />

                {{-- Nationality --}}
                <x-form.input 
                    name="nationality"
                    label="Nationality"
                    placeholder="e.g. USA"
                    :value="old('nationality', $passenger->nationality)"
                    required
                />

                {{-- Passport Number --}}
                <x-form.input 
                    name="passport_number"
                    label="Passport Number"
                    placeholder="e.g. AB123456"
                    :value="old('passport_number', $passenger->passport_number)"
                    required
                />

                {{-- ID Number --}}
                <x-form.input 
                    name="id_number"
                    label="ID Number"
                    placeholder="e.g. 1234-5678-9012"
                    :value="old('id_number', $passenger->id_number)"
                    required
                />

                {{-- Seat Number --}}
                <x-form.input 
                    name="seat_number"
                    label="Seat Number"
                    placeholder="e.g. 12A"
                    :value="old('seat_number', optional($passenger->ticket)->seat_number)"
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
                    selected="{{ old('meal_preference', $passenger->meal_preference) }}"
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
                    selected="{{ old('status', $passenger->status) }}"
                />
            </div>

            {{-- Ticket Info (Read-only) --}}
            @if($passenger->ticket)
                <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i class="fa-regular fa-ticket text-cyan-600"></i>
                        <span class="font-medium">Ticket Information:</span>
                        <span class="font-mono">{{ $passenger->ticket->ticket_number }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">
                            {{ $passenger->ticket->status }}
                        </span>
                        <span class="text-xs text-gray-400">| Class: {{ ucfirst($passenger->ticket->class) }}</span>
                        @if($passenger->ticket->seat_number)
                            <span class="text-xs text-gray-400">| Seat: {{ $passenger->ticket->seat_number }}</span>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Form Actions --}}
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.bookings.show', $passenger->booking_id) }}" 
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition text-sm font-medium">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-cyan-600 text-white rounded-xl hover:bg-cyan-700 transition text-sm font-medium flex items-center gap-2">
                    <i class="fa-regular fa-save"></i>
                    Update Passenger
                </button>
            </div>
        </form>
    </div>
</x-layout>