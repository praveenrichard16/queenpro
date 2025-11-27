<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use ZipArchive;

class DownloadSaudiArabiaLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saudi-arabia:download-locations 
                            {--force : Force download even if files exist}
                            {--source=github : Data source (github, raw)}
                            {--format=json : Preferred format (json, zip)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Saudi Arabia location data from GitHub repository';

    /**
     * GitHub repository information
     */
    protected $githubRepo = 'homaily/Saudi-Arabia-Regions-Cities-and-Districts';
    protected $githubApiUrl = 'https://api.github.com/repos';
    protected $githubRawUrl = 'https://raw.githubusercontent.com';
    protected $dataPath;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->dataPath = database_path('seeders/data');
        
        // Create data directory if it doesn't exist
        if (!File::exists($this->dataPath)) {
            File::makeDirectory($this->dataPath, 0755, true);
            $this->info("Created directory: {$this->dataPath}");
        }

        // Check if files already exist
        if (!$this->option('force') && $this->filesExist()) {
            if (!$this->confirm('Location data files already exist. Do you want to download again?')) {
                $this->info('Download cancelled.');
                return Command::SUCCESS;
            }
        }

        $source = $this->option('source');
        $format = $this->option('format');

        $this->info('Downloading Saudi Arabia location data...');
        $this->newLine();

        try {
            if ($source === 'github' && $format === 'zip') {
                return $this->downloadFromGitHubZip();
            } elseif ($source === 'github' || $source === 'raw') {
                return $this->downloadFromGitHubRaw();
            } else {
                $this->error("Unknown source: {$source}");
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    /**
     * Download from GitHub as ZIP archive
     */
    protected function downloadFromGitHubZip(): int
    {
        $this->info('Downloading ZIP archive from GitHub...');
        
        // Get latest release or main branch ZIP
        $zipUrl = "https://github.com/{$this->githubRepo}/archive/refs/heads/main.zip";
        $zipPath = $this->dataPath . '/saudi-arabia-locations.zip';

        try {
            $this->downloadFile($zipUrl, $zipPath, 'ZIP archive');
            
            $this->info('Extracting ZIP archive...');
            $this->extractZip($zipPath);
            
            // Clean up ZIP file
            File::delete($zipPath);
            
            $this->newLine();
            $this->info('✓ Successfully downloaded and extracted location data!');
            $this->info('Files are ready in: ' . $this->dataPath);
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to download ZIP: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Download individual JSON files from GitHub raw
     */
    protected function downloadFromGitHubRaw(): int
    {
        $this->info('Downloading JSON files from GitHub...');
        
        // List of files to download
        $files = [
            'regions.json' => "regions.json",
            'cities.json' => "cities.json",
            'districts.json' => "districts.json",
        ];

        // Alternative file names to try
        $alternativeFiles = [
            'regions' => ['regions.json', 'region.json', 'Regions.json'],
            'cities' => ['cities.json', 'city.json', 'Cities.json'],
            'districts' => ['districts.json', 'district.json', 'Districts.json'],
        ];

        $downloaded = 0;
        $failed = 0;

        foreach ($files as $localName => $remoteName) {
            $this->info("Downloading {$localName}...");
            
            $success = false;
            $basePath = "{$this->githubRepo}/main";
            
            // Try main file name first
            $urls = [
                "{$this->githubRawUrl}/{$basePath}/{$remoteName}",
                "{$this->githubRawUrl}/{$basePath}/data/{$remoteName}",
                "{$this->githubRawUrl}/{$basePath}/json/{$remoteName}",
            ];

            // Try alternative names
            if (isset($alternativeFiles[explode('.', $localName)[0]])) {
                foreach ($alternativeFiles[explode('.', $localName)[0]] as $altName) {
                    $urls[] = "{$this->githubRawUrl}/{$basePath}/{$altName}";
                    $urls[] = "{$this->githubRawUrl}/{$basePath}/data/{$altName}";
                    $urls[] = "{$this->githubRawUrl}/{$basePath}/json/{$altName}";
                }
            }

            foreach ($urls as $url) {
                try {
                    $filePath = $this->dataPath . '/' . $localName;
                    if ($this->downloadFile($url, $filePath, $localName, false)) {
                        $success = true;
                        $downloaded++;
                        break;
                    }
                } catch (\Exception $e) {
                    // Try next URL
                    continue;
                }
            }

            if (!$success) {
                $this->warn("  ✗ Could not download {$localName}");
                $failed++;
            }
        }

        $this->newLine();
        
        if ($downloaded > 0) {
            $this->info("✓ Successfully downloaded {$downloaded} file(s)!");
            $this->info('Files are ready in: ' . $this->dataPath);
            
            if ($failed > 0) {
                $this->warn("⚠ {$failed} file(s) could not be downloaded.");
                $this->info('You may need to download them manually from:');
                $this->info("https://github.com/{$this->githubRepo}");
            }
            
            return Command::SUCCESS;
        } else {
            $this->error('✗ Failed to download any files.');
            $this->info('Please download manually from:');
            $this->info("https://github.com/{$this->githubRepo}");
            return Command::FAILURE;
        }
    }

    /**
     * Download a file with progress bar
     */
    protected function downloadFile(
        string $url, 
        string $destination, 
        string $label = 'File',
        bool $showProgress = true
    ): bool {
        try {
            $response = Http::timeout(300)->get($url);

            if ($response->failed()) {
                if ($showProgress) {
                    $this->error("  ✗ Failed to download {$label}: HTTP {$response->status()}");
                }
                return false;
            }

            // Check if response is valid JSON (for JSON files)
            if (str_ends_with($destination, '.json')) {
                $content = $response->body();
                json_decode($content);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Might be HTML error page
                    if (str_contains($content, '<html') || str_contains($content, '<!DOCTYPE')) {
                        if ($showProgress) {
                            $this->warn("  ✗ {$label} not found at this URL");
                        }
                        return false;
                    }
                }
            }

            File::put($destination, $response->body());
            
            if ($showProgress) {
                $size = $this->formatBytes(File::size($destination));
                $this->info("  ✓ Downloaded {$label} ({$size})");
            }
            
            return true;
        } catch (\Exception $e) {
            if ($showProgress) {
                $this->error("  ✗ Error downloading {$label}: " . $e->getMessage());
            }
            return false;
        }
    }

    /**
     * Extract ZIP archive
     */
    protected function extractZip(string $zipPath): void
    {
        if (!class_exists('ZipArchive')) {
            throw new \Exception('ZipArchive class not available. Please install php-zip extension.');
        }

        $zip = new ZipArchive;
        
        if ($zip->open($zipPath) !== true) {
            throw new \Exception('Failed to open ZIP archive');
        }

        $extractPath = $this->dataPath . '/extracted';
        if (File::exists($extractPath)) {
            File::deleteDirectory($extractPath);
        }
        File::makeDirectory($extractPath, 0755, true);

        // Extract all files
        $zip->extractTo($extractPath);
        $zip->close();

        // Find and move JSON files
        $this->moveJsonFiles($extractPath, $this->dataPath);
        
        // Clean up extracted directory
        File::deleteDirectory($extractPath);
    }

    /**
     * Move JSON files from extracted directory to data directory
     */
    protected function moveJsonFiles(string $sourceDir, string $destDir): void
    {
        $files = File::allFiles($sourceDir);
        $moved = 0;

        foreach ($files as $file) {
            $extension = $file->getExtension();
            if (in_array(strtolower($extension), ['json', 'sql'])) {
                $fileName = $file->getFilename();
                $destPath = $destDir . '/' . $fileName;
                
                // Don't overwrite existing files unless forced
                if (!$this->option('force') && File::exists($destPath)) {
                    continue;
                }
                
                File::move($file->getPathname(), $destPath);
                $moved++;
            }
        }

        if ($moved > 0) {
            $this->info("  ✓ Extracted and moved {$moved} file(s)");
        }
    }

    /**
     * Check if location data files already exist
     */
    protected function filesExist(): bool
    {
        $files = ['regions.json', 'cities.json', 'districts.json'];
        
        foreach ($files as $file) {
            if (File::exists($this->dataPath . '/' . $file)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

