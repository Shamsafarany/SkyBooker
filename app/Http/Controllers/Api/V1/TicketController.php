<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Http\Resources\Api\V1\TicketResource;
use App\Http\Resources\Api\V1\TicketCollection;
use App\Services\Admin\TicketService;

class TicketController extends Controller
{
    public function __construct(private TicketService $ticketService) {}
    public function index()
    {
        try {
            $result = $this->ticketService->getApiList();
            if (!$result['success']) {
                return Response::error($result['message'], 422);
            }

            return Response::success(
                new TicketCollection($result['tickets']),
                'Tickets retrieved'
            );

        } catch (\Throwable $e) {
            Log::error('TICKET INDEX ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve tickets', 500);
        }
    }

    public function store(StoreTicketRequest $request)
    {
        try {
            $result = $this->ticketService->create($request->validated());

            if (!$result['success']) {
                return Response::error($result['message'], 422);
            }

            return Response::success(
                new TicketResource($result['ticket']),
                'Ticket created',
                201
            );

        } catch (\Throwable $e) {
            Log::error('TICKET STORE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to create ticket', 500);
        }
    }

    public function show(Ticket $ticket)
    {
        try {   
            $result = $this->ticketService->getApiShow($ticket);
            if (!$result['success']) {
                return Response::error($result['message'], 422);
            }
            return Response::success(
                $result['ticket'],
                'Ticket retrieved'
            );
        } catch (\Throwable $e) {
            Log::error('TICKET SHOW ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve ticket', 500);
        }
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        try {
            $result = $this->ticketService->update($ticket, $request->validated());

            if (!$result['success']) {
                return Response::error($result['message'], 422);
            }

            return Response::success(
                new TicketResource($result['ticket']),
                'Ticket updated'
            );

        } catch (\Throwable $e) {
            Log::error('TICKET UPDATE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to update ticket', 500);
        }
    }

    public function destroy(Ticket $ticket)
    {
        try {
            $this->ticketService->delete($ticket);
            return Response::success(null, 'Ticket deleted', 204);

        } catch (\Throwable $e) {
            Log::error('TICKET DELETE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to delete ticket', 500);
        }
    }
}
