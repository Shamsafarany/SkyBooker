<?php

namespace App\Filters;

use Illuminate\Http\Request;

class AirportFilter
{
    public function apply($query, Request $request)
    {
        if ($request->filled('code')) {
            $query->where('code', 'LIKE', '%' . $request->code . '%');
        }

        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->filled('city')) {
            $query->where('city', 'LIKE', '%' . $request->city . '%');
        }

        if ($request->filled('country')) {
            $query->where('country', 'LIKE', '%' . $request->country . '%');
        }
        if ($request->filled('sort')) {
            $direction = $request->get('direction', 'asc');

            // whitelist allowed sortable fields
            $sortable = ['name', 'city', 'created_at'];

            if (in_array($request->sort, $sortable)) {
                $query->orderBy($request->sort, $direction);
            }
        }


        return $query;
    }
}
