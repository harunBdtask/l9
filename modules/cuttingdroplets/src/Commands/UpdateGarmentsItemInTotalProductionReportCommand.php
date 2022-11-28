<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateGarmentsItemInTotalProductionReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-garments-item-id-in-total-production-report-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update garments_item_id In total_production_reports table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info("Execution Started!");
            $counter = 0;
            DB::table('total_production_reports')
                ->select('order_id')
                ->whereNull('garments_item_id')
                ->groupBy('order_id')
                ->orderBy('order_id', 'asc')
                ->chunk(200, function ($total_production_reports) use (&$counter) {
                    foreach ($total_production_reports as $total_production_report) {
                        $order = DB::table('orders')->where('id', $total_production_report->order_id)->first();
                        $order_item_details = collect(json_decode($order->item_details))->toArray();
                        if ($order && $order_item_details && array_key_exists('details', $order_item_details)) {
                            $item_details = collect($order_item_details['details']);
                            $garments_item_id = $item_details->first() && isset($item_details->first()->item_id) ? $item_details->first()->item_id : null;
                        }
                        if ($garments_item_id) {
                            DB::table('total_production_reports')
                                ->where('order_id', $total_production_report->order_id)
                                ->update([
                                    'garments_item_id' => $garments_item_id
                                ]);
                            ++$counter;
                            $this->info("Counter: " . $counter);
                        }
                    }
                });
            $this->info("Execution Ended!");
        } catch (Exception $e) {
            $this->info("Something went wrong!");
            $this->info($e->getMessage());
        }
        return 0;
    }
}
