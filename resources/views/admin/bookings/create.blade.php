<x-layout title="Add Booking" header="Add Booking">
    <div class="mb-6">
        <a href="{{ route('admin.bookings.index') }}" 
            class="text-cyan-600 hover:text-cyan-800 transition group flex items-center gap-2 mt-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="text-sm font-medium group-hover:underline">Back to Bookings</span>
        </a>
    </div>

    <form action="{{ route('admin.bookings.store') }}" method="POST" id="booking-form">
        @csrf
        <div class="bg-white rounded-3xl shadow-xl border overflow-hidden">
            <div class="px-8 py-6 bg-gradient from-cyan-50 to-cyan-100/50 border-b">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-cyan-600 flex items-center justify-center shadow-lg shadow-cyan-600/30">
                        <i class="fa-solid fa-plus text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Add New Booking</h2>
                        <p class="text-sm text-gray-600">Enter the details of the new Booking</p>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-6">
                {{-- Booking Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Booking Reference --}}
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
                        :options="$flights->mapWithKeys(function($flight) {
                            return [
                                $flight->id => $flight->flight_number . ' (' . $flight->origin->code . ' → ' . $flight->destination->code . ')'
                            ];
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

                    {{-- Total Price --}}
                    <x-form.input 
                        name="total_price"
                        label="Total Price ($)"
                        type="number"
                        step="0.01"
                        icon="fa-dollar-sign"
                        readonly
                        id="total_price"
                        helper="Auto-calculated from flight"
                    />

                    {{-- Booking Date --}}
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

                    {{-- Add Passenger Button --}}
                    <div class="md:col-span-2 mt-2">
                        <button type="button" 
                                id="toggle-passenger-form"
                                class="inline-flex items-center gap-2 text-cyan-600 hover:text-cyan-700 font-medium transition group">
                            <i class="fa-regular fa-plus text-lg group-hover:scale-110 transition-transform"></i>
                            <span>Add Passengers to this Booking</span>
                            <i class="fa-solid fa-chevron-down text-sm transition-transform duration-300"></i>
                        </button>
                        <p class="text-xs text-gray-400 mt-1">Click to expand the passenger form below</p>
                    </div>
                </div>

                {{-- Passenger Sections (Hidden by default) --}}
                <div id="passenger-sections-wrapper" class="hidden mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center gap-3 mb-4">
                        <i class="fa-solid fa-users text-cyan-600 text-lg"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Passenger Details</h3>
                        <span class="text-sm text-gray-400" id="passenger-count-label">(1 passenger)</span>
                    </div>

                    <div id="passenger-sections" class="space-y-4">
                        {{-- Passenger 1 --}}
                        <div class="bg-gray-50 rounded-2xl border  overflow-hidden shadow-xl">
                            <div class="px-6 py-3 bg-gradient from-cyan-50 to-cyan-100/50 border-b border-cyan-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-xs">
                                        1
                                    </div>
                                    <h4 class="font-medium text-gray-800">Main Passenger</h4>
                                    <span class="text-xs text-gray-400">(Customer)</span>
                                </div>
                                <i class="fa-solid fa-user-check text-cyan-900"></i>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-form.input 
                                        name="passengers[0][first_name]"
                                        label="First Name"
                                        placeholder="e.g. John"
                                        :value="old('passengers.0.first_name', $selectedUser->first_name ?? '')"
                                        required
                                    />

                                    <x-form.input 
                                        name="passengers[0][last_name]"
                                        label="Last Name"
                                        placeholder="e.g. Doe"
                                        :value="old('passengers.0.last_name', $selectedUser->last_name ?? '')"
                                        required
                                    />

                                    <x-form.input 
                                        name="passengers[0][email]"
                                        label="Email"
                                        type="email"
                                        placeholder="john@example.com"
                                        :value="old('passengers.0.email', $selectedUser->email ?? '')"
                                        required
                                    />

                                    <x-form.input 
                                        name="passengers[0][phone]"
                                        label="Phone"
                                        placeholder="+1-555-123-4567"
                                        :value="old('passengers.0.phone', $selectedUser->phone ?? '')"
                                    />

                                    <x-form.input 
                                        name="passengers[0][date_of_birth]"
                                        label="Date of Birth"
                                        type="date"
                                        :value="old('passengers.0.date_of_birth', $selectedUser->date_of_birth ?? '')"
                                    />

                                    <x-form.input 
                                        name="passengers[0][nationality]"
                                        label="Nationality"
                                        placeholder="e.g. USA"
                                        required
                                    />

                                    <x-form.input 
                                        name="passengers[0][passport_number]"
                                        label="Passport Number"
                                        placeholder="e.g. AB123456"
                                        required
                                    />

                                    <x-form.input 
                                        name="passengers[0][id_number]"
                                        label="ID Number"
                                        placeholder="e.g. 1234-5678-9012"
                                        required
                                    />

                                    <x-form.input 
                                        name="passengers[0][seat_number]"
                                        label="Seat Number"
                                        placeholder="e.g. 12A"
                                    />

                                    <x-form.select 
                                        name="passengers[0][meal_preference]"
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

                                    <x-form.select 
                                        name="passengers[0][status]"
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
                            </div>
                        </div>
                    </div>

                    <button type="button" 
                            id="add-passenger-btn"
                            class="mt-4 text-sm text-cyan-600 hover:text-cyan-800 font-medium transition flex items-center gap-2">
                        <i class="fa-regular fa-plus"></i>
                        Add Another Passenger
                    </button>
                </div>

                {{-- Submit Buttons --}}
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <span>All fields marked with <span class="text-rose-500">*</span> are required</span>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.bookings.index') }}" 
                                class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-8 py-2.5 text-sm font-semibold text-white bg-cyan-700 hover:bg-cyan-600 rounded-xl transition-all duration-200 shadow-lg shadow-cyan-600/30 hover:shadow-xl hover:shadow-cyan-600/40 flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">
                                <i class="fa-solid fa-plus"></i>
                                Create Booking
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const flightSelect = document.getElementById('flight_id');
        const seatsInput = document.getElementById('number_of_seats');
        const totalPriceInput = document.getElementById('total_price');
        const passengerContainer = document.getElementById('passenger-sections');
        const passengerCountLabel = document.getElementById('passenger-count-label');
        const toggleBtn = document.getElementById('toggle-passenger-form');
        const passengerWrapper = document.getElementById('passenger-sections-wrapper');

        // Toggle passenger form visibility
        toggleBtn.addEventListener('click', function() {
            passengerWrapper.classList.toggle('hidden');
            const icon = this.querySelector('.fa-chevron-down');
            if (passengerWrapper.classList.contains('hidden')) {
                icon.style.transform = 'rotate(0deg)';
            } else {
                icon.style.transform = 'rotate(180deg)';
            }
        });

        // Get flight prices from the JSON
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

        // Generate passenger sections based on number of seats
        function generatePassengerSections() {
            const numSeats = parseInt(seatsInput.value) || 1;
            
            // Keep the first passenger (main customer)
            const firstPassenger = passengerContainer.querySelector('.bg-gray-50');
            
            // Remove all passenger sections except the first one
            while (passengerContainer.children.length > 1) {
                passengerContainer.removeChild(passengerContainer.lastChild);
            }

            // Update passenger count label
            passengerCountLabel.textContent = `(${numSeats} passenger${numSeats > 1 ? 's' : ''})`;

            // Add additional passenger sections
            for (let i = 1; i < numSeats; i++) {
                const passengerDiv = document.createElement('div');
                passengerDiv.className = 'bg-gray-50 rounded-2xl border border-gray-200 overflow-hidden';
                passengerDiv.innerHTML = `
                    <div class="px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-200/50 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold text-xs">
                                ${i + 1}
                            </div>
                            <h4 class="font-medium text-gray-800">Passenger ${i + 1}</h4>
                            <span class="text-xs text-gray-400">(Additional)</span>
                        </div>
                        <button type="button" 
                                class="text-rose-600 hover:text-rose-800 text-sm font-medium remove-passenger"
                                onclick="this.closest('.bg-gray-50').remove(); updatePassengerCount();">
                            <i class="fa-regular fa-trash mr-1"></i> Remove
                        </button>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">First Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="passengers[${i}][first_name]" 
                                       placeholder="e.g. John"
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:outline-none transition-all duration-200"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Last Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="passengers[${i}][last_name]" 
                                       placeholder="e.g. Doe"
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:outline-none transition-all duration-200"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-rose-500">*</span></label>
                                <input type="email" name="passengers[${i}][email]" 
                                       placeholder="john@example.com"
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:outline-none transition-all duration-200"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone</label>
                                <input type="text" name="passengers[${i}][phone]" 
                                       placeholder="+1-555-123-4567"
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:outline-none transition-all duration-200">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Date of Birth</label>
                                <input type="date" name="passengers[${i}][date_of_birth]" 
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:outline-none transition-all duration-200">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nationality <span class="text-rose-500">*</span></label>
                                <input type="text" name="passengers[${i}][nationality]" 
                                       placeholder="e.g. USA"
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:outline-none transition-all duration-200"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Passport Number <span class="text-rose-500">*</span></label>
                                <input type="text" name="passengers[${i}][passport_number]" 
                                       placeholder="e.g. AB123456"
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:outline-none transition-all duration-200"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">ID Number <span class="text-rose-500">*</span></label>
                                <input type="text" name="passengers[${i}][id_number]" 
                                       placeholder="e.g. 1234-5678-9012"
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:outline-none transition-all duration-200"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Seat Number</label>
                                <input type="text" name="passengers[${i}][seat_number]" 
                                       placeholder="e.g. 12A"
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:outline-none transition-all duration-200">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Meal Preference</label>
                                <select name="passengers[${i}][meal_preference]" 
                                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:outline-none transition-all duration-200 appearance-none">
                                    <option value="standard">Standard</option>
                                    <option value="vegetarian">Vegetarian</option>
                                    <option value="vegan">Vegan</option>
                                    <option value="gluten_free">Gluten Free</option>
                                    <option value="kosher">Kosher</option>
                                    <option value="halal">Halal</option>
                                    <option value="child_meal">Child Meal</option>
                                    <option value="none">No Meal</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
                                <select name="passengers[${i}][status]" 
                                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:outline-none transition-all duration-200 appearance-none">
                                    <option value="pending">⏳ Pending</option>
                                    <option value="confirmed">✅ Confirmed</option>
                                    <option value="checked_in">🛂 Checked In</option>
                                    <option value="boarded">🛫 Boarded</option>
                                    <option value="cancelled">❌ Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                `;
                passengerContainer.appendChild(passengerDiv);
            }
        }

        function updatePassengerCount() {
            const count = passengerContainer.querySelectorAll('.bg-gray-50').length;
            passengerCountLabel.textContent = `(${count} passenger${count > 1 ? 's' : ''})`;
        }

        // Event Listeners
        flightSelect.addEventListener('change', calculateTotalPrice);
        seatsInput.addEventListener('input', function() {
            calculateTotalPrice();
            generatePassengerSections();
        });

        // Add passenger button
        document.getElementById('add-passenger-btn').addEventListener('click', function() {
            const currentCount = passengerContainer.querySelectorAll('.bg-gray-50').length;
            const newCount = currentCount + 1;
            seatsInput.value = newCount;
            generatePassengerSections();
            calculateTotalPrice();
        });

        // Initial calculation and passenger generation
        setTimeout(() => {
            calculateTotalPrice();
            generatePassengerSections();
        }, 100);
    });
</script>