<?php

namespace App\Http\Controllers;

use App\Models\SaudiArabiaLocation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SaudiArabiaLocationController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $term = $request->get('term', '');
        
        $locations = SaudiArabiaLocation::active()
            ->search($term)
            ->limit(50)
            ->get()
            ->map(function($location) {
                return [
                    'id' => $location->id,
                    'city' => $location->city_name_en,
                    'city_ar' => $location->city_name_ar ?? '',
                    'state' => $location->region_name_en,
                    'state_ar' => $location->region_name_ar ?? '',
                    'postal_code' => $location->postal_code,
                    'display' => "{$location->city_name_en}, {$location->region_name_en}" . ($location->postal_code ? " ({$location->postal_code})" : "")
                ];
            });

        return response()->json($locations);
    }

    public function states(): JsonResponse
    {
        $states = SaudiArabiaLocation::getStates();
        return response()->json($states);
    }

    public function cities(Request $request): JsonResponse
    {
        $state = $request->get('state');
        
        if (!$state) {
            return response()->json([]);
        }

        $cities = SaudiArabiaLocation::getCitiesByState($state);
        return response()->json($cities);
    }

    public function postalCodes(Request $request): JsonResponse
    {
        $city = $request->get('city');
        $state = $request->get('state');
        
        $postalCodes = SaudiArabiaLocation::getPostalCodes($city, $state);
        return response()->json($postalCodes->toArray());
    }
}

