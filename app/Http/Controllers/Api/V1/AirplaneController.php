<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Airplane;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\AirplaneResource;
use App\Http\Requests\Airplane\StoreAirplaneRequest;
use App\Http\Requests\Airplane\UpdateAirplaneRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
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
        $airplanes = Cache::remember('api.airplanes.list', 60, function () {
        Log::info('AIRPLANE INDEX: Cache MISS - querying database');
        $airplanes = Airplane::all();
            return AirplaneResource::collection($airplanes)->resolve();
        });
        Log::info('AIRPLANE INDEX: Cache HIT - getting cache');
        return Response::success($airplanes, 'Airplanes retrieved');
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
        Cache::forget('api.airplanes.list');
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
        $key = "api.airplanes.{$airplane->id}";
        $data = Cache::remember($key, 60, function () use ($airplane) {
            Log::info('AIRPLANE INDEX: Cache MISS - querying database');
            return (new AirplaneResource($airplane))->resolve();
        });
        Log::info('AIRPLANE INDEX: Cache HIT - getting cache');
        return Response::success($data, 'Airplane retrieved');
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
        Cache::forget('api.airplanes.list');
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
        Cache::forget('api.airplanes.list');
        return response()->json(null, 204);
    }
}