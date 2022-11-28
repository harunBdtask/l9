<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Observers;

use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateFloorWisePrintEmbrReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintEmbrProductionReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintProductionReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use Carbon\Carbon;
use DB;

class PrintInventoryObserver
{
    /**
     * Handle the print inventory "created" event.
     *
     * @param  \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory $printInventory
     * @return void
     */
    public function created(PrintInventory $printInventory)
    {

    }

    /**
     * Handle the print inventory "updated" event.
     *
     * @param  \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory $printInventory
     * @return void
     */
    public function updated(PrintInventory $printInventory)
    {

    }

    /**
     * Handle the print inventory "deleted" event.
     *
     * @param  \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory $printInventory
     * @return void
     */
    public function deleted(PrintInventory $printInventory)
    {
        $bundleCard = $printInventory->bundle_card;
        $orderId = $bundleCard->order_id;
        $garmentsItemId = $bundleCard->garments_item_id;
        $purchaseOrderId = $bundleCard->purchase_order_id;
        $colorId = $bundleCard->color_id;

        $printInventoryChallan = $printInventory->printInventoryChallan;
        if ($printInventoryChallan) {
            $orderWiseRep = TotalProductionReport::where([
                'order_id' => $orderId,
                'garments_item_id' => $garmentsItemId,
                'purchase_order_id' => $purchaseOrderId,
                'color_id' => $colorId
            ])->first();
            
            $operation_name = $printInventoryChallan->operation_name;
            $bundle_qty = $bundleCard->quantity - $bundleCard->total_rejection;
            if ($orderWiseRep) {
                if ($operation_name == 1) {
                    if ($bundleCard->print_sent_date == date('Y-m-d')) {
                        $orderWiseRep->decrement('todays_sent', $bundle_qty);
                    }
                    $orderWiseRep->decrement('total_sent', $bundle_qty);
                    DB::table('bundle_cards')
                        ->where('id', $printInventory->bundle_card_id)
                        ->update([
                            'print_sent_date' => null,
                            'print_embr_send_scan_time' => null
                        ]);
                    $this->updateDateWisePrintProductionReportForChallanDelete($bundleCard);
                } else {
                    if ($bundleCard->embroidary_sent_date == date('Y-m-d')) {
                        $orderWiseRep->decrement('todays_embroidary_sent', $bundle_qty);
                    }
                    $orderWiseRep->decrement('total_embroidary_sent', $bundle_qty);
                    DB::table('bundle_cards')
                        ->where('id', $printInventory->bundle_card_id)
                        ->update([
                            'embroidary_sent_date' => null,
                            'print_embr_send_scan_time' => null
                        ]);
                }
            }
            $this->updateColorAndDateWisePrintEmbrSent($bundleCard, $operation_name);
            $this->updateDateWisePrintEmbrProductionReport($bundleCard, $operation_name);
            $this->updateDateFloorWisePrintEmbrReport($bundleCard, $operation_name);

            $total_sent_qty = $printInventoryChallan->send_total_qty - $bundle_qty;

            DB::table('print_inventory_challans')->where('id', $printInventoryChallan->id)->update([
                'send_total_qty' => $total_sent_qty
            ]);
        }
    }

    private function updateDateWisePrintEmbrProductionReport($bundleCard, $operation_name)
    {
        if ($operation_name == 1) {
            $sent_date = $bundleCard->print_sent_date;
            $decremented_column = 'print_sent_qty';
        } else {
            $sent_date = $bundleCard->embroidary_sent_date;
            $decremented_column = 'embroidery_sent_qty';
        }
        $dateAndSizeWiseInfo = DateWisePrintEmbrProductionReport::where([
            'purchase_order_id' => $bundleCard->purchase_order_id,
            'color_id' => $bundleCard->color_id,
            'size_id' => $bundleCard->size_id,
            'production_date' => $sent_date
        ])->first();

        if ($dateAndSizeWiseInfo) {
            $dateAndSizeWiseInfo->decrement($decremented_column, ($bundleCard->quantity - $bundleCard->total_rejection));
        }
    }

