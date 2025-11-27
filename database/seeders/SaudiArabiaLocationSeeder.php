<?php

namespace Database\Seeders;

use App\Models\SaudiArabiaLocation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SaudiArabiaLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting Saudi Arabia Location Data Import...');
        
        $dataPath = database_path('seeders/data');
        
        // Check if data directory exists and has files
        if (!File::exists($dataPath) || empty(File::files($dataPath))) {
            $this->command->warn('No location data files found!');
            $this->command->info('Run this command to download the data:');
            $this->command->info('  php artisan saudi-arabia:download-locations');
            $this->command->newLine();
            return;
        }
        
        // Clear existing data (optional - comment out if you want to keep existing data)
        // SaudiArabiaLocation::truncate();
        
        // Try different possible file locations and formats
        // Method 1: Try importing from separate files (regions.json, cities.json, districts.json)
        if ($this->importFromSeparateFiles($dataPath)) {
            $this->command->info('Successfully imported from separate files.');
            return;
        }

        // Method 2: Try importing from combined JSON file
        if ($this->importFromCombinedFile($dataPath)) {
            $this->command->info('Successfully imported from combined file.');
            return;
        }

        // Method 3: Try importing from MySQL dump
        if ($this->importFromMySQLDump($dataPath)) {
            $this->command->info('Successfully imported from MySQL dump.');
            return;
        }

        $this->command->error('No valid data files found!');
        $this->command->info('Expected files:');
        $this->command->info('  - regions.json, cities.json, districts.json (separate files)');
        $this->command->info('  - saudi-arabia-locations.json (combined file)');
        $this->command->info('  - saudi-arabia-locations.sql (MySQL dump)');
        $this->command->info('');
        $this->command->info('Download from: https://github.com/homaily/Saudi-Arabia-Regions-Cities-and-Districts');
    }

    /**
     * Import from separate JSON files (regions.json, cities.json, districts.json)
     */
    protected function importFromSeparateFiles(string $dataPath): bool
    {
        $regionsFile = $dataPath . '/regions.json';
        $citiesFile = $dataPath . '/cities.json';
        $districtsFile = $dataPath . '/districts.json';

        if (!File::exists($citiesFile)) {
            return false;
        }

        $this->command->info('Importing from separate files...');

        // Import regions first (if available)
        $regions = [];
        if (File::exists($regionsFile)) {
            $regionsData = json_decode(File::get($regionsFile), true);
            if (is_array($regionsData)) {
                // Handle different possible structures
                $regions = $this->normalizeRegions($regionsData);
                $this->command->info("Found " . count($regions) . " regions");
            }
        }

        // Import cities
        $citiesData = json_decode(File::get($citiesFile), true);
        if (!is_array($citiesData)) {
            return false;
        }

        $this->command->info("Found " . count($citiesData) . " cities");
        $this->command->info('Processing cities...');

        $locations = [];
        $bar = $this->command->getOutput()->createProgressBar(count($citiesData));
        $bar->start();

        foreach ($citiesData as $city) {
            $normalized = $this->normalizeCityData($city, $regions);
            if ($normalized) {
                $locations[] = $normalized;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();

        // Import districts (if available)
        if (File::exists($districtsFile)) {
            $districtsData = json_decode(File::get($districtsFile), true);
            if (is_array($districtsData)) {
                $this->command->info("Found " . count($districtsData) . " districts");
                $this->command->info('Processing districts...');
                
                $districtBar = $this->command->getOutput()->createProgressBar(count($districtsData));
                $districtBar->start();

                foreach ($districtsData as $district) {
                    $normalized = $this->normalizeDistrictData($district, $citiesData);
                    if ($normalized) {
                        $locations[] = $normalized;
                    }
                    $districtBar->advance();
                }

                $districtBar->finish();
                $this->command->newLine();
            }
        }

        // Insert in chunks for performance
        $this->command->info('Inserting ' . count($locations) . ' locations into database...');
        $chunks = array_chunk($locations, 500);
        $insertBar = $this->command->getOutput()->createProgressBar(count($chunks));
        $insertBar->start();

        foreach ($chunks as $chunk) {
            try {
                DB::table('saudi_arabia_locations')->insert($chunk);
            } catch (\Exception $e) {
                $this->command->error("\nError inserting chunk: " . $e->getMessage());
            }
            $insertBar->advance();
        }

        $insertBar->finish();
        $this->command->newLine();

        return true;
    }

    /**
     * Import from combined JSON file
     */
    protected function importFromCombinedFile(string $dataPath): bool
    {
        $possibleFiles = [
            'saudi-arabia-locations.json',
            'locations.json',
            'saudi_locations.json',
            'all-locations.json',
        ];

        foreach ($possibleFiles as $filename) {
            $filePath = $dataPath . '/' . $filename;
            if (File::exists($filePath)) {
                $this->command->info("Importing from: {$filename}");
                
                $data = json_decode(File::get($filePath), true);
                if (!is_array($data)) {
                    continue;
                }

                $locations = [];
                $bar = $this->command->getOutput()->createProgressBar(count($data));
                $bar->start();

                foreach ($data as $item) {
                    $normalized = $this->normalizeLocationData($item);
                    if ($normalized) {
                        $locations[] = $normalized;
                    }
                    $bar->advance();
                }

                $bar->finish();
                $this->command->newLine();

                // Insert in chunks
                $chunks = array_chunk($locations, 500);
                foreach ($chunks as $chunk) {
                    try {
                        DB::table('saudi_arabia_locations')->insert($chunk);
                    } catch (\Exception $e) {
                        $this->command->error("\nError: " . $e->getMessage());
                    }
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Import from MySQL dump file
     */
    protected function importFromMySQLDump(string $dataPath): bool
    {
        $sqlFile = $dataPath . '/saudi-arabia-locations.sql';
        
        if (!File::exists($sqlFile)) {
            return false;
        }

        $this->command->info('Importing from SQL dump...');
        
        try {
            $sql = File::get($sqlFile);
            // Remove CREATE TABLE statements if they exist
            $sql = preg_replace('/CREATE TABLE.*?;/is', '', $sql);
            // Split by semicolons and execute each statement
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            
            $bar = $this->command->getOutput()->createProgressBar(count($statements));
            $bar->start();

            foreach ($statements as $statement) {
                if (!empty($statement) && stripos($statement, 'INSERT') === 0) {
                    // Adjust table name if needed
                    $statement = str_replace('`regions`', '`saudi_arabia_locations`', $statement);
                    $statement = str_replace('`cities`', '`saudi_arabia_locations`', $statement);
                    $statement = str_replace('`districts`', '`saudi_arabia_locations`', $statement);
                    
                    try {
                        DB::unprepared($statement);
                    } catch (\Exception $e) {
                        // Skip errors for now
                    }
                }
                $bar->advance();
            }

            $bar->finish();
            $this->command->newLine();
            
            return true;
        } catch (\Exception $e) {
            $this->command->error("Error importing SQL: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Normalize regions data from different possible formats
     */
    protected function normalizeRegions(array $regionsData): array
    {
        $regions = [];
        
        // Handle array of regions
        if (isset($regionsData[0])) {
            foreach ($regionsData as $region) {
                $regions[$region['id'] ?? $region['Id'] ?? null] = [
                    'name_en' => $region['name_en'] ?? $region['nameEn'] ?? $region['Name'] ?? null,
                    'name_ar' => $region['name_ar'] ?? $region['nameAr'] ?? $region['name_ar'] ?? null,
                ];
            }
        } else {
            // Handle object format
            foreach ($regionsData as $id => $region) {
                $regions[$id] = [
                    'name_en' => $region['name_en'] ?? $region['nameEn'] ?? null,
                    'name_ar' => $region['name_ar'] ?? $region['nameAr'] ?? null,
                ];
            }
        }

        return $regions;
    }

    /**
     * Normalize city data
     */
    protected function normalizeCityData(array $city, array $regions = []): ?array
    {
        $regionId = $city['region_id'] ?? $city['regionId'] ?? $city['RegionId'] ?? null;
        $regionNameEn = null;
        $regionNameAr = null;

        if ($regionId && isset($regions[$regionId])) {
            $regionNameEn = $regions[$regionId]['name_en'];
            $regionNameAr = $regions[$regionId]['name_ar'];
        } else {
            $regionNameEn = $city['region_name_en'] ?? $city['regionNameEn'] ?? $city['region'] ?? null;
            $regionNameAr = $city['region_name_ar'] ?? $city['regionNameAr'] ?? null;
        }

        return [
            'region_id' => $regionId,
            'region_name_ar' => $regionNameAr,
            'region_name_en' => $regionNameEn,
            'city_id' => $city['id'] ?? $city['Id'] ?? $city['city_id'] ?? null,
            'city_name_ar' => $city['name_ar'] ?? $city['nameAr'] ?? $city['name_ar'] ?? null,
            'city_name_en' => $city['name_en'] ?? $city['nameEn'] ?? $city['name'] ?? $city['Name'] ?? null,
            'district_id' => null,
            'district_name_ar' => null,
            'district_name_en' => null,
            'postal_code' => $city['postal_code'] ?? $city['postalCode'] ?? $city['zip'] ?? null,
            'latitude' => $city['latitude'] ?? $city['lat'] ?? null,
            'longitude' => $city['longitude'] ?? $city['lng'] ?? $city['lon'] ?? null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Normalize district data
     */
    protected function normalizeDistrictData(array $district, array $cities = []): ?array
    {
        $cityId = $district['city_id'] ?? $district['cityId'] ?? $district['CityId'] ?? null;
        
        // Find city info
        $cityInfo = null;
        foreach ($cities as $city) {
            if (($city['id'] ?? $city['Id'] ?? null) == $cityId) {
                $cityInfo = $city;
                break;
            }
        }

        if (!$cityInfo) {
            return null;
        }

        return [
            'region_id' => $cityInfo['region_id'] ?? $cityInfo['regionId'] ?? null,
            'region_name_ar' => $cityInfo['region_name_ar'] ?? $cityInfo['regionNameAr'] ?? null,
            'region_name_en' => $cityInfo['region_name_en'] ?? $cityInfo['regionNameEn'] ?? $cityInfo['region'] ?? null,
            'city_id' => $cityId,
            'city_name_ar' => $cityInfo['name_ar'] ?? $cityInfo['nameAr'] ?? null,
            'city_name_en' => $cityInfo['name_en'] ?? $cityInfo['nameEn'] ?? $cityInfo['name'] ?? null,
            'district_id' => $district['id'] ?? $district['Id'] ?? null,
            'district_name_ar' => $district['name_ar'] ?? $district['nameAr'] ?? null,
            'district_name_en' => $district['name_en'] ?? $district['nameEn'] ?? $district['name'] ?? null,
            'postal_code' => $district['postal_code'] ?? $district['postalCode'] ?? $district['zip'] ?? null,
            'latitude' => $district['latitude'] ?? $district['lat'] ?? null,
            'longitude' => $district['longitude'] ?? $district['lng'] ?? $district['lon'] ?? null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Normalize location data from combined file
     */
    protected function normalizeLocationData(array $item): ?array
    {
        // Try to determine if it's a region, city, or district
        if (isset($item['city_name_en']) || isset($item['cityNameEn']) || isset($item['city'])) {
            return $this->normalizeCityData($item);
        } elseif (isset($item['district_name_en']) || isset($item['districtNameEn']) || isset($item['district'])) {
            return $this->normalizeDistrictData($item, []);
        } elseif (isset($item['region_name_en']) || isset($item['regionNameEn']) || isset($item['region'])) {
            // Region only - we'll skip regions without cities for now
            return null;
        }

        return null;
    }
}

