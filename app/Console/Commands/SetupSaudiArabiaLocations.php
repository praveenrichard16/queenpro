<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupSaudiArabiaLocations extends Command
{
    protected $signature = 'saudi-arabia:setup 
                            {--skip-download : Skip downloading and only run seeder}
                            {--force : Force download even if files exist}';

    protected $description = 'Download and import Saudi Arabia location data';

    public function handle(): int
    {
        // Step 1: Download data
        if (!$this->option('skip-download')) {
            $this->info('Step 1: Downloading location data...');
            $this->newLine();
            
            $exitCode = $this->call('saudi-arabia:download-locations', [
                '--force' => $this->option('force'),
            ]);
            
            if ($exitCode !== Command::SUCCESS) {
                $this->error('Download failed. Please check the error messages above.');
                return Command::FAILURE;
            }
            
            $this->newLine();
        }

        // Step 2: Run seeder
        $this->info('Step 2: Importing location data into database...');
        $this->newLine();
        
        if (!$this->confirm('Do you want to import the data into the database now?')) {
            $this->info('Import cancelled. Run manually with: php artisan db:seed --class=SaudiArabiaLocationSeeder');
            return Command::SUCCESS;
        }

        $exitCode = $this->call('db:seed', [
            '--class' => 'SaudiArabiaLocationSeeder',
        ]);

        if ($exitCode === Command::SUCCESS) {
            $this->newLine();
            $this->info('✓ Saudi Arabia location data setup complete!');
        }

        return $exitCode;
    }
}

