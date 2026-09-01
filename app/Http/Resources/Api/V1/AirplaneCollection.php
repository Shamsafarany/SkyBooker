<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AirplaneCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,

            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }

    public function with($request)
    {
        return [
            'status' => 'success',
            'message' => 'Airplanes retrieved successfully',
            'timestamp' => now()->toDateTimeString(),

            'links' => [
                'self' => url()->current(),
            ],
        ];
    }
}
