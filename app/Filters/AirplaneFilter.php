<?php

namespace App\Filters;

use Illuminate\Http\Request;

class AirplaneFilter
{
    protected array $allowed = [
    'model',
    'manufacturer',
    'capacity',
    'sort',
    'direction'
];
    public function apply($query, Request $request)
    {
        foreach ($request->query() as $key => $value) {
            if (!in_array($key, $this->allowed)) {
                abort(400, "Unknown filter parameter: $key");
            }
        }
        
        if ($request->filled('model')) {
            $query->where('model', 'LIKE', '%' . $request->model . '%');
        }

        if ($request->filled('manufacturer')) {
            $query->where('manufacturer', 'LIKE', '%' . $request->manufacturer . '%');
        }

        if ($request->filled('capacity')) {
            $query->where('capacity', $request->capacity);
        }

        return $query;
    }
}
