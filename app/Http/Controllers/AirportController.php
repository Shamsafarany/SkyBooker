<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airport;

class AirportController extends Controller
{
    public function index()
    {
        $airports = Airport::withCount(['departingFlights', 'arrivingFlights'])
            ->orderBy('name')
            ->get();

        return view('admin.airports.index', compact('airports'));
    }

    public function create()
    {
        //
        return view('admin.airports.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }
    public function destroy(string $id)
    {
        //
    }
}
