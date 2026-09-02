<?php

namespace App\Filters;

use Illuminate\Http\Request;

class FlightFilter
{
    protected array $allowed = [
        'flight_number',
        'status',
        'origin',
        'destination',
        'sort',
        'direction',
        'paginate',
        'page',
        'per_page',
        'limit',
        'perPage'
    ];
    protected array $sortable = [
        'flight_number',
        'departure_date',
        'status',
        'origin_airport_id',
        'destination_airport_id',
        'origin',
        'destination'
    ];

    public function apply($query, Request $request)
    {
        foreach ($request->query() as $key => $value) {
            if (!in_array($key, $this->allowed)) {
                abort(400, "Unknown filter parameter: $key");
            }
        }
    
        if ($request->filled('flight_number')) {
            $query->where('flight_number', 'LIKE', '%' . $request->flight_number . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('origin')) {
            $query->whereHas('origin', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->origin . '%')
                ->orWhere('code', 'LIKE', '%' . $request->origin . '%');
            });
        }

        if ($request->filled('destination')) {
            $query->whereHas('destination', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->destination . '%')
                ->orWhere('code', 'LIKE', '%' . $request->destination . '%');
            });
        }

        if ($request->filled('sort')) {
            if (!in_array($request->sort, $this->sortable)) {
                abort(400, "Sorting by '{$request->sort}' is not allowed.");
            }
            $direction = $request->input('direction', 'asc');
            $query->orderBy($request->sort, $direction);
        }


        return $query;
    }
}
