<x-layout title="Add Booking" header="Add Booking">
    <div class="mb-6">
        <a href="{{ route('admin.bookings.index') }}" 
            class="text-cyan-600 hover:text-cyan-800 transition group flex items-center gap-2 mt-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="text-sm font-medium group-hover:underline">Back to Bookings</span>
        </a>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
            <ul class="text-red-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                        id="flight_id"
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

                    <div class="md:col-span-2">
                        <x-form.textarea 
                            name="notes"
                            label="Notes"
                            placeholder="Any additional notes about this booking..."
                            rows="3"
                        />
                    </div>

                    <div class="md:col-span-2">
                        <x-form.textarea 
                            name="special_requests"
                            label="Special Requests"
                            placeholder="e.g. Wheelchair assistance, meal preferences, etc."
                            rows="3"
                        />
                    </div>

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
                </div>

                {{-- Note about passengers --}}
                <div class="p-4 bg-cyan-50 rounded-xl border border-cyan-200 mt-4">
                    <div class="flex items-start gap-3">
                        <i class="fa fa-circle-info text-cyan-600 text-lg mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-700">
                                Passengers can be added after booking creation
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                You'll be able to add passengers from the booking details page
                            </p>
                        </div>
                    </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const flightSelect = document.getElementById('flight_id');
            const seatsInput = document.getElementById('number_of_seats');
            const totalPriceInput = document.getElementById('total_price');

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

            // Event Listeners
            flightSelect.addEventListener('change', calculateTotalPrice);
            seatsInput.addEventListener('input', calculateTotalPrice);

            // Initial calculation
            setTimeout(calculateTotalPrice, 100);
        });
    </script>
</x-layout>