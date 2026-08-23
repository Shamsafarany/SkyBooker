<x-layout title="Booking Archive" header="Booking Archive">
    <div class="mb-6">
        <a href="{{ route('admin.bookings.index') }}" 
            class="text-cyan-600 hover:text-cyan-800 transition group flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="text-sm font-medium group-hover:underline">Back to Bookings</span>
        </a>
    </div>

    {{-- Stats --}}
    <x-stats 
        title="Deleted Bookings Overview"
        :stats="[
            [
                'label' => 'Total Deleted',
                'value' => $stats['total_deleted'],
                'icon' => 'fa fa-trash-can',
                'color' => 'text-red-700',
                'bg' => 'bg-red-50',
                'icon_color' => 'text-red-500',
            ],
            [
                'label' => 'Deleted Today',
                'value' => $stats['today_deleted'],
                'icon' => 'fa fa-calendar-day',
                'color' => 'text-amber-700',
                'bg' => 'bg-amber-50',
                'icon_color' => 'text-amber-500',
            ],
            [
                'label' => 'Deleted This Week',
                'value' => $stats['this_week_deleted'],
                'icon' => 'fa fa-calendar-week',
                'color' => 'text-cyan-700',
                'bg' => 'bg-cyan-50',
                'icon_color' => 'text-cyan-500',
            ],
            [
                'label' => 'Deleted This Month',
                'value' => $stats['this_month_deleted'],
                'icon' => 'fa fa-calendar-alt',
                'color' => 'text-purple-700',
                'bg' => 'bg-purple-50',
                'icon_color' => 'text-purple-500',
            ],
        ]"
        :columns="4"
        class="mb-8"
    />

    {{-- Archive Table --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gradient from-red-50 to-red-100/50 border-b border-red-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fa-regular fa-trash-can text-red-600"></i>
                <h3 class="text-lg font-semibold text-gray-900">Deleted Bookings Archive</h3>
                <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">
                    {{ $bookings->total() }} total
                </span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.bookings.index') }}" 
                   class="text-sm text-cyan-600 hover:text-cyan-800 transition">
                    <i class="fa-regular fa-eye mr-1"></i> Active Bookings
                </a>
            </div>
        </div>

        @if($bookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Reference
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Customer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Flight
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Seats
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Deleted At
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-medium text-gray-900">
                                        {{ $booking->booking_reference }}
                                    </span>
                                    <span class="ml-2 text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">
                                        Deleted
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $booking->user->first_name ?? 'N/A' }} 
                                    {{ $booking->user->last_name ?? '' }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $booking->flight->flight_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $booking->number_of_seats }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    ${{ number_format($booking->total_price, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $booking->deleted_at->format('M d, Y H:i') }}
                                    <br>
                                    <span class="text-xs text-gray-400">
                                        {{ $booking->deleted_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-5">
                                        {{-- Restore --}}
                                        <form action="{{ route('admin.bookings.restore', $booking->id) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Restore this booking?')">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-emerald-600 hover:text-emerald-800 text-sm transition inline-flex items-center gap-1">
                                                <i class="fa fa-rotate-left"></i>
                                                Restore
                                            </button>
                                        </form>

                                        {{-- Force Delete --}}
                                        <form action="{{ route('admin.bookings.destroy', $booking->id) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('⚠️ Permanently delete this booking? This cannot be undone!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 text-sm transition inline-flex items-center gap-1">
                                                <i class="fa-regular fa-trash-can"></i>
                                                Permanent
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $bookings->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="px-6 py-12 text-center text-cyan-600">
                <i class="fa-regular fa-trash-can text-4xl mb-3 block"></i>
                <p class="text-gray-500">No deleted bookings found.</p>
                <a href="{{ route('admin.bookings.index') }}" 
                   class="mt-2 inline-block text-sm text-cyan-600 hover:text-cyan-800 font-medium transition">
                    <i class="fa fa-arrow-left mr-1"></i>
                    Back to Active Bookings
                </a>
            </div>
        @endif
    </div>
</x-layout>