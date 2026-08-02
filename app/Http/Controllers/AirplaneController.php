<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airplane;

class AirplaneController extends Controller
{
    public function index()
    {
        
        $airplanes = Airplane::withCount('flights')
            ->orderBy('manufacturer')
            ->orderBy('model')
            ->get();
        return view('admin.airplanes.index', compact('airplanes'));
    }

    public function create()
    {
        return view('admin.airplanes.create');
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
