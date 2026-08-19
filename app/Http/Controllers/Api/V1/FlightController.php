<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FlightCollection;
use App\Http\Resources\Api\V1\BookingCollection;
use App\Http\Resources\Api\V1\TicketCollection;
use App\Models\Flight;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\FlightResource;
use App\Http\Requests\Flight\StoreFlightRequest;
use App\Http\Requests\Flight\UpdateFlightRequest;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Flights',
    description: 'API Endpoints for managing Flights'
)]

class FlightController extends Controller
{

    #[OA\Get(
        path: '/api/v1/flights',
        tags: ['Flights'],
        summary: 'List all Flights with pagination',
        parameters: [
            new OA\Parameter(
                name: 'page',
                description: 'Page number',
                in: 'query',
                schema: new OA\Schema(type: 'integer', default: 1)
            ),
            new OA\Parameter(
                name: 'per_page',
                description: 'Items per page',
                in: 'query',
                schema: new OA\Schema(type: 'integer', default: 15)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of Flights with pagination'
            )
        ]
    )]
    public function index()
    {
        $flights = Flight::with([
        'airline',
        'origin',
        'destination',
        'airplane'
        ])->paginate(15);
        return new FlightCollection($flights);
    }

    #[OA\Post(
        path: '/api/v1/flights',
        tags: ['Flights'],
        summary: 'Create a new Flight',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    'flight_number',
                    'airline_id',
                    'origin_airport_id',
                    'destination_airport_id',
                    'airplane_id',
                    'departure_date',
                    'departure_time',
                    'arrival_date',
                    'arrival_time',
                    'duration',
                    'price',
                    'total_seats',
                    'status'
                ],
                properties: [
                    new OA\Property(
                        property: 'flight_number',
                        type: 'string',
                        example: 'AA123'
                    ),
                    new OA\Property(
                        property: 'airline_id',
                        type: 'integer',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'origin_airport_id',
                        type: 'integer',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'destination_airport_id',
                        type: 'integer',
                        example: 2
                    ),
                    new OA\Property(
                        property: 'airplane_id',
                        type: 'integer',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'departure_date',
                        type: 'string',
                        format: 'date',
                        example: '2026-08-20'
                    ),
                    new OA\Property(
                        property: 'departure_time',
                        type: 'string',
                        format: 'time',
                        example: '10:00:00'
                    ),
                    new OA\Property(
                        property: 'arrival_date',
                        type: 'string',
                        format: 'date',
                        example: '2026-08-20'
                    ),
                    new OA\Property(
                        property: 'arrival_time',
                        type: 'string',
                        format: 'time',
                        example: '14:00:00'
                    ),
                    new OA\Property(
                        property: 'duration',
                        type: 'string',
                        example: '4h 30m'
                    ),
                    new OA\Property(
                        property: 'price',
                        type: 'number',
                        format: 'float',
                        example: 499.99
                    ),
                    new OA\Property(
                        property: 'total_seats',
                        type: 'integer',
                        example: 189
                    ),
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        example: 'scheduled',
                        enum: ['scheduled', 'open', 'closing', 'completed', 'cancelled', 'delayed', 'boarding', 'departed']
                    ),
                    new OA\Property(
                        property: 'booking_deadline',
                        type: 'string',
                        format: 'date',
                        example: '2026-08-19',
                        nullable: true
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Flight created successfully'
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            )
        ]
    )]
    public function store(StoreFlightRequest $request)
    {
        return response()->json(
            new FlightResource(Flight::create($request->validated())),
            201
        );
    }

    #[OA\Get(
        path: '/api/v1/flights/{flight}',
        tags: ['Flights'],
        summary: 'Get Flight by ID',
        parameters: [
            new OA\Parameter(
                name: 'flight',
                description: 'Flight ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Flight retrieved successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Flight not found'
            )
        ]
    )]
    public function show(Flight $flight)
    {
        $flight->load([
        'airline',
        'origin',
        'destination',
        'airplane'
    ]);
        return new FlightResource($flight);
    }

    #[OA\Put(
        path: '/api/v1/flights/{flight}',
        tags: ['Flights'],
        summary: 'Update a Flight',
        parameters: [
            new OA\Parameter(
                name: 'flight',
                description: 'Flight ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'flight_number',
                        type: 'string',
                        example: 'AA456'
                    ),
                    new OA\Property(
                        property: 'airline_id',
                        type: 'integer',
                        example: 2
                    ),
                    new OA\Property(
                        property: 'origin_airport_id',
                        type: 'integer',
                        example: 3
                    ),
                    new OA\Property(
                        property: 'destination_airport_id',
                        type: 'integer',
                        example: 4
                    ),
                    new OA\Property(
                        property: 'airplane_id',
                        type: 'integer',
                        example: 2
                    ),
                    new OA\Property(
                        property: 'departure_date',
                        type: 'string',
                        format: 'date',
                        example: '2026-08-21'
                    ),
                    new OA\Property(
                        property: 'departure_time',
                        type: 'string',
                        format: 'time',
                        example: '11:00:00'
                    ),
                    new OA\Property(
                        property: 'arrival_date',
                        type: 'string',
                        format: 'date',
                        example: '2026-08-21'
                    ),
                    new OA\Property(
                        property: 'arrival_time',
                        type: 'string',
                        format: 'time',
                        example: '15:00:00'
                    ),
                    new OA\Property(
                        property: 'duration',
                        type: 'string',
                        example: '4h 00m'
                    ),
                    new OA\Property(
                        property: 'price',
                        type: 'number',
                        format: 'float',
                        example: 599.99
                    ),
                    new OA\Property(
                        property: 'total_seats',
                        type: 'integer',
                        example: 200
                    ),
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        example: 'boarding',
                        enum: ['scheduled', 'open', 'closing', 'completed', 'cancelled', 'delayed', 'boarding', 'departed']
                    ),
                    new OA\Property(
                        property: 'booking_deadline',
                        type: 'string',
                        format: 'date',
                        example: '2026-08-20',
                        nullable: true
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Flight updated successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Flight not found'
            )
        ]
    )]
    public function update(UpdateFlightRequest $request, Flight $flight)
    {
        $flight->update($request->validated());
        return new FlightResource($flight);
    }

    #[OA\Delete(
        path: '/api/v1/flights/{flight}',
        tags: ['Flights'],
        summary: 'Delete a Flight',
        parameters: [
            new OA\Parameter(
                name: 'flight',
                description: 'Flight ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Flight deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Flight not found'
            )
        ]
    )]
    public function destroy(Flight $flight)
    {
        $flight->delete();
        return response()->json(null, 204);
    }

    #[OA\Get(
        path: '/api/v1/flights/{flight}/bookings',
        tags: ['Flights'],
        summary: 'Get all bookings for a flight',
        parameters: [
            new OA\Parameter(
                name: 'flight',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of bookings for the flight'
            ),
            new OA\Response(
                response: 404,
                description: 'Flight not found'
            )
        ]
    )]
    public function bookings(Flight $flight)
    {
        //Eager load relationships
        $bookings = $flight->bookings()
            ->paginate(15);
        
        return new BookingCollection($bookings);
    }

    #[OA\Get(
    path: '/api/v1/flights/{flight}/tickets',
    tags: ['Flights'],
    summary: 'Get all tickets for a flight',
    parameters: [
        new OA\Parameter(
            name: 'flight',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer')
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'List of tickets for the flight'
        ),
        new OA\Response(
            response: 404,
            description: 'Flight not found'
        )
    ]
)]
    public function tickets(Flight $flight)
    {
        // Get all tickets through bookings → passengers
        $tickets = Ticket::whereHas('passenger.booking', function ($query) use ($flight) {
            $query->where('flight_id', $flight->id);
        })->with(['passenger'])->paginate(15);
        
        return new TicketCollection($tickets);
    }
}
