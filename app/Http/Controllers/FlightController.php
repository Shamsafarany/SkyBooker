<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Airplane;
use App\Http\Requests\Flight\StoreFlightRequest;
use App\Http\Requests\Flight\UpdateFlightRequest;
use App\Services\WeatherService;
use App\Services\Admin\FlightService;
class FlightController extends Controller
{
    public function __construct(private FlightService $flightService) {}
    public function index(Request $request){
        
        $data = $this->flightService->getAllWithStats();    
        return view('admin.flights.index', ['flights' => $data['flights'],
        'stats' => $data['stats'], 'airlines' => $data['airlines']]);

    }

    public function create()
    {
        $airlines = Airline::orderBy('name')->get();
        $airports = Airport::orderBy('name')->get();
        $airplanes = Airplane::where('status', 'active')->orderBy('model')->get();

        return view('admin.flights.create', compact('airlines', 'airports', 'airplanes'));
    }

    public function store(StoreFlightRequest $request)
    {
        $result = $this->flightService->create($request->validated());

        if (!$result['success']) {
            return back()
                ->with('error', $result['message'])
                ->withInput();
        }

        $flight = $result['flight'];

        return redirect()
            ->route('admin.flights.show', $flight)
            ->with('success', 'Flight created successfully!');
    }

    public function show(string $id)
    {
        $result = $this->flightService->showAdmin($id);

        return view('admin.flights.show', [
            'flight' => $result['flight'],
            'passengers' => $result['passengers'],
            'originWeather' => $result['originWeather'],
        ]);
    }


    public function edit(Flight $flight)
    {
        $airlines = Airline::orderBy('name')->get();
        $airports = Airport::orderBy('name')->get();
        $airplanes = Airplane::where('status', 'active')->orderBy('model')->get();
        return view('admin.flights.edit', compact('flight', 'airlines', 'airports', 'airplanes'));
    }

    public function update(UpdateFlightRequest $request, Flight $flight)
    {
        $this->flightService->update($flight, $request->validated());
        return redirect()->route('admin.flights.show', $flight)
            ->with('success', 'Flight updated successfully!');
    }

    public function destroy(Flight $flight)
    {
        $result = $this->flightService->delete($flight);
        if (!$result['success']) {
        return back()->with('error', $result['message']);
    }
        return redirect()->route('admin.flights.index')
            ->with('success', 'Flight deleted successfully!');
    }

    public function weather(Flight $flight, WeatherService $weather)
    {
        $data = $weather->getWeatherByCity($flight->origin->city);
        return response()->success($data, 'Weather for flight city');
    }

    
}



