    private function updateDateFloorWisePrintEmbrReport($bundleCard, $operation_name)
    {
        if ($operation_name == 1) {
            $sent_date = $bundleCard->print_sent_date;
            $decremented_column = 'print_sent_qty';
        } else {
            $sent_date = $bundleCard->embroidary_sent_date;
            $decremented_column = 'embroidery_sent_qty';
        }
        $dateAndSizeWiseInfo = DateFloorWisePrintEmbrReport::where([
            'cutting_floor_id' => $bundleCard->cutting_floor_id,
            'garments_item_id' => $bundleCard->garments_item_id,
            'purchase_order_id' => $bundleCard->purchase_order_id,
            'color_id' => $bundleCard->color_id,
            'production_date' => $sent_date
        ])->first();

        if ($dateAndSizeWiseInfo) {
            $dateAndSizeWiseInfo->decrement($decremented_column, ($bundleCard->quantity - $bundleCard->total_rejection));
        }
    }

    public function updateColorAndDateWisePrintEmbrSent($bundleCard, $operation_name)
    {
        if ($operation_name == 1) {
            $sent_date = $bundleCard->print_sent_date;
            $decremented_column = 'print_sent_qty';
        } else {
            $sent_date = $bundleCard->embroidary_sent_date;
            $decremented_column = 'embroidary_sent_qty';
        }
        $dateAndColorWiseInfo = DateAndColorWiseProduction::where([
            'purchase_order_id' => $bundleCard->purchase_order_id,
            'color_id' => $bundleCard->color_id,
            'production_date' => $sent_date
        ])->first();

        if ($dateAndColorWiseInfo) {
            $dateAndColorWiseInfo->decrement($decremented_column, ($bundleCard->quantity - $bundleCard->total_rejection));
        }
    }

    public function updateDateWisePrintProductionReportForChallanDelete($bundleCard)
    {
        $print_date = $bundleCard->print_sent_date;
        $purchaseOrderId = $bundleCard->purchase_order_id;
        $colorId = $bundleCard->color_id;

        $deleted_qty = $bundleCard->quantity - $bundleCard->total_rejection;

        $date_wise_print_production_report = DateWisePrintProductionReport::where('print_date', $print_date)->first();
        if ($date_wise_print_production_report) {
            $print_details_existing_data = $date_wise_print_production_report->print_details;
            $total_print_sent = $date_wise_print_production_report->total_print_sent;
            $total_print_received = $date_wise_print_production_report->total_print_received;
            $total_print_rejection = $date_wise_print_production_report->total_print_rejection;
            $total_print_sent -= $deleted_qty;
            foreach ($print_details_existing_data as $key => $print_detail) {
                $is_detail_exist = 0;
                if ($print_detail['purchase_order_id'] == $purchaseOrderId && $print_detail['color_id'] == $colorId) {
                    $is_detail_exist = 1;
                    $print_details_existing_data[$key] = [
                        'buyer_id' => $print_detail['buyer_id'],
                        'order_id' => $print_detail['order_id'],
                        'purchase_order_id' => $print_detail['purchase_order_id'],
                        'color_id' => $print_detail['color_id'],
                        'print_sent' => $print_detail['print_sent'] - $deleted_qty ?? 0,
                        'print_received' => $print_detail['print_received'] ?? 0,
                        'print_rejection' => $print_detail['print_rejection'] ?? 0,
                    ];
                    break;
                }
            }
            if ($is_detail_exist == 1) {
                $date_wise_print_production_report->total_print_sent = $total_print_sent ?? 0;
                $date_wise_print_production_report->total_print_received = $total_print_received ?? 0;
                $date_wise_print_production_report->total_print_rejection = $total_print_rejection ?? 0;
                $date_wise_print_production_report->print_details = $print_details_existing_data;
                $date_wise_print_production_report->save();
            }
        }
        return true;
    }

    /**
     * Handle the print inventory "restored" event.
     *
     * @param  \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory $printInventory
     * @return void
     */
    public function restored(PrintInventory $printInventory)
    {
        //
    }

    /**
     * Handle the print inventory "force deleted" event.
     *
     * @param  \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory $printInventory
     * @return void
     */
    public function forceDeleted(PrintInventory $printInventory)
    {
        //
    }
}
