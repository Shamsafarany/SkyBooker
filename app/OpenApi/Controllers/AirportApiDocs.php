<?php

namespace App\OpenApi\Controllers;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Airports',
    description: 'API Endpoints for managing airports'
)]
class AirportApiDocs
{
    #[OA\Get(
        path: '/api/v1/airports',
        tags: ['Airports'],
        summary: 'List all airports',
        parameters: [
        new OA\QueryParameter(name: 'name', description: 'Filter by airport name', required: false),
        new OA\QueryParameter(name: 'code', description: 'Filter by airport code', required: false),
        new OA\QueryParameter(name: 'city', description: 'Filter by city', required: false),
        new OA\QueryParameter(name: 'country', description: 'Filter by country', required: false),
        new OA\QueryParameter(name: 'sort', description: 'Sort field', required: false),
        new OA\QueryParameter(name: 'direction', description: 'Sort direction (asc/desc)', required: false)
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of airports'
            )
        ]
    )]
    public static function index() {}

    #[OA\Post(
        path: '/api/v1/airports',
        tags: ['Airports'],
        summary: 'Create a new airport',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['code','name','city','country','terminals','status'],
                properties: [
                    new OA\Property(property: 'code', type: 'string', example: 'AMS'),
                    new OA\Property(property: 'name', type: 'string', example: 'Amsterdam Schiphol'),
                    new OA\Property(property: 'city', type: 'string', example: 'Amsterdam'),
                    new OA\Property(property: 'country', type: 'string', example: 'Netherlands'),
                    new OA\Property(property: 'terminals', type: 'integer', example: 7),
                    new OA\Property(property: 'status', type: 'string', example: 'active')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Airport created successfully')
        ]
    )]
    public static function store() {}

    #[OA\Get(
        path: '/api/v1/airports/{code}',
        tags: ['Airports'],
        summary: 'Get airport by code',
        parameters: [
            new OA\Parameter(
                name: 'code',
                description: 'Airport code (3 letters)',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', example: 'JFK')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Airport retrieved successfully'),
            new OA\Response(response: 404, description: 'Airport not found')
        ]
    )]
    public static function show() {}

    #[OA\Put(
        path: '/api/v1/airports/{code}',
        tags: ['Airports'],
        summary: 'Update an airport',
        parameters: [
            new OA\Parameter(
                name: 'code',
                description: 'Airport code (3 letters)',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', example: 'JFK')
            )
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'New Name'),
                    new OA\Property(property: 'city', type: 'string', example: 'New City'),
                    new OA\Property(property: 'country', type: 'string', example: 'New Country'),
                    new OA\Property(property: 'terminals', type: 'integer', example: 10),
                    new OA\Property(property: 'status', type: 'string', example: 'maintenance')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Airport updated successfully'),
            new OA\Response(response: 404, description: 'Airport not found')
        ]
    )]
    public static function update() {}

    #[OA\Delete(
        path: '/api/v1/airports/{code}',
        tags: ['Airports'],
        summary: 'Delete an airport',
        parameters: [
            new OA\Parameter(
                name: 'code',
                description: 'Airport code (3 letters)',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', example: 'JFK')
            )
        ],
        responses: [
            new OA\Response(response: 204, description: 'Airport deleted successfully'),
            new OA\Response(response: 404, description: 'Airport not found')
        ]
    )]
    public static function destroy() {}

    #[OA\Get(
        path: '/api/v1/airports/search',
        tags: ['Airports'],
        summary: 'Search airports',
        parameters: [
            new OA\Parameter(name: 'code', in: 'query', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'name', in: 'query', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'city', in: 'query', schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Search results')
        ]
    )]
    public static function search() {}

    #[OA\Get(
        path: '/api/v1/airports/{code}/flights',
        tags: ['Airports'],
        summary: 'Get all flights for an airport',
        parameters: [
            new OA\Parameter(
                name: 'code',
                description: 'Airport code (3 letters)',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', example: 'JFK')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of flights for the airport'),
            new OA\Response(response: 404, description: 'Airport not found')
        ]
    )]

    #[OA\Post(
    path: '/api/v1/airports/{id}/status',
    tags: ['Airports'],
    summary: 'Update airport status',
    parameters: [
        new OA\Parameter(
            name: 'id',
            description: 'Airport ID',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer', example: 12)
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
                    enum: ['active', 'inactive', 'maintenance', 'closed'],
                    example: 'maintenance'
                )
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Airport status updated'),
        new OA\Response(response: 422, description: 'Invalid status transition')
    ]
)]


    public static function flights() {}
}
