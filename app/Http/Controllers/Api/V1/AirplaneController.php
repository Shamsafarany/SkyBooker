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
use App\Services\Admin\AirplaneService;

class AirplaneController extends Controller
{
    public function __construct(private AirplaneService $airplaneService) {}
    public function index()
    {
        try {
            $airplanes = $this->airplaneService->getApiList();
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
            $airplane = $this->airplaneService->create($request->validated());

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
            $data = $this->airplaneService->getApiShow($airplane);

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
            $airplane= $this->airplaneService->update($airplane,$request->validated());
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
            $this->airplaneService->delete($airplane);
            return Response::success(null, 'Airplane deleted', 204);

        } catch (\Throwable $e) {
            Log::error('AIRPLANE DELETE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to delete airplane', 500);
        }
    }
}
