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

class AirplaneController extends Controller
{ 
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

    public function store(StoreAirplaneRequest $request)
    {
        Cache::forget('api.airplanes.list');
        return response()->json(
            new AirplaneResource(Airplane::create($request->validated())),
            201
        );
    }

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

    public function update(UpdateAirplaneRequest $request, Airplane $airplane)
    {
        $airplane->update($request->validated());
        Cache::forget('api.airplanes.list');
        return new AirplaneResource($airplane);
    }

    public function destroy(Airplane $airplane)
    {
        $airplane->delete();
        Cache::forget('api.airplanes.list');
        return response()->json(null, 204);
    }
}