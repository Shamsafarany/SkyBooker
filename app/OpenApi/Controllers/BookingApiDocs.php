<?php

namespace App\OpenApi\Controllers;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Bookings',
    description: 'API Endpoints for managing Bookings'
)]
class BookingApiDocs
{
    #[OA\Get(
        path: '/api/v1/bookings',
        tags: ['Bookings'],
        summary: 'List all bookings with pagination',
        parameters: [
        new OA\QueryParameter(name: 'reference', description: 'Filter by booking reference', required: false),
        new OA\QueryParameter(name: 'status', description: 'Filter by booking status', required: false),
        new OA\QueryParameter(name: 'flight_id', description: 'Filter by flight ID', required: false),
        new OA\QueryParameter(name: 'user_id', description: 'Filter by user ID', required: false),
        new OA\QueryParameter(name: 'sort', description: 'Sort field', required: false),
        new OA\QueryParameter(name: 'direction', description: 'Sort direction (asc/desc)', required: false),
        new OA\QueryParameter(name: 'paginate', description: 'Items per page', required: false),
        new OA\QueryParameter(name: 'page', description: 'Page number', required: false),
        new OA\QueryParameter(name: 'per_page', description: 'Items per page (alias)', required: false),
        new OA\QueryParameter(name: 'limit', description: 'Items per page (alias)', required: false),
        new OA\QueryParameter(name: 'perPage', description: 'Items per page (alias)', required: false),
    ],
        responses: [
            new OA\Response(response: 200, description: 'List of bookings with pagination')
        ]
    )]
    public static function index() {}

    #[OA\Post(
        path: '/api/v1/bookings',
        tags: ['Bookings'],
        summary: 'Create a new booking',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['user_id','flight_id','number_of_seats','total_price','status','passengers'],
                properties: [
                    new OA\Property(property: 'user_id', type: 'integer'),
                    new OA\Property(property: 'flight_id', type: 'integer'),
                    new OA\Property(property: 'number_of_seats', type: 'integer'),
                    new OA\Property(property: 'total_price', type: 'number'),
                    new OA\Property(property: 'status', type: 'string'),
                    new OA\Property(property: 'notes', type: 'string', nullable: true),
                    new OA\Property(property: 'special_requests', type: 'string', nullable: true),
                    new OA\Property(
                        property: 'passengers',
                        type: 'array',
                        items: new OA\Items(
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
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Booking created successfully'),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public static function store() {}

    #[OA\Get(
        path: '/api/v1/bookings/{booking}',
        tags: ['Bookings'],
        summary: 'Get booking by ID',
        parameters: [
            new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Booking retrieved successfully'),
            new OA\Response(response: 404, description: 'Booking not found')
        ]
    )]
    public static function show() {}

    #[OA\Put(
        path: '/api/v1/bookings/{booking}',
        tags: ['Bookings'],
        summary: 'Update a booking',
        parameters: [
            new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'user_id', type: 'integer'),
                    new OA\Property(property: 'flight_id', type: 'integer'),
                    new OA\Property(property: 'number_of_seats', type: 'integer'),
                    new OA\Property(property: 'total_price', type: 'number'),
                    new OA\Property(property: 'status', type: 'string'),
                    new OA\Property(property: 'notes', type: 'string'),
                    new OA\Property(property: 'special_requests', type: 'string'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Booking updated successfully')
        ]
    )]
    public static function update() {}

    #[OA\Delete(
        path: '/api/v1/bookings/{booking}',
        tags: ['Bookings'],
        summary: 'Delete a booking',
        parameters: [
            new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 204, description: 'Booking deleted successfully'),
            new OA\Response(response: 404, description: 'Booking not found')
        ]
    )]
    public static function destroy() {}

    #[OA\Get(
        path: '/api/v1/bookings/{booking}/passengers',
        tags: ['Bookings'],
        summary: 'Get all passengers for a booking',
        parameters: [
            new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of passengers for the booking')
        ]
    )]
    public static function passengers() {}

    #[OA\Get(
        path: '/api/v1/bookings/{booking}/tickets',
        tags: ['Bookings'],
        summary: 'Get all tickets for a booking',
        parameters: [
            new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of tickets for the booking')
        ]
    )]
    public static function tickets() {}
}
