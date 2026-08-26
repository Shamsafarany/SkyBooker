<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\TicketResource;
use App\Http\Resources\Api\V1\TicketCollection;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['passenger'])->paginate(15);
        return new TicketCollection($tickets);
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create($request->validated());
        $ticket->load('passenger');
        return response()->json(
            new TicketResource($ticket),
            201
        );
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('passenger');
        return new TicketResource($ticket);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->validated());
        $ticket->load('passenger');
        return new TicketResource($ticket);
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return response()->json(null, 204);
    }
}
