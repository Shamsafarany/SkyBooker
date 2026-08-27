<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\Admin\TicketService;

class TicketController extends Controller
{
    public function __construct(private TicketService $ticketService) {}
    public function show(string $id)
    {
        $result = $this->ticketService->getAdminShow($id);

        return view('admin.tickets.show', [
            'ticket' => $result['ticket']
        ]);
    }

    public function destroy(Ticket $ticket)
    {
        $result = $this->ticketService->delete($ticket);
        if (!$result['success']) {
            return back()->with('error', 'Failed to delete ticket.');
        }
        return back()->with('success', 'Ticket deleted successfully!');
    }
    public function generatePDF(Ticket $ticket)
    {
        $pdf = $this->ticketService->generatePdf($ticket);

        return $pdf->download('ticket-' . $ticket->ticket_number . '.pdf');
    }
}
