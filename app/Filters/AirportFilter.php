<?php

namespace App\Filters;

use Illuminate\Http\Request;

class AirportFilter
{
    protected array $allowed = [
    'name',
    'code',
    'city',
    'country',
    'sort',
    'direction'
    ];

    protected array $sortable = [
        'code'
    ];

    public function apply($query, Request $request)
    {
        foreach ($request->query() as $key => $value) {
            if (!in_array($key, $this->allowed)) {
                abort(400, "Unknown filter parameter: $key");
            }
        }

    
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->filled('code')) {
            $query->where('code', 'LIKE', '%' . $request->code . '%');
        }

        if ($request->filled('city')) {
            $query->where('city', 'LIKE', '%' . $request->city . '%');
        }

        if ($request->filled('country')) {
            $query->where('country', 'LIKE', '%' . $request->country . '%');
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
