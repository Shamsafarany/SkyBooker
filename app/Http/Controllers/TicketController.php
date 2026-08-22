<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

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
        Log::channel('booking')->info('Ticket deleted successfully', [
            'ticket_id' => $ticket->id,
            ]);
        return redirect()->back()->with('success', 'Ticket deleted successfully!');
    }
    public function generatePDF(Ticket $ticket)
    {
        $ticket->load('passenger.booking.flight');
        
        $pdf = Pdf::loadView('admin.tickets.pdf', compact('ticket'));

        Log::channel('booking')->info('Ticket pdf generated', [
            'ticket_id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
        ]);
        
        return $pdf->download('ticket-' . $ticket->ticket_number . '.pdf');
    }
}
