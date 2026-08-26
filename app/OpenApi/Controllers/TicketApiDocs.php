<?php

namespace App\OpenApi\Controllers;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Tickets',
    description: 'API Endpoints for managing tickets'
)]
class TicketApiDocs
{
    #[OA\Get(
        path: '/api/v1/tickets',
        tags: ['Tickets'],
        summary: 'List all tickets',
        responses: [
            new OA\Response(response: 200, description: 'List of tickets')
        ]
    )]
    public static function index() {}

    #[OA\Post(
        path: '/api/v1/tickets',
        tags: ['Tickets'],
        summary: 'Create a new ticket',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    'passenger_id','first_name','last_name','email','class','status'
                ],
                properties: [
                    new OA\Property(property: 'passenger_id', type: 'integer'),
                    new OA\Property(property: 'first_name', type: 'string'),
                    new OA\Property(property: 'last_name', type: 'string'),
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'phone', type: 'string', nullable: true),
                    new OA\Property(property: 'seat_number', type: 'string', nullable: true),
                    new OA\Property(property: 'class', type: 'string'),
                    new OA\Property(property: 'meal_preference', type: 'string', nullable: true),
                    new OA\Property(property: 'status', type: 'string'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Ticket created successfully'),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public static function store() {}

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
            new OA\Response(response: 200, description: 'Ticket retrieved successfully'),
            new OA\Response(response: 404, description: 'Ticket not found')
        ]
    )]
    public static function show() {}

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
                    new OA\Property(property: 'status', type: 'string'),
                    new OA\Property(property: 'seat_number', type: 'string'),
                    new OA\Property(property: 'meal_preference', type: 'string'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Ticket updated successfully'),
            new OA\Response(response: 404, description: 'Ticket not found')
        ]
    )]
    public static function update() {}

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
            new OA\Response(response: 204, description: 'Ticket deleted successfully'),
            new OA\Response(response: 404, description: 'Ticket not found')
        ]
    )]
    public static function destroy() {}
}
