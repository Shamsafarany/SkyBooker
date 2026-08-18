<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Airplane;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\AirplaneResource;
use App\Http\Requests\Airplane\StoreAirplaneRequest;
use App\Http\Requests\Airplane\UpdateAirplaneRequest;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Airplanes',
    description: 'API Endpoints for managing Airplanes'
)]

class AirplaneController extends Controller
{
    #[OA\Get(
        path: '/api/v1/airplanes',
        tags: ['Airplanes'],
        summary: 'List all Airplanes',
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of Airplanes'
            )
        ]
    )]    
    public function index()
    {
        return AirplaneResource::collection(Airplane::all());
    }

    #[OA\Post(
        path: '/api/v1/airplanes',
        tags: ['Airplanes'],
        summary: 'Create a new Airplane',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    'model',
                    'manufacturer',
                    'registration',
                    'capacity',
                    'year',
                    'status'
                ],
                properties: [
                    new OA\Property(
                        property: 'model',
                        type: 'string',
                        example: 'Boeing 737-800'
                    ),
                    new OA\Property(
                        property: 'manufacturer',
                        type: 'string',
                        example: 'Boeing'
                    ),
                    new OA\Property(
                        property: 'registration',
                        type: 'string',
                        example: 'N12345'
                    ),
                    new OA\Property(
                        property: 'capacity',
                        type: 'integer',
                        example: 189
                    ),
                    new OA\Property(
                        property: 'year',
                        type: 'integer',
                        example: 2020
                    ),
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        example: 'active',
                        enum: ['active', 'inactive', 'maintenance', 'retired']
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Airplane created successfully'
            )
        ]
    )]
    public function store(StoreAirplaneRequest $request)
    {
        return response()->json(
            new AirplaneResource(Airplane::create($request->validated())),
            201
        );
    }

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
            new OA\Response(
                response: 200,
                description: 'Airplane retrieved successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Airplane not found'
            )
        ]
    )]
    public function show(Airplane $airplane)
    {
        return new AirplaneResource($airplane);
    }

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
                    new OA\Property(
                        property: 'model',
                        type: 'string',
                        example: 'Boeing 737-900'
                    ),
                    new OA\Property(
                        property: 'manufacturer',
                        type: 'string',
                        example: 'Boeing'
                    ),
                    new OA\Property(
                        property: 'registration',
                        type: 'string',
                        example: 'N67890'
                    ),
                    new OA\Property(
                        property: 'capacity',
                        type: 'integer',
                        example: 200
                    ),
                    new OA\Property(
                        property: 'year',
                        type: 'integer',
                        example: 2022
                    ),
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        example: 'maintenance',
                        enum: ['active', 'inactive', 'maintenance', 'retired']
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Airplane updated successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Airplane not found'
            )
        ]
    )]
    public function update(UpdateAirplaneRequest $request, Airplane $airplane)
    {
        $airplane->update($request->validated());
        return new AirplaneResource($airplane);
    }

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
            new OA\Response(
                response: 204,
                description: 'Airplane deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Airplane not found'
            )
        ]
    )]
    public function destroy(Airplane $airplane)
    {
        $airplane->delete();
        return response()->json(null, 204);
    }
}