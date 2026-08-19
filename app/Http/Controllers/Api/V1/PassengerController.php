<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\PassengerResource;
use App\Http\Requests\Passenger\StorePassengerRequest;
use App\Http\Requests\Passenger\UpdatePassengerRequest;
use App\Http\Resources\Api\V1\PassengerCollection;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Passengers',
    description: 'API Endpoints for managing Passengers'
)]

class PassengerController extends Controller
{
    #[OA\Get(
        path: '/api/v1/passengers',
        tags: ['Passengers'],
        summary: 'List all passengers',
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of passengers'
            )
        ]
    )]
    public function index()
    {
        $passengers = Passenger::with(['booking', 'ticket'])->paginate(15);
        return new PassengerCollection($passengers);
    }

    public function store(StorePassengerRequest $request)
    {
        //
    }

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
            new OA\Response(
                response: 200,
                description: 'Passenger retrieved successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Passenger not found'
            )
        ]
    )]
    public function show(Passenger $passenger)
    {
        $passenger->load(['booking', 'ticket']);
        return new PassengerResource($passenger);
    }

    public function update(UpdatePassengerRequest $request, Passenger $passenger)
    {
        $passenger->update($request->validated());
        $passenger->load(['booking', 'ticket']);
        return new PassengerResource($passenger);
    }

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
            new OA\Response(
                response: 200,
                description: 'Passenger deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Passenger not found'
            )
        ]
    )]
    public function destroy(Passenger $passenger)
    {
        $passenger->delete();
        return response()->json(null, 204);
    }
}
