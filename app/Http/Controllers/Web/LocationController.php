<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Zone;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return view('locations.index', [
            'locations' => Location::with('zone')->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('locations.create', ['zones' => Zone::all()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'zone_id'    => 'required|exists:zones,id',
            'code'       => 'required|string|max:50|unique:locations',
            'x_coord'    => 'required|integer|min:0',
            'y_coord'    => 'required|integer|min:0',
            'z_coord'    => 'required|integer|min:0',
            'max_weight' => 'required|numeric|min:0.1',
            'max_volume' => 'required|numeric|min:0.1',
        ]);

        Location::create($request->only('zone_id', 'code', 'x_coord', 'y_coord', 'z_coord', 'max_weight', 'max_volume') + ['is_active' => true]);

        return redirect()->route('locations.index')->with('success', 'Location created successfully.');
    }

    public function edit(Location $location)
    {
        return view('locations.edit', ['location' => $location, 'zones' => Zone::all()]);
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'zone_id'    => 'required|exists:zones,id',
            'code'       => 'required|string|max:50|unique:locations,code,' . $location->id,
            'x_coord'    => 'required|integer|min:0',
            'y_coord'    => 'required|integer|min:0',
            'z_coord'    => 'required|integer|min:0',
            'max_weight' => 'required|numeric|min:0.1',
            'max_volume' => 'required|numeric|min:0.1',
            'is_active'  => 'boolean',
        ]);

        $location->update($request->only('zone_id', 'code', 'x_coord', 'y_coord', 'z_coord', 'max_weight', 'max_volume') + [
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Location deleted.');
    }
}
