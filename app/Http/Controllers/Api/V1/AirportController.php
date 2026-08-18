<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AirportResource;
use App\Models\Airport;
use App\Http\Requests\Airport\StoreAirportRequest;
use App\Http\Requests\Airport\UpdateAirportRequest;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Airports',
    description: 'API Endpoints for managing airports'
)]
class AirportController extends Controller
{
    #[OA\Get(
        path: '/api/v1/airports',
        tags: ['Airports'],
        summary: 'List all airports',
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of airports'
            )
        ]
    )]
    public function index()
    {
        $airports = Airport::all();

        return AirportResource::collection($airports);
    }

    #[OA\Post(
        path: '/api/v1/airports',
        tags: ['Airports'],
        summary: 'Create a new airport',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    'code',
                    'name',
                    'city',
                    'country',
                    'terminals',
                    'status'
                ],
                properties: [
                    new OA\Property(
                        property: 'code',
                        type: 'string',
                        example: 'AMS'
                    ),
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        example: 'Amsterdam Schiphol'
                    ),
                    new OA\Property(
                        property: 'city',
                        type: 'string',
                        example: 'Amsterdam'
                    ),
                    new OA\Property(
                        property: 'country',
                        type: 'string',
                        example: 'Netherlands'
                    ),
                    new OA\Property(
                        property: 'terminals',
                        type: 'integer',
                        example: 7
                    ),
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        example: 'active'
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Airport created successfully'
            )
        ]
    )]
    public function store(StoreAirportRequest $request)
    {
        $airport = Airport::create($request->validated());

        return response()->json(
            new AirportResource($airport),
            201
        );
    }

    #[OA\Get(
        path: '/api/v1/airports/{airport}',
        tags: ['Airports'],
        summary: 'Get airport by ID',
        parameters: [
            new OA\Parameter(
                name: 'airport',
                description: 'Airport ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Airport retrieved successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Airport not found'
            )
        ]
    )]
    public function show(Airport $airport)
    {
        return new AirportResource($airport);
    }

    #[OA\Put(
        path: '/api/v1/airports/{airport}',
        tags: ['Airports'],
        summary: 'Update an airport',
        parameters: [
            new OA\Parameter(
                name: 'airport',
                description: 'Airport ID',
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
                        property: 'name',
                        type: 'string',
                        example: 'New Name'
                    ),
                    new OA\Property(
                        property: 'city',
                        type: 'string',
                        example: 'New City'
                    ),
                    new OA\Property(
                        property: 'country',
                        type: 'string',
                        example: 'New Country'
                    ),
                    new OA\Property(
                        property: 'terminals',
                        type: 'integer',
                        example: 10
                    ),
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        example: 'maintenance'
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Airport updated successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Airport not found'
            )
        ]
    )]
    public function update(
        UpdateAirportRequest $request,
        Airport $airport
    ) {
        $airport->update($request->validated());

        return new AirportResource($airport);
    }

    #[OA\Delete(
        path: '/api/v1/airports/{airport}',
        tags: ['Airports'],
        summary: 'Delete an airport',
        parameters: [
            new OA\Parameter(
                name: 'airport',
                description: 'Airport ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Airport deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Airport not found'
            )
        ]
    )]
    public function destroy(Airport $airport)
    {
        $airport->delete();

        return response()->json(null, 204);
    }
}