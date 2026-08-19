<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\PassengerResource;
use App\Http\Resources\Api\V1\TicketResource;
use App\Http\Requests\Passenger\StorePassengerRequest;
use App\Http\Requests\Passenger\UpdatePassengerRequest;
use App\Http\Resources\Api\V1\PassengerCollection;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Passengers',
    description: 'API Endpoints for managing Passengers'
)]

class PassengerController extends Controller
{
    #[OA\Get(
        path: '/api/v1/passengers',
        tags: ['Passengers'],
        summary: 'List all passengers',
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of passengers'
            )
        ]
    )]
    public function index()
    {
        $passengers = Passenger::with(['booking', 'ticket'])->paginate(15);
        return new PassengerCollection($passengers);
    }

        #[OA\Post(
        path: '/api/v1/passengers',
        tags: ['Passengers'],
        summary: 'Create a new passenger',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['booking_id', 'first_name', 'last_name', 'email', 'nationality', 'passport_number', 'id_number'],
                properties: [
                    new OA\Property(property: 'booking_id', type: 'integer', example: 1),
                    new OA\Property(property: 'first_name', type: 'string', example: 'John'),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Doe'),
                    new OA\Property(property: 'email', type: 'string', example: 'john@example.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '+1234567890', nullable: true),
                    new OA\Property(property: 'date_of_birth', type: 'string', example: '1990-01-01', nullable: true),
                    new OA\Property(property: 'nationality', type: 'string', example: 'USA'),
                    new OA\Property(property: 'passport_number', type: 'string', example: 'AB123456'),
                    new OA\Property(property: 'id_number', type: 'string', example: 'ID123456'),
                    new OA\Property(property: 'seat_number', type: 'string', example: '12A', nullable: true),
                    new OA\Property(property: 'meal_preference', type: 'string', example: 'vegetarian', nullable: true),
                    new OA\Property(property: 'status', type: 'string', example: 'pending', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Passenger created successfully'
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            )
        ]
    )]
    public function store(StorePassengerRequest $request)
    {
        $passenger = Passenger::create($request->validated());
        $passenger->load(['booking', 'ticket']);
        return response()->json(new PassengerResource($passenger), 201);
    }

    #[OA\Get(
        path: '/api/v1/passengers/{passenger}',
        tags: ['Passengers'],
        summary: 'Get passenger by ID',
        parameters: [
            new OA\Parameter(
                name: 'passenger',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Passenger retrieved successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Passenger not found'
            )
        ]
    )]
    public function show(Passenger $passenger)
    {
        $passenger->load(['booking', 'ticket']);
        return new PassengerResource($passenger);
    }

    public function update(UpdatePassengerRequest $request, Passenger $passenger)
    {
        $passenger->update($request->validated());
        $passenger->load(['booking', 'ticket']);
        return new PassengerResource($passenger);
    }

    #[OA\Delete(
        path: '/api/v1/passengers/{passenger}',
        tags: ['Passengers'],
        summary: 'Delete a passenger',
        parameters: [
            new OA\Parameter(
                name: 'passenger',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Passenger deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Passenger not found'
            )
        ]
    )]
    public function destroy(Passenger $passenger)
    {
        $passenger->delete();
        return response()->json(null, 204);
    }
    #[OA\Get(
    path: '/api/v1/passengers/{passenger}/ticket',
    tags: ['Passengers'],
    summary: 'Get ticket for a passenger',
    parameters: [
        new OA\Parameter(
            name: 'passenger',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer')
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Ticket for the passenger'
        ),
        new OA\Response(
            response: 404,
            description: 'Passenger not found'
        )
    ]
)]
public function ticket(Passenger $passenger)
{
    $ticket = $passenger->ticket()->with(['passenger'])->first();
    
    if (!$ticket) {
        return response()->json([
            'message' => 'No ticket found for this passenger'
        ], 404);
    }
    
    return new TicketResource($ticket);
}
}
