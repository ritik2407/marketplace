<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminLocationController extends Controller
{
    /**
     * Display location manager.
     */
    public function index(): View
    {
        $states = State::with(['cities' => fn ($q) => $q->withCount(['listings', 'areas'])->orderBy('name')])
            ->orderBy('name')
            ->get();

        return view('admin.locations.index', compact('states'));
    }

    /**
     * Store new city.
     */
    public function storeCity(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'state_id' => ['required', 'exists:states,id'],
            'name' => ['required', 'string', 'max:100'],
            'is_popular' => ['nullable', 'boolean'],
        ]);

        City::create([
            'state_id' => $validated['state_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_popular' => $request->boolean('is_popular'),
        ]);

        return back()->with('success', "City '{$validated['name']}' added successfully.");
    }

    /**
     * Toggle popular city status.
     */
    public function togglePopularCity(int $id): RedirectResponse
    {
        $city = City::findOrFail($id);
        $city->update(['is_popular' => ! $city->is_popular]);

        $status = $city->is_popular ? 'promoted to Popular City' : 'removed from Popular Cities';

        return back()->with('success', "City '{$city->name}' {$status}.");
    }

    /**
     * Store new area under a city.
     */
    public function storeArea(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'city_id' => ['required', 'exists:cities,id'],
            'name' => ['required', 'string', 'max:100'],
        ]);

        Area::create([
            'city_id' => $validated['city_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('success', "Area '{$validated['name']}' added to city.");
    }

    /**
     * Delete a city.
     */
    public function destroyCity(int $id): RedirectResponse
    {
        $city = City::findOrFail($id);
        $name = $city->name;
        $city->delete();

        return back()->with('success', "City '{$name}' deleted.");
    }

    /**
     * Delete an area.
     */
    public function destroyArea(int $id): RedirectResponse
    {
        $area = Area::findOrFail($id);
        $name = $area->name;
        $area->delete();

        return back()->with('success', "Area '{$name}' deleted.");
    }
}
