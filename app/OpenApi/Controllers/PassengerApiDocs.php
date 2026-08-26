<?php

namespace App\OpenApi\Controllers;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Passengers',
    description: 'API Endpoints for managing Passengers'
)]
class PassengerApiDocs
{
    #[OA\Get(
        path: '/api/v1/passengers',
        tags: ['Passengers'],
        summary: 'List all passengers',
        responses: [
            new OA\Response(response: 200, description: 'List of passengers')
        ]
    )]
    public static function index() {}

    #[OA\Post(
        path: '/api/v1/passengers',
        tags: ['Passengers'],
        summary: 'Create a new passenger',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    'booking_id','first_name','last_name','email',
                    'nationality','passport_number','id_number'
                ],
                properties: [
                    new OA\Property(property: 'booking_id', type: 'integer'),
                    new OA\Property(property: 'first_name', type: 'string'),
                    new OA\Property(property: 'last_name', type: 'string'),
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'phone', type: 'string', nullable: true),
                    new OA\Property(property: 'date_of_birth', type: 'string', nullable: true),
                    new OA\Property(property: 'nationality', type: 'string'),
                    new OA\Property(property: 'passport_number', type: 'string'),
                    new OA\Property(property: 'id_number', type: 'string'),
                    new OA\Property(property: 'seat_number', type: 'string', nullable: true),
                    new OA\Property(property: 'meal_preference', type: 'string', nullable: true),
                    new OA\Property(property: 'status', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Passenger created successfully'),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public static function store() {}

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
            new OA\Response(response: 200, description: 'Passenger retrieved successfully'),
            new OA\Response(response: 404, description: 'Passenger not found')
        ]
    )]
    public static function show() {}

    #[OA\Put(
        path: '/api/v1/passengers/{passenger}',
        tags: ['Passengers'],
        summary: 'Update a passenger',
        parameters: [
            new OA\Parameter(
                name: 'passenger',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'first_name', type: 'string'),
                    new OA\Property(property: 'last_name', type: 'string'),
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'phone', type: 'string', nullable: true),
                    new OA\Property(property: 'date_of_birth', type: 'string', nullable: true),
                    new OA\Property(property: 'nationality', type: 'string'),
                    new OA\Property(property: 'passport_number', type: 'string'),
                    new OA\Property(property: 'id_number', type: 'string'),
                    new OA\Property(property: 'seat_number', type: 'string', nullable: true),
                    new OA\Property(property: 'meal_preference', type: 'string', nullable: true),
                    new OA\Property(property: 'status', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Passenger updated successfully')
        ]
    )]
    public static function update() {}

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
            new OA\Response(response: 204, description: 'Passenger deleted successfully'),
            new OA\Response(response: 404, description: 'Passenger not found')
        ]
    )]
    public static function destroy() {}

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
            new OA\Response(response: 200, description: 'Ticket for the passenger'),
            new OA\Response(response: 404, description: 'Passenger not found')
        ]
    )]
    public static function ticket() {}
}
