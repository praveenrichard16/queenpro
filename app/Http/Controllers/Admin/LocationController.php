<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SaudiArabiaLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = SaudiArabiaLocation::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('city_name_en', 'like', "%{$search}%")
                  ->orWhere('region_name_en', 'like', "%{$search}%")
                  ->orWhere('postal_code', 'like', "%{$search}%")
                  ->orWhere('city_name_ar', 'like', "%{$search}%")
                  ->orWhere('region_name_ar', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->get('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->get('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by region
        if ($request->filled('region')) {
            $query->where('region_name_en', $request->get('region'));
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city_name_en', $request->get('city'));
        }

        // Filter by pincode
        if ($request->filled('pincode')) {
            $query->where('postal_code', 'like', "%{$request->get('pincode')}%");
        }

        // Filter by district
        if ($request->filled('district')) {
            $query->where('district_name_en', $request->get('district'));
        }

        $locations = $query->orderBy('region_name_en')
                          ->orderBy('city_name_en')
                          ->paginate(20);

        // Get unique regions for filter dropdown
        $regions = SaudiArabiaLocation::select('region_name_en')
            ->distinct()
            ->whereNotNull('region_name_en')
            ->orderBy('region_name_en')
            ->pluck('region_name_en');

        // Get unique cities for filter dropdown (optionally filtered by selected region)
        $citiesQuery = SaudiArabiaLocation::select('city_name_en')
            ->distinct()
            ->whereNotNull('city_name_en');
        
        if ($request->filled('region')) {
            $citiesQuery->where('region_name_en', $request->get('region'));
        }
        
        $cities = $citiesQuery->orderBy('city_name_en')
            ->pluck('city_name_en');

        // Get unique districts for filter dropdown (optionally filtered by selected region/city)
        $districtsQuery = SaudiArabiaLocation::select('district_name_en')
            ->distinct()
            ->whereNotNull('district_name_en');
        
        if ($request->filled('region')) {
            $districtsQuery->where('region_name_en', $request->get('region'));
        }
        
        if ($request->filled('city')) {
            $districtsQuery->where('city_name_en', $request->get('city'));
        }
        
        $districts = $districtsQuery->orderBy('district_name_en')
            ->pluck('district_name_en');

        return view('admin.settings.locations.index', compact('locations', 'regions', 'cities', 'districts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $regions = SaudiArabiaLocation::select('region_name_en')
            ->distinct()
            ->whereNotNull('region_name_en')
            ->orderBy('region_name_en')
            ->pluck('region_name_en');

        return view('admin.settings.locations.create', compact('regions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'region_name_en' => 'required|string|max:255',
            'region_name_ar' => 'nullable|string|max:255',
            'city_name_en' => 'required|string|max:255',
            'city_name_ar' => 'nullable|string|max:255',
            'district_name_en' => 'nullable|string|max:255',
            'district_name_ar' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        SaudiArabiaLocation::create($validated);

        return redirect()->route('admin.settings.locations.index')
            ->with('success', 'Location created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaudiArabiaLocation $location): View
    {
        $regions = SaudiArabiaLocation::select('region_name_en')
            ->distinct()
            ->whereNotNull('region_name_en')
            ->orderBy('region_name_en')
            ->pluck('region_name_en');

        return view('admin.settings.locations.edit', compact('location', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaudiArabiaLocation $location): RedirectResponse
    {
        $validated = $request->validate([
            'region_name_en' => 'required|string|max:255',
            'region_name_ar' => 'nullable|string|max:255',
            'city_name_en' => 'required|string|max:255',
            'city_name_ar' => 'nullable|string|max:255',
            'district_name_en' => 'nullable|string|max:255',
            'district_name_ar' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $location->update($validated);

        return redirect()->route('admin.settings.locations.index')
            ->with('success', 'Location updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaudiArabiaLocation $location): RedirectResponse
    {
        $location->delete();

        return redirect()->route('admin.settings.locations.index')
            ->with('success', 'Location deleted successfully.');
    }

    /**
     * Show import form
     */
    public function import(): View
    {
        return view('admin.settings.locations.import');
    }

    /**
     * Process import file
     */
    public function processImport(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,json|max:10240',
            'import_mode' => 'required|in:create,update,replace',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $importMode = $request->input('import_mode');

        try {
            if ($extension === 'json') {
                $this->importJson($file, $importMode);
            } else {
                $this->importCsv($file, $importMode);
            }

            return redirect()->route('admin.settings.locations.index')
                ->with('success', 'Locations imported successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Export locations
     */
    public function export(Request $request): Response
    {
        $query = SaudiArabiaLocation::query();

        // Apply filters
        if ($request->filled('status')) {
            if ($request->get('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->get('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('region')) {
            $query->where('region_name_en', $request->get('region'));
        }

        if ($request->filled('city')) {
            $query->where('city_name_en', $request->get('city'));
        }

        if ($request->filled('pincode')) {
            $query->where('postal_code', 'like', "%{$request->get('pincode')}%");
        }

        if ($request->filled('district')) {
            $query->where('district_name_en', $request->get('district'));
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('city_name_en', 'like', "%{$search}%")
                  ->orWhere('region_name_en', 'like', "%{$search}%")
                  ->orWhere('postal_code', 'like', "%{$search}%")
                  ->orWhere('city_name_ar', 'like', "%{$search}%")
                  ->orWhere('region_name_ar', 'like', "%{$search}%");
            });
        }

        $locations = $query->orderBy('region_name_en')
                          ->orderBy('city_name_en')
                          ->get();

        $format = $request->get('format', 'csv');

        if ($format === 'json') {
            return $this->exportJson($locations);
        } else {
            return $this->exportCsv($locations);
        }
    }

    /**
     * Import JSON file
     */
    private function importJson($file, $mode): void
    {
        $content = file_get_contents($file->getRealPath());
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON format.');
        }

        if ($mode === 'replace') {
            DB::table('saudi_arabia_locations')->truncate();
        }

        $locations = [];
        foreach ($data as $item) {
            $location = [
                'region_name_en' => $item['region_name_en'] ?? $item['state_name_en'] ?? null,
                'region_name_ar' => $item['region_name_ar'] ?? $item['state_name_ar'] ?? null,
                'city_name_en' => $item['city_name_en'] ?? null,
                'city_name_ar' => $item['city_name_ar'] ?? null,
                'district_name_en' => $item['district_name_en'] ?? null,
                'district_name_ar' => $item['district_name_ar'] ?? null,
                'postal_code' => $item['postal_code'] ?? null,
                'latitude' => $item['latitude'] ?? null,
                'longitude' => $item['longitude'] ?? null,
                'is_active' => $item['is_active'] ?? true,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($mode === 'update' && isset($item['postal_code'])) {
                SaudiArabiaLocation::updateOrCreate(
                    ['postal_code' => $item['postal_code']],
                    $location
                );
            } else {
                $locations[] = $location;
            }
        }

        if (!empty($locations) && $mode !== 'update') {
            DB::table('saudi_arabia_locations')->insert($locations);
        }
    }

    /**
     * Import CSV file
     */
    private function importCsv($file, $mode): void
    {
        $handle = fopen($file->getRealPath(), 'r');
        $headers = fgetcsv($handle);
        
        if ($mode === 'replace') {
            DB::table('saudi_arabia_locations')->truncate();
        }

        $locations = [];
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($headers, $row);
            
            $location = [
                'region_name_en' => $data['region_name_en'] ?? $data['state_name_en'] ?? null,
                'region_name_ar' => $data['region_name_ar'] ?? $data['state_name_ar'] ?? null,
                'city_name_en' => $data['city_name_en'] ?? null,
                'city_name_ar' => $data['city_name_ar'] ?? null,
                'district_name_en' => $data['district_name_en'] ?? null,
                'district_name_ar' => $data['district_name_ar'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
                'latitude' => isset($data['latitude']) ? (float)$data['latitude'] : null,
                'longitude' => isset($data['longitude']) ? (float)$data['longitude'] : null,
                'is_active' => isset($data['is_active']) ? (bool)$data['is_active'] : true,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($mode === 'update' && isset($data['postal_code'])) {
                SaudiArabiaLocation::updateOrCreate(
                    ['postal_code' => $data['postal_code']],
                    $location
                );
            } else {
                $locations[] = $location;
            }
        }
        fclose($handle);

        if (!empty($locations) && $mode !== 'update') {
            DB::table('saudi_arabia_locations')->insert($locations);
        }
    }

    /**
     * Export to JSON
     */
    private function exportJson($locations): Response
    {
        $data = $locations->map(function($location) {
            return [
                'region_name_en' => $location->region_name_en,
                'region_name_ar' => $location->region_name_ar,
                'city_name_en' => $location->city_name_en,
                'city_name_ar' => $location->city_name_ar,
                'district_name_en' => $location->district_name_en,
                'district_name_ar' => $location->district_name_ar,
                'postal_code' => $location->postal_code,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'is_active' => $location->is_active,
            ];
        });

        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="saudi_arabia_locations_' . date('Y-m-d') . '.json"',
        ]);
    }

    /**
     * Export to CSV
     */
    private function exportCsv($locations): Response
    {
        $filename = 'saudi_arabia_locations_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($locations) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'region_name_en', 'region_name_ar', 'city_name_en', 'city_name_ar',
                'district_name_en', 'district_name_ar', 'postal_code',
                'latitude', 'longitude', 'is_active'
            ]);

            // Data
            foreach ($locations as $location) {
                fputcsv($file, [
                    $location->region_name_en,
                    $location->region_name_ar,
                    $location->city_name_en,
                    $location->city_name_ar,
                    $location->district_name_en,
                    $location->district_name_ar,
                    $location->postal_code,
                    $location->latitude,
                    $location->longitude,
                    $location->is_active ? '1' : '0',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

