<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Database\Seeders\IndiaLocationSeeder;

class ImportIndiaLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'india:import-locations 
                            {--file= : Path to the location data file (JSON or CSV)}
                            {--clear : Clear existing location data before import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import India location data (states, districts, pincodes) from JSON or CSV file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('India Location Data Import');
        $this->newLine();

        $dataPath = database_path('seeders/data');
        
        // Create data directory if it doesn't exist
        if (!File::exists($dataPath)) {
            File::makeDirectory($dataPath, 0755, true);
            $this->info("Created directory: {$dataPath}");
        }

        // Handle file option
        $filePath = $this->option('file');
        if ($filePath) {
            if (!File::exists($filePath)) {
                $this->error("File not found: {$filePath}");
                return Command::FAILURE;
            }

            // Copy file to data directory
            $fileName = basename($filePath);
            $destPath = $dataPath . '/' . $fileName;
            File::copy($filePath, $destPath);
            $this->info("Copied file to: {$destPath}");
        }

        // Clear existing data if requested
        if ($this->option('clear')) {
            if ($this->confirm('This will delete all existing location data. Are you sure?')) {
                $this->info('Clearing existing location data...');
                \App\Models\SaudiArabiaLocation::truncate();
                $this->info('Existing data cleared.');
            } else {
                $this->info('Import cancelled.');
                return Command::SUCCESS;
            }
        }

        // Run the seeder
        $this->newLine();
        $seeder = new IndiaLocationSeeder();
        $seeder->setCommand($this);
        $seeder->run();

        $this->newLine();
        $this->info('✓ Import completed!');
        
        return Command::SUCCESS;
    }
}

