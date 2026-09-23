<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\State;
use App\Models\Subcategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationApiController extends Controller
{
    /**
     * Get states for a given country.
     */
    public function getStates(Request $request): JsonResponse
    {
        $countryId = $request->query('country_id');
        $states = State::when($countryId, fn ($q) => $q->where('country_id', $countryId))
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return response()->json($states);
    }

    /**
     * Get cities for a given state.
     */
    public function getCities(Request $request): JsonResponse
    {
        $stateId = $request->query('state_id');
        $cities = City::when($stateId, fn ($q) => $q->where('state_id', $stateId))
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'is_popular']);

        return response()->json($cities);
    }

    /**
     * Get areas for a given city.
     */
    public function getAreas(Request $request): JsonResponse
    {
        $cityId = $request->query('city_id');
        $areas = Area::when($cityId, fn ($q) => $q->where('city_id', $cityId))
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return response()->json($areas);
    }

    /**
     * Get subcategories for a given category.
     */
    public function getSubcategories(Request $request): JsonResponse
    {
        $categoryId = $request->query('category_id');
        $subcategories = Subcategory::when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return response()->json($subcategories);
    }
}
