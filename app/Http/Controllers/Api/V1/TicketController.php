<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\TicketResource;
use App\Http\Resources\Api\V1\TicketCollection;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Tickets',
    description: 'API Endpoints for managing tickets'
)]
class TicketController extends Controller
{
    #[OA\Get(
        path: '/api/v1/tickets',
        tags: ['Tickets'],
        summary: 'List all tickets',
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of tickets'
            )
        ]
    )]
    public function index()
    {
        $tickets = Ticket::with(['passenger'])->paginate(15);
        return new TicketCollection($tickets);
    }
    #[OA\Post(
    path: '/api/v1/tickets',
    tags: ['Tickets'],
    summary: 'Create a new ticket',
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: [
                'passenger_id',
                'first_name',
                'last_name',
                'email',
                'class',
                'status'
            ],
            properties: [
                new OA\Property(
                    property: 'passenger_id',
                    type: 'integer',
                    example: 1
                ),
                new OA\Property(
                    property: 'first_name',
                    type: 'string',
                    example: 'John'
                ),
                new OA\Property(
                    property: 'last_name',
                    type: 'string',
                    example: 'Doe'
                ),
                new OA\Property(
                    property: 'email',
                    type: 'string',
                    example: 'john@example.com'
                ),
                new OA\Property(
                    property: 'phone',
                    type: 'string',
                    example: '+1234567890',
                    nullable: true
                ),
                new OA\Property(
                    property: 'seat_number',
                    type: 'string',
                    example: '12A',
                    nullable: true
                ),
                new OA\Property(
                    property: 'class',
                    type: 'string',
                    example: 'economy'
                ),
                new OA\Property(
                    property: 'meal_preference',
                    type: 'string',
                    example: 'vegetarian',
                    nullable: true
                ),
                new OA\Property(
                    property: 'status',
                    type: 'string',
                    example: 'issued'
                ),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: 'Ticket created successfully'
        ),
        new OA\Response(
            response: 422,
            description: 'Validation error'
        )
    ]
)]
    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create($request->validated());
        $ticket->load('passenger');
        return response()->json(
            new TicketResource($ticket),
            201
        );
    }
    #[OA\Get(
        path: '/api/v1/tickets/{ticket}',
        tags: ['Tickets'],
        summary: 'Get ticket by ID',
        parameters: [
            new OA\Parameter(
                name: 'ticket',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Ticket retrieved successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Ticket not found'
            )
        ]
    )]
    public function show(Ticket $ticket)
    {
        $ticket->load('passenger');
        return new TicketResource($ticket);
    }

    #[OA\Put(
        path: '/api/v1/tickets/{ticket}',
        tags: ['Tickets'],
        summary: 'Update a ticket',
        parameters: [
            new OA\Parameter(
                name: 'ticket',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'used'),
                    new OA\Property(property: 'seat_number', type: 'string', example: '15A'),
                    new OA\Property(property: 'meal_preference', type: 'string', example: 'vegetarian'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Ticket updated successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Ticket not found'
            )
        ]
    )]
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->validated());
        $ticket->load('passenger');
        return new TicketResource($ticket);
    }

    #[OA\Delete(
        path: '/api/v1/tickets/{ticket}',
        tags: ['Tickets'],
        summary: 'Delete a ticket',
        parameters: [
            new OA\Parameter(
                name: 'ticket',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Ticket deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Ticket not found'
            )
        ]
    )]
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return response()->json(null, 204);
    }
}
