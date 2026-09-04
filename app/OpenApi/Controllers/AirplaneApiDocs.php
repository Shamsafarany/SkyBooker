<?php

namespace App\OpenApi\Controllers;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Airplanes',
    description: 'API Endpoints for managing Airplanes'
)]
class AirplaneApiDocs
{
    #[OA\Get(
        path: '/api/v1/airplanes',
        tags: ['Airplanes'],
        summary: 'List all Airplanes',
        parameters: [
        new OA\QueryParameter(name: 'model', description: 'Filter by airplane model', required: false),
        new OA\QueryParameter(name: 'manufacturer', description: 'Filter by manufacturer', required: false),
        new OA\QueryParameter(name: 'capacity', description: 'Filter by capacity', required: false),
        new OA\QueryParameter(name: 'sort', description: 'Sort field', required: false),
        new OA\QueryParameter(name: 'direction', description: 'Sort direction (asc/desc)', required: false),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of Airplanes')
        ]
    )]
    public static function index() {}

    #[OA\Post(
        path: '/api/v1/airplanes',
        tags: ['Airplanes'],
        summary: 'Create a new Airplane',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['model','manufacturer','registration','capacity','year','status'],
                properties: [
                    new OA\Property(property: 'model', type: 'string', example: 'Boeing 737-800'),
                    new OA\Property(property: 'manufacturer', type: 'string', example: 'Boeing'),
                    new OA\Property(property: 'registration', type: 'string', example: 'N12345'),
                    new OA\Property(property: 'capacity', type: 'integer', example: 189),
                    new OA\Property(property: 'year', type: 'integer', example: 2020),
                    new OA\Property(property: 'status', type: 'string', example: 'active')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Airplane created successfully')
        ]
    )]
    public static function store() {}

    #[OA\Get(
        path: '/api/v1/airplanes/{airplane}',
        tags: ['Airplanes'],
        summary: 'Get Airplane by ID',
        parameters: [
            new OA\Parameter(
                name: 'airplane',
                description: 'Airplane ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Airplane retrieved successfully'),
            new OA\Response(response: 404, description: 'Airplane not found')
        ]
    )]
    public static function show() {}

    #[OA\Put(
        path: '/api/v1/airplanes/{airplane}',
        tags: ['Airplanes'],
        summary: 'Update an Airplane',
        parameters: [
            new OA\Parameter(
                name: 'airplane',
                description: 'Airplane ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'model', type: 'string'),
                    new OA\Property(property: 'manufacturer', type: 'string'),
                    new OA\Property(property: 'registration', type: 'string'),
                    new OA\Property(property: 'capacity', type: 'integer'),
                    new OA\Property(property: 'year', type: 'integer'),
                    new OA\Property(property: 'status', type: 'string')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Airplane updated successfully'),
            new OA\Response(response: 404, description: 'Airplane not found')
        ]
    )]
    public static function update() {}

    #[OA\Delete(
        path: '/api/v1/airplanes/{airplane}',
        tags: ['Airplanes'],
        summary: 'Delete an Airplane',
        parameters: [
            new OA\Parameter(
                name: 'airplane',
                description: 'Airplane ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 204, description: 'Airplane deleted successfully'),
            new OA\Response(response: 404, description: 'Airplane not found')
        ]
    )]

    #[OA\Post(
    path: '/api/v1/airplanes/{id}/status',
    tags: ['Airplanes'],
    summary: 'Update airplane status',
    parameters: [
        new OA\Parameter(
            name: 'id',
            description: 'Airplane ID',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer', example: 44)
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
                    enum: ['active', 'inactive', 'maintenance', 'retired'],
                    example: 'maintenance'
                )
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Airplane status updated'),
        new OA\Response(response: 422, description: 'Invalid status transition')
    ]
)]

    public static function destroy() {}
}
