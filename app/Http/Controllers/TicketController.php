<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function index()
    {
        //
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        $ticket = Ticket::with([
            'passenger',
            'passenger.booking',
            'passenger.booking.flight',
            'passenger.booking.flight.airline',
            'passenger.booking.flight.origin',
            'passenger.booking.flight.destination',
        ])->findOrFail($id);

        return view('admin.tickets.show', compact('ticket'));
    }
    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->back()->with('success', 'Ticket deleted successfully!');
    }
}
