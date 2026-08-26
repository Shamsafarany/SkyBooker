<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Airplane;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Airplane\StoreAirplaneRequest;
use App\Http\Requests\Airplane\UpdateAirplaneRequest;
use App\Http\Resources\Api\V1\AirplaneResource;

class AirplaneController extends Controller
{
    public function index()
    {
        try {
            $airplanes = Cache::remember('api.airplanes.list', 60, function () {
                Log::info('AIRPLANE INDEX: Cache MISS - querying database');

                return AirplaneResource::collection(
                    Airplane::all()
                )->resolve();
            });

            Log::info('AIRPLANE INDEX: Cache HIT - getting cache');

            return Response::success($airplanes, 'Airplanes retrieved');

        } catch (\Throwable $e) {
            Log::error('AIRPLANE INDEX ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve airplanes', 500);
        }
    }

    public function store(StoreAirplaneRequest $request)
    {
        try {
            $airplane = Airplane::create($request->validated());

            return Response::success(
                new AirplaneResource($airplane),
                'Airplane created',
                201
            );

        } catch (\Throwable $e) {
            Log::error('AIRPLANE STORE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to create airplane', 500);
        }
    }

    public function show(Airplane $airplane)
    {
        try {
            $key = "api.airplanes.{$airplane->id}";

            $data = Cache::remember($key, 60, function () use ($airplane) {
                Log::info('AIRPLANE SHOW: Cache MISS - querying database');
                return (new AirplaneResource($airplane))->resolve();
            });

            Log::info('AIRPLANE SHOW: Cache HIT - getting cache');

            return Response::success($data, 'Airplane retrieved');

        } catch (\Throwable $e) {
            Log::error('AIRPLANE SHOW ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve airplane', 500);
        }
    }

    public function update(UpdateAirplaneRequest $request, Airplane $airplane)
    {
        try {
            $airplane->update($request->validated());
            return Response::success(
                new AirplaneResource($airplane),
                'Airplane updated'
            );

        } catch (\Throwable $e) {
            Log::error('AIRPLANE UPDATE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to update airplane', 500);
        }
    }

    public function destroy(Airplane $airplane)
    {
        try {
            $airplane->delete();
            return Response::success(null, 'Airplane deleted', 204);

        } catch (\Throwable $e) {
            Log::error('AIRPLANE DELETE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to delete airplane', 500);
        }
    }
}
