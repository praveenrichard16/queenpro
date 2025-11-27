<?php

namespace App\Console\Commands;

use App\Services\DripCampaignService;
use Illuminate\Console\Command;

class ProcessDripCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drip-campaigns:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled drip campaign messages';

    /**
     * Execute the console command.
     */
    public function handle(DripCampaignService $service)
    {
        $this->info('Processing drip campaigns...');
        
        $service->processScheduledCampaigns();
        
        $this->info('Drip campaigns processed successfully.');
    }
}
