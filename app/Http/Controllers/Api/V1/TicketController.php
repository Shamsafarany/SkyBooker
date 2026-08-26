<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Http\Resources\Api\V1\TicketResource;
use App\Http\Resources\Api\V1\TicketCollection;

class TicketController extends Controller
{
    public function index()
    {
        try {
            $tickets = Ticket::with(['passenger'])->paginate(15);
            Log::info('PASSENGER INDEX: Cache MISS - querying database');  
            return Response::success(new TicketCollection($tickets), 'Tickets retrieved');

        } catch (\Throwable $e) {
            Log::error('TICKET INDEX ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve tickets', 500);
        }
    }

    public function store(StoreTicketRequest $request)
    {
        try {
            $ticket = Ticket::create($request->validated());
            $ticket->load(['passenger']);

            return Response::success(new TicketResource($ticket), 'Ticket created', 201);

        } catch (\Throwable $e) {
            Log::error('TICKET STORE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to create ticket', 500);
        }
    }

    public function show(Ticket $ticket)
    {
        try {
            $key = "api.tickets.show.{$ticket->id}";

            $data = Cache::remember($key, 60, function () use ($ticket) {
                Log::info('TICKET SHOW: Cache MISS - querying database');

                $ticket->load(['passenger']);

                return (new TicketResource($ticket))->resolve();
            });

            Log::info('TICKET SHOW: Cache HIT - returning cached ticket');

            return Response::success($data, 'Ticket retrieved');

        } catch (\Throwable $e) {
            Log::error('TICKET SHOW ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve ticket', 500);
        }
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        try {
            $ticket->update($request->validated());
            $ticket->load(['passenger']);

            Cache::forget("api.tickets.show.{$ticket->id}");

            return Response::success(new TicketResource($ticket), 'Ticket updated');

        } catch (\Throwable $e) {
            Log::error('TICKET UPDATE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to update ticket', 500);
        }
    }

    public function destroy(Ticket $ticket)
    {
        try {
            $ticket->delete();
            Cache::forget("api.tickets.show.{$ticket->id}");

            return Response::success(null, 'Ticket deleted', 204);

        } catch (\Throwable $e) {
            Log::error('TICKET DELETE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to delete ticket', 500);
        }
    }
}
