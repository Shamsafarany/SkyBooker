<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\BookingChangeStatusRequest;
use App\Models\Flight;
use App\Models\User;
use App\Models\Booking;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\booking\UpdateBookingRequest;
use App\Http\Requests\SearchRequest;
use App\Services\Admin\BookingService;
use App\Services\Admin\SearchService;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService, private SearchService $searchService
    ) {}
    
    public function index()
    {
        $flights = $this->bookingService->getBookings();
        $stats = $this->bookingService->getBookingsWithStats();

        return view('admin.bookings.index', compact('flights', 'stats'));
    }

    public function create()
    {
        $users = User::orderBy('first_name')->get();
        $flights = Flight::with(['origin', 'destination'])
            ->where('departure_date', '>=', now())
            ->orderBy('departure_date')
            ->get();
        $bookingReference = Booking::generateReference();
        return view('admin.bookings.create', compact('users', 'flights', 'bookingReference'));
    }

    public function store(StoreBookingRequest $request)
    {
        $result = $this->bookingService->create($request->validated());

        if (!$result['success']) {
            return back()->with('error', $result['message'])->withInput();
        }

        return redirect()->route('admin.bookings.show', $result['booking'])
            ->with('success', 'Booking created successfully!');
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'flight', 'passengers.ticket']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function archive()
    {
        $bookings = Booking::onlyTrashed()
            ->with(['user', 'flight'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_deleted' => Booking::onlyTrashed()->count(),
            'today_deleted' => Booking::onlyTrashed()
                ->whereDate('deleted_at', today())
                ->count(),
            'this_week_deleted' => Booking::onlyTrashed()
                ->whereDate('deleted_at', '>=', now()->startOfWeek())
                ->count(),
            'this_month_deleted' => Booking::onlyTrashed()
                ->whereDate('deleted_at', '>=', now()->startOfMonth())
                ->count(),
        ];

        return view('admin.bookings.archive', compact('bookings', 'stats'));
    }

    public function edit(Booking $booking)
    {
        $users = User::orderBy('first_name')->get();
        $flights = Flight::with(['origin', 'destination'])
            ->where('departure_date', '>=', now())
            ->orderBy('departure_date')
            ->get();
        return view('admin.bookings.edit', compact(['booking', 'users', 'flights']));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $result = $this->bookingService->update($booking, $request->validated());

        if (!$result['success']) {
            return back()->with('error', $result['error'])->withInput();
        }

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking updated successfully!');
    }


    public function destroy($id)
    {
        $booking = Booking::withTrashed()->findOrFail($id);
        $result = $this->bookingService->delete($booking);

        if (!$result['success']) {
            return back()->with('error', $result['error']);
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully!');
    }

    public function restore(Booking $booking)
    {
        $result = $this->bookingService->restore($booking);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking restored successfully!');
    }

    public function search(SearchRequest $request){
        $results = $this->searchService->searchBookings($request->validated());
        $flights = $this->bookingService->getBookings();
        $stats = $this->bookingService->getBookingsWithStats();
            return view('admin.bookings.index',compact([
            'flights',
            'stats',
            'results'
        ]));
    }

    public function changeStatus(BookingChangeStatusRequest $request, Booking $booking)
    {
        $updated = $this->bookingService->changeStatus(
            $booking,
            $request->validated()['status']
        );

        if (! $updated) {
        return redirect()
            ->back()
            ->with('error', "Invalid status transition: {$booking->status} → {$request->status}");
    }

    return redirect()
        ->back()
        ->with('success', 'Booking status updated successfully.');
    }

}
