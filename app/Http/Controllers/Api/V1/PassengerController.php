<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\PassengerResource;
use App\Http\Resources\Api\V1\TicketResource;
use App\Http\Requests\Passenger\StorePassengerRequest;
use App\Http\Requests\Passenger\UpdatePassengerRequest;
use App\Http\Resources\Api\V1\PassengerCollection;

class PassengerController extends Controller
{
    public function index()
    {
        $passengers = Passenger::with(['booking', 'ticket'])->paginate(15);
        return new PassengerCollection($passengers);
    }

    public function store(StorePassengerRequest $request)
    {
        $passenger = Passenger::create($request->validated());
        $passenger->load(['booking', 'ticket']);
        return response()->json(new PassengerResource($passenger), 201);
    }

    public function show(Passenger $passenger)
    {
        $passenger->load(['booking', 'ticket']);
        return new PassengerResource($passenger);
    }

    public function update(UpdatePassengerRequest $request, Passenger $passenger)
    {
        $passenger->update($request->validated());
        $passenger->load(['booking', 'ticket']);
        return new PassengerResource($passenger);
    }

    public function destroy(Passenger $passenger)
    {
        $passenger->delete();
        return response()->json(null, 204);
    }
    
public function ticket(Passenger $passenger)
{
    $ticket = $passenger->ticket()->with(['passenger'])->first();
    
    if (!$ticket) {
        return response()->json([
            'message' => 'No ticket found for this passenger'
        ], 404);
    }
    
    return new TicketResource($ticket);
}
}
