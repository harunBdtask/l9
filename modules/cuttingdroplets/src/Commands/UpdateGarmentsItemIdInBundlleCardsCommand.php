<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateGarmentsItemIdInBundlleCardsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-garments-item-id-in-bundlecard-related-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update garments_item_id In BundleCard related tables';

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
            DB::table('bundle_card_generation_details')
                ->whereNull('garments_item_id')
                ->whereNull('deleted_at')
                ->orderBy('id', 'asc')
                ->chunk(200, function ($bundle_card_generation_details) use (&$counter) {
                    foreach ($bundle_card_generation_details as $bundle_card_generation_detail) {
                        $order = DB::table('orders')->where('id', $bundle_card_generation_detail->order_id)->first();
                        $order_item_details = collect(json_decode($order->item_details))->toArray();
                        if ($order && $order_item_details && array_key_exists('details', $order_item_details)) {
                            $item_details = collect($order_item_details['details']);
                            $garments_item_id = $item_details->first() && isset($item_details->first()->item_id) ? $item_details->first()->item_id : null;
                        }
                        if ($garments_item_id) {
                            $this->info("SID: " . $bundle_card_generation_detail->id);
                            DB::table('bundle_card_generation_details')
                                ->where('id', $bundle_card_generation_detail->id)
                                ->update([
                                    'garments_item_id' => $garments_item_id
                                ]);
                            DB::table('bundle_cards')
                                ->where('bundle_card_generation_detail_id', $bundle_card_generation_detail->id)
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
