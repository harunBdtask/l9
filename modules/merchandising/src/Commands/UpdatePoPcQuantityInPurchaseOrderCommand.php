<?php

namespace SkylarkSoft\GoRMG\Merchandising\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdatePoPcQuantityInPurchaseOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:po-pc-qty-in-purchase-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update PoPcQuantity In PurchaseOrder Model';

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
     */
    public function handle()
    {
        try {
            $counter = 0;
            $chunk_data_count = 500;
            $this->info("Execution started successfully!");
            DB::beginTransaction();
            DB::table('purchase_orders')
                ->select('id')
                ->whereNull('deleted_at')
                ->orderBy('id', 'asc')
                ->chunk($chunk_data_count, function ($purchase_orders) use (&$counter) {
                    foreach ($purchase_orders as $purchase_order) {
                        $pcs_qty = DB::table('purchase_order_details')
                            ->whereNull('deleted_at')
                            ->where('purchase_order_id', $purchase_order->id)
                            ->sum('quantity');
                        DB::table('purchase_orders')
                        ->where('id', $purchase_order->id)
                        ->update([
                            'po_pc_quantity' => $pcs_qty
                        ]);
                        $this->info("Updated Data count: " . ++$counter);
                    }
                });
            $this->info("Execution ended successfully!");
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
        }
    }
}
