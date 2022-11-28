<?php

namespace SkylarkSoft\GoRMG\Merchandising\Commands;

use Illuminate\Console\Command;
use SkylarkSoft\GoRMG\Merchandising\Services\ProTracker\ProTrackerDataService;
use Throwable;

class ProTracker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'color-size-breakdown-for-protracker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Data rearrange and save to desire table for protracker from order table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Throwable
     */
    public function handle()
    {
        $proTrackerService = new ProTrackerDataService();
        $message = $proTrackerService->processAllPurchaseOrders();
        $this->info($message);

        return 0;
    }
}
