<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;


class FlightCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,

            'meta' => [
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
            ],

            'stats' => [
                'total_revenue' => $this->collection->sum('price'),
                'total_seats' => $this->collection->sum('total_seats'),
                'avg_price' => $this->collection->avg('price'),
            ],
        ];
    }

    public function with($request)
    {
        return [
            'status' => 'success',
            'message' => 'Flights retrieved successfully',
            'timestamp' => now()->toDateTimeString(),

            'links' => [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl(),
                'self' => $this->url($this->currentPage()),
            ],
        ];
    }
}

