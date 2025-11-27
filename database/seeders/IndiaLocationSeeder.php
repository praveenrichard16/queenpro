<?php

namespace Database\Seeders;

use App\Models\SaudiArabiaLocation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class IndiaLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting India Location Data Import...');
        
        $dataPath = database_path('seeders/data');
        
        // Check if data directory exists and has files
        if (!File::exists($dataPath) || empty(File::files($dataPath))) {
            $this->command->warn('No location data files found!');
            $this->command->info('Please place India location data file in: ' . $dataPath);
            $this->command->info('Expected file: india-locations.json or india-locations.csv');
            $this->command->info('Or use the admin import feature at: /admin/settings/locations/import');
            $this->command->newLine();
            return;
        }
        
        // Clear existing data (optional - comment out if you want to keep existing data)
        // SaudiArabiaLocation::truncate();
        
        // Try different possible file locations and formats
        // Method 1: Try importing from combined JSON file
        if ($this->importFromCombinedFile($dataPath)) {
            $this->command->info('Successfully imported from combined file.');
            return;
        }

        // Method 2: Try importing from CSV file
        if ($this->importFromCsvFile($dataPath)) {
            $this->command->info('Successfully imported from CSV file.');
            return;
        }

        $this->command->error('No valid data files found!');
        $this->command->info('Expected files:');
        $this->command->info('  - india-locations.json (combined JSON file)');
        $this->command->info('  - india-locations.csv (CSV file)');
        $this->command->info('');
        $this->command->info('CSV format should have columns: state, city, district, pincode, latitude, longitude');
        $this->command->info('JSON format should be an array of objects with: state, city, district, pincode, latitude, longitude');
    }

    /**
     * Import from combined JSON file
     */
    protected function importFromCombinedFile(string $dataPath): bool
    {
        $possibleFiles = [
            'india-locations.json',
            'india-locations-sample.json',
            'india_locations.json',
            'indian-locations.json',
            'locations.json',
        ];

        foreach ($possibleFiles as $filename) {
            $filePath = $dataPath . '/' . $filename;
            if (File::exists($filePath)) {
                $this->command->info("Importing from: {$filename}");
                
                $data = json_decode(File::get($filePath), true);
                if (!is_array($data)) {
                    $this->command->warn("  Invalid JSON format in {$filename}");
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
        }

        return false;
    }

    /**
     * Import from CSV file
     */
    protected function importFromCsvFile(string $dataPath): bool
    {
        $possibleFiles = [
            'india-locations.csv',
            'india-locations-sample.csv',
            'india_locations.csv',
            'indian-locations.csv',
            'locations.csv',
        ];

        foreach ($possibleFiles as $filename) {
            $filePath = $dataPath . '/' . $filename;
            if (File::exists($filePath)) {
                $this->command->info("Importing from: {$filename}");
                
                $handle = fopen($filePath, 'r');
                if (!$handle) {
                    continue;
                }

                $headers = fgetcsv($handle);
                if (!$headers) {
                    fclose($handle);
                    continue;
                }

                // Normalize headers (case-insensitive, handle spaces)
                $headers = array_map(function($h) {
                    return strtolower(trim($h));
                }, $headers);

                $locations = [];
                $rowCount = 0;
                
                // Count total rows first (for progress bar)
                $totalRows = 0;
                while (($row = fgetcsv($handle)) !== false) {
                    $totalRows++;
                }
                rewind($handle);
                fgetcsv($handle); // Skip header again

                $bar = $this->command->getOutput()->createProgressBar($totalRows);
                $bar->start();

                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) !== count($headers)) {
                        continue;
                    }

                    $data = array_combine($headers, $row);
                    $normalized = $this->normalizeLocationData($data);
                    if ($normalized) {
                        $locations[] = $normalized;
                    }
                    $bar->advance();
                    $rowCount++;
                }

                fclose($handle);
                $bar->finish();
                $this->command->newLine();

                // Insert in chunks
                if (!empty($locations)) {
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
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Normalize location data from different possible formats
     */
    protected function normalizeLocationData(array $item): ?array
    {
        // Map India data to database structure
        // Handle different possible field names
        $state = $item['state'] ?? $item['State'] ?? $item['state_name'] ?? $item['stateName'] ?? $item['region'] ?? $item['region_name_en'] ?? null;
        $city = $item['city'] ?? $item['City'] ?? $item['city_name'] ?? $item['cityName'] ?? $item['city_name_en'] ?? null;
        $district = $item['district'] ?? $item['District'] ?? $item['district_name'] ?? $item['districtName'] ?? $item['district_name_en'] ?? null;
        $pincode = $item['pincode'] ?? $item['Pincode'] ?? $item['pin_code'] ?? $item['pinCode'] ?? $item['postal_code'] ?? $item['postalCode'] ?? $item['zip'] ?? null;
        $latitude = $item['latitude'] ?? $item['Latitude'] ?? $item['lat'] ?? null;
        $longitude = $item['longitude'] ?? $item['Longitude'] ?? $item['lng'] ?? $item['lon'] ?? null;

        // State and city are required
        if (empty($state) || empty($city)) {
            return null;
        }

        // Clean pincode (remove spaces, ensure 6 digits for India)
        if ($pincode) {
            $pincode = preg_replace('/[^0-9]/', '', $pincode);
            if (strlen($pincode) > 6) {
                $pincode = substr($pincode, 0, 6);
            }
        }

        return [
            'region_id' => null,
            'region_name_ar' => null,
            'region_name_en' => $state,
            'city_id' => null,
            'city_name_ar' => null,
            'city_name_en' => $city,
            'district_id' => null,
            'district_name_ar' => null,
            'district_name_en' => $district,
            'postal_code' => $pincode ?: null,
            'latitude' => $latitude ? (float)$latitude : null,
            'longitude' => $longitude ? (float)$longitude : null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

