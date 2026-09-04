<?php

namespace App\OpenApi\Controllers;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Flights',
    description: 'API Endpoints for managing Flights'
)]
class FlightApiDocs
{
    #[OA\Get(
        path: '/api/v1/flights',
        tags: ['Flights'],
        summary: 'List all Flights',
        parameters: [
        new OA\QueryParameter(name: 'flight_number', description: 'Filter by flight number', required: false),
        new OA\QueryParameter(name: 'status', description: 'Filter by flight status', required: false),
        new OA\QueryParameter(name: 'origin', description: 'Filter by origin airport name or code', required: false),
        new OA\QueryParameter(name: 'destination', description: 'Filter by destination airport name or code', required: false),
        new OA\QueryParameter(name: 'sort', description: 'Sort field', required: false),
        new OA\QueryParameter(name: 'direction', description: 'Sort direction (asc/desc)', required: false),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of Flights with pagination')
        ]
    )]
    public static function index() {}

    #[OA\Post(
        path: '/api/v1/flights',
        tags: ['Flights'],
        summary: 'Create a new Flight',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    'flight_number','airline_id','origin_airport_id','destination_airport_id',
                    'airplane_id','departure_date','departure_time','arrival_date','arrival_time',
                    'duration','price','total_seats','status'
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Flight created successfully')
        ]
    )]
    public static function store() {}

    #[OA\Get(
        path: '/api/v1/flights/{flight}',
        tags: ['Flights'],
        summary: 'Get Flight by ID',
        parameters: [
            new OA\Parameter(name: 'flight', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Flight retrieved successfully'),
            new OA\Response(response: 404, description: 'Flight not found')
        ]
    )]
    public static function show() {}

    #[OA\Put(
        path: '/api/v1/flights/{flight}',
        tags: ['Flights'],
        summary: 'Update a Flight',
        parameters: [
            new OA\Parameter(name: 'flight', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Flight updated successfully'),
            new OA\Response(response: 404, description: 'Flight not found')
        ]
    )]
    public static function update() {}

    #[OA\Delete(
        path: '/api/v1/flights/{flight}',
        tags: ['Flights'],
        summary: 'Delete a Flight',
        parameters: [
            new OA\Parameter(name: 'flight', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 204, description: 'Flight deleted successfully'),
            new OA\Response(response: 404, description: 'Flight not found')
        ]
    )]
    public static function destroy() {}

    #[OA\Get(
        path: '/api/v1/flights/{flight}/bookings',
        tags: ['Flights'],
        summary: 'Get all bookings for a flight',
        parameters: [
            new OA\Parameter(name: 'flight', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of bookings for the flight')
        ]
    )]
    public static function bookings() {}

    #[OA\Get(
        path: '/api/v1/flights/{flight}/tickets',
        tags: ['Flights'],
        summary: 'Get all tickets for a flight',
        parameters: [
            new OA\Parameter(name: 'flight', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of tickets for the flight')
        ]
    )]

    #[OA\Post(
    path: '/api/v1/flights/{id}/status',
    tags: ['Flights'],
    summary: 'Update flight status',
    parameters: [
        new OA\Parameter(
            name: 'id',
            description: 'Flight ID',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer', example: 101)
        )
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['status'],
            properties: [
                new OA\Property(
                    property: 'status',
                    type: 'string',
                    enum: ['scheduled', 'open', 'closing', 'completed', 'cancelled', 'delayed'],
                    example: 'open'
                )
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Flight status updated'),
        new OA\Response(response: 422, description: 'Invalid status transition')
    ]
)]

    public static function tickets() {}
}
