<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index()
    {
        return view('zones.index', ['zones' => Zone::withCount('locations')->latest()->get()]);
    }

    public function create()
    {
        return view('zones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:zones',
            'type' => 'required|in:standard,cold_storage,hazmat,inbound,outbound',
            'description' => 'nullable|string',
        ]);

        Zone::create($request->only('name', 'code', 'type', 'description'));

        return redirect()->route('zones.index')->with('success', 'Zone created successfully.');
    }

    public function edit(Zone $zone)
    {
        return view('zones.edit', compact('zone'));
    }

    public function update(Request $request, Zone $zone)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:zones,code,' . $zone->id,
            'type' => 'required|in:standard,cold_storage,hazmat,inbound,outbound',
            'description' => 'nullable|string',
        ]);

        $zone->update($request->only('name', 'code', 'type', 'description'));

        return redirect()->route('zones.index')->with('success', 'Zone updated successfully.');
    }

    public function destroy(Zone $zone)
    {
        $zone->delete();
        return redirect()->route('zones.index')->with('success', 'Zone deleted.');
    }
}
