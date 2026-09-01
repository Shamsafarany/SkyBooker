<?php
namespace App\Filters;

use Illuminate\Http\Request;

class BookingFilter
{
    protected array $allowed = [
        'booking_reference',
        'status',
        'flight',
        'flight_id',
        'user_id',
        'sort',
        'direction',
        'paginate',
        'page',
        'per_page',
        'limit',
        'perPage'

    ];
    protected array $sortable = [
        'booking_reference',
        'user_id',
        'flight_id',
        'flight'
    ];


    public function apply($query, Request $request)
    {
        foreach ($request->query() as $key => $value) {
            if (!in_array($key, $this->allowed)) {
                abort(400, "Unknown filter parameter: $key");
            }
        }
    
        if ($request->filled('booking_reference')) {
            $query->where('booking_reference', 'LIKE', '%' . $request->booking_reference . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('flight')) {
            $query->whereHas('flight', function ($q) use ($request) {
                $q->where('flight_number', 'LIKE', '%' . $request->flight . '%');
            });
        }


        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
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
