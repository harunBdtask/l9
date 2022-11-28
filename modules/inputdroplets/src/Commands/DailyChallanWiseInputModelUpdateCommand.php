<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DailyChallanWiseInputModelUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:daily-challan-wise-input-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Daily Challan Wise Input Report';

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
            $date = $this->validate_cmd(function () {
                return $this->ask('Enter date [Eg: 2016-01-01] to update data for this date');
            }, ['date', 'required|date']);
            $formatedDate = Carbon::parse($date);
            $todayDate = $formatedDate->toDateString();
            $from_date = $formatedDate->subDay()->endOfDay()->toDateTimeString();
            $end_date = $formatedDate->addDays(2)->startOfDay()->toDateTimeString();

            DB::beginTransaction();
            DB::table('daily_challan_wise_inputs')->whereDate('production_date', $todayDate)->delete();
            
            DB::table('cutting_inventory_challans')
                ->select('id', 'challan_no', 'line_id', 'input_date', 'updated_at')
                ->where('type', 'challan')
                ->whereNull('deleted_at')
                ->whereBetween('created_at', [$from_date, $end_date])
                ->orderBy('created_at', 'desc')
                ->chunk($chunk_data_count, function ($data) use (&$counter) {
                    foreach ($data as $cutting_inventory_challan) {

                        $inputDate = $cutting_inventory_challan->input_date;
                        $lineId = $cutting_inventory_challan->line_id;
                        $floorId = DB::table('lines')->find($lineId)->floor_id;
                        $this->updateData($cutting_inventory_challan, $floorId, $lineId, $inputDate);
                        $this->info("Update Challan count: " . ++$counter);
                    }
                });
            DB::commit();
            $this->info("Execution ended successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
        }
    }

    private function updateData($cutting_inventory_challan, $floorId, $lineId, $inputDate)
    {
        $cuttingInventories = DB::table('cutting_inventories')
            ->where('challan_no', $cutting_inventory_challan->challan_no)
            ->whereNull('deleted_at')
            ->get();
        if ($cuttingInventories) {
            foreach ($cuttingInventories as $cuttingInventory) {
                $bundleCard = DB::table('bundle_cards')->where('id', $cuttingInventory->bundle_card_id)->first();
                if ($bundleCard) {

                    $bundleQty = $bundleCard->quantity
                        - $bundleCard->total_rejection
                        - $bundleCard->print_rejection
                        - $bundleCard->embroidary_rejection;
                    $dailyChallanWiseInput = DB::table('daily_challan_wise_inputs')
                        ->where([
                            'floor_id' => $floorId,
                            'line_id' => $lineId,
                            'purchase_order_id' => $bundleCard->purchase_order_id,
                            'garments_item_id' => $bundleCard->garments_item_id,
                            'color_id' => $bundleCard->color_id,
                            'challan_no' => $cuttingInventory->challan_no,
                            'production_date' => $inputDate,
                            'buyer_id' => $bundleCard->buyer_id,
                            'order_id' => $bundleCard->order_id,
                            'factory_id' => $cuttingInventory->factory_id,
                        ])
                        ->first();
                    if (!$dailyChallanWiseInput) {
                        $insertData = [
                            'floor_id' => $floorId,
                            'line_id' => $lineId,
                            'buyer_id' => $bundleCard->buyer_id,
                            'order_id' => $bundleCard->order_id,
                            'purchase_order_id' => $bundleCard->purchase_order_id,
                            'garments_item_id' => $bundleCard->garments_item_id,
                            'color_id' => $bundleCard->color_id,
                            'challan_no' => $cuttingInventory->challan_no,
                            'production_date' => $inputDate,
                            'sewing_input' => $bundleQty,
                            'factory_id' => $cuttingInventory->factory_id,
                            'created_at' => $cutting_inventory_challan->updated_at,
                            'updated_at' => $cutting_inventory_challan->updated_at,
                        ];

                        DB::table('daily_challan_wise_inputs')->insert($insertData);
                    } else {
                        $id = $dailyChallanWiseInput->id;
                        DB::table('daily_challan_wise_inputs')
                            ->where('id', $id)
                            ->update([
                                'sewing_input' => $dailyChallanWiseInput->sewing_input + $bundleQty,
                                'updated_at' => $cutting_inventory_challan->updated_at,
                            ]);
                    }
                }
            }
        }
    }

    /**
     * Validate an input.
     *
     * @param  mixed   $method
     * @param  array   $rules
     * @return string
     */
    public function validate_cmd($method, $rules)
    {
        $value = $method();
        $validate = $this->validateInput($rules, $value);

        if ($validate !== true) {
            $this->warn($validate);
            $value = $this->validate_cmd($method, $rules);
        }
        return $value;
    }

    public function validateInput($rules, $value)
    {

        $validator = Validator::make([$rules[0] => $value], [$rules[0] => $rules[1]]);

        if ($validator->fails()) {
            $error = $validator->errors();
            return $error->first($rules[0]);
        } else {
            return true;
        }
    }
}
