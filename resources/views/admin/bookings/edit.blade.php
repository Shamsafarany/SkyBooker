<x-layout title="Edit Booking" header="Edit Booking">
    <div class="mb-6">
        <a href="{{ route('admin.bookings.index') }}" 
            class="text-cyan-600 hover:text-cyan-800 transition group flex items-center gap-2 mt-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="text-sm font-medium group-hover:underline">Back to Bookings</span>
        </a>
    </div>

    <x-form.form-create
        title="Edit Booking: {{ $booking->booking_reference }}"
        subtitle="Edit booking info"
        action="{{ route('admin.bookings.update', $booking) }}"
        method="PUT"
        submitLabel="Update"
        cancelRoute="{{ route('admin.bookings.show', $booking) }}"
    >
        {{-- Booking Details --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Booking Reference (Auto-generated) --}}
            <x-form.input 
                name="booking_reference"
                label="Booking Reference"
                value="{{ old('booking_reference', $booking->booking_reference) }}"
                icon="fa-ticket"
                readonly
            />
            
            {{-- User (Customer) --}}
            <x-form.select 
                name="user_id"
                label="Customer"
                icon="fa-user"
                :options="$users->pluck('full_name', 'id')->toArray()"
                selected="{{ old('user_id', $booking->user_id) }}"
                required
            />
            
            {{-- Flight --}}
            <x-form.select 
                name="flight_id"
                label="Flight"
                icon="fa-plane"
                :options="$flights->mapWithKeys(function($flight) {
                    return [$flight->id => $flight->flight_number . ' (' . $flight->origin->code . ' → ' . $flight->destination->code . ')'];
                })->toArray()"
                selected="{{ old('flight_id', $booking->flight_id) }}"
                required
            />

            {{-- Number of Seats --}}
            <x-form.input 
                name="number_of_seats"
                label="Number of Seats"
                type="number"
                value="{{ old('number_of_seats', $booking->number_of_seats) }}"
                min="1"
                icon="fa-chair"
                required
                id="number_of_seats"
            />

            {{-- Total Price (Auto-calculated) --}}
            <x-form.input 
                name="total_price"
                label="Total Price ($)"
                type="number"
                value="{{ old('total_price', $booking->total_price) }}"
                step="0.01"
                icon="fa-dollar-sign"
                id="total_price"
                required
            />

            {{-- Booking Date --}}
            <x-form.input 
                name="booking_date"
                label="Booking Date"
                type="datetime-local"
                value="{{ old('booking_date', $booking->booking_date ? date('Y-m-d\TH:i', strtotime($booking->booking_date)) : '') }}"
                icon="fa-calendar"
                required
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
                selected="{{ old('status', $booking->status) }}"
                required
            />

            <div class="md:col-span-2">
                <x-form.textarea 
                    name="notes"
                    label="Notes"
                    value="{{ old('notes', $booking->notes) }}"
                    placeholder="Any additional notes about this booking..."
                    rows="3"
                />
            </div>

            <div class="md:col-span-2">
                <x-form.textarea 
                    name="special_requests"
                    label="Special Requests"
                    value="{{ old('special_requests', $booking->special_requests) }}"
                    placeholder="e.g. Wheelchair assistance, meal preferences, etc."
                    rows="3"
                />
            </div>
        </div>
    </x-form.form-create>
        {{-- Passenger List --}}
    <div class="mt-8 bg-white rounded-2xl shadow-xl border overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between bg-cyan-50/50">
            <h2 class="font-semibold text-cyan-800 flex items-center gap-2">
                <i class="fa-solid fa-users text-cyan-600"></i>
                Passengers
            </h2>
            <a href="{{ route('admin.passengers.create', ['booking_id' => $booking->id]) }}" 
            class="text-sm text-cyan-600 hover:text-cyan-800 font-medium transition inline-flex items-center gap-1">
            <i class="fa-regular fa-plus mr-1"></i>
            Add Passenger to Booking
            </a>
        </div>
        
        @if(count($booking->passengers)>0)
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
                        @foreach($booking->passengers as $passenger)
                            <tr class="border-b border-gray-50 hover:bg-cyan-50/30 transition">
                                <td class="px-6 py-3 font-medium text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-cyan-700 text-xs font-bold border border-cyan-200">
                                            {{ strtoupper(substr($passenger->getFullName(), 0, 1)) }}
                                        </div>
                                        {{ $passenger->getFullName()}}
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-gray-600">{{ $passenger->email}}</td>
                                <td class="px-6 py-3 font-mono font-bold text-cyan-700">{{ $passenger->ticket->seat_number ?$passenger->ticket->seat_number : 'N/A' }}</td>
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
                                <td class="px-6 py-3 text-right flex items-center justify-end gap-2">
                                   <a href="{{ route('admin.passengers.edit', $passenger) }}" class="text-cyan-600 hover:text-cyan-800 text-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.passengers.destroy', $passenger) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-rose-500 hover:text-rose-700 text-sm"
                                                    onclick="return confirm('Remove this passenger?')">
                                                Delete
                                            </button>
                                        </form>
                                </td>
                            </tr>    
                    </tbody>
                    @endforeach
                    </thead>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center text-cyan-400">
                <i class="fa-solid fa-users text-3xl mb-3 block"></i>
                <p>No passengers found for this booking.</p>
                <a href="{{ route('admin.passengers.create', ['booking_id' => $booking->id]) }}" 
                class="mt-2 inline-block text-sm text-cyan-600 hover:text-cyan-800 font-medium transition">
                    <i class="fa fa-user-plus mr-1"></i>
                    Add first passenger
                </a>
            </div>
        @endif
    </div>
</x-layout>