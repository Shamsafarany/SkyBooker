<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookingCollection extends ResourceCollection
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
                'total_revenue' => $this->collection->sum('total_price'),
                'total_bookings' => $this->collection->count(),
                'total_seats' => $this->collection->sum('number_of_seats'),
                'avg_price' => $this->collection->avg('total_price') ?? 0,
                'confirmed' => $this->collection->where('status', 'confirmed')->count(),
                'pending' => $this->collection->where('status', 'pending')->count(),
                'cancelled' => $this->collection->where('status', 'cancelled')->count(),
                'completed' => $this->collection->where('status', 'completed')->count(),
                'failed' => $this->collection->where('status', 'failed')->count(),
                'refunded' => $this->collection->where('status', 'refunded')->count(),
            ],
            'links' => [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl(),
                'self' => $this->url($this->currentPage()),
            ],
        ];
    }

    public function with($request)
    {
        return [
            'status' => 'success',
            'message' => 'Flights retrieved successfully',
            'timestamp' => now()->toDateTimeString(),
        ];
    }
    public function withResponse($request, $response)
    {
        $response->header('Accept', 'application/json');
        $response->header('X-API-Version', '1.0.0');
        $response->header('X-Response-Time', microtime(true) - LARAVEL_START);
    }
}
