<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Observers;

use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintEmbrProductionReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintFactoryReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintProductionReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintReceiveInventoryChallan;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use Carbon\Carbon;
use DB;

class PrintFactoryObserver
{
    /**
     * Handle the print inventory challan "created" event.
     *
     * @param  \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan $printInventoryChallan
     * @return void
     */


    public function created(PrintReceiveInventoryChallan $printInventoryChallan)
    {

        $challanSent = PrintReceiveInventoryChallan::where([
            'operation_name' => $printInventoryChallan->operation_name,
            'challan_no' => $printInventoryChallan->challan_no
        ])->count();

        if ($challanSent == 1) {
            $totalReceiveQty = 0;
            foreach ($printInventoryChallan->print_receive_inventories as $printInventory) {
                $bundleCard = $printInventory->bundle_card;
                $bundle_card_id = $printInventory->bundle_card_id;
                $buyerId = $bundleCard->buyer_id;
                $orderId = $bundleCard->order_id;
                $purchaseOrderId = $bundleCard->purchase_order_id;
                $colorId = $bundleCard->color_id;
                $sizeId = $bundleCard->size_id;
                $bundleQty = $bundleCard->quantity - $bundleCard->total_rejection - $bundleCard->print_factory_receive_rejection;
                $totalReceiveQty += $bundleQty;
                $printReceive = 0;
                $embroidaryReceive = 0;

                if ($printInventoryChallan->operation_name == PRNT) {
                    $printReceive = $bundleQty;
                } elseif ($printInventoryChallan->operation_name == EMBROIDARY) {
                    $embroidaryReceive = $bundleQty;
                }

                if ($embroidaryReceive > 0 || $printReceive > 0) {
                    $sendDate = $printInventoryChallan->updated_at->toDateString();
                    $this->updateDateWisePrintEmbrProductionReport($bundle_card_id, $buyerId, $orderId, $purchaseOrderId, $colorId, $sizeId, $printReceive, $embroidaryReceive, $sendDate);
                }
            }


        }
    }

    /**
     * Handle the print inventory challan "updated" event.
     *
     * @param  \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan $printInventoryChallan
     * @return void
     */
    public function updated(PrintReceiveInventoryChallan $printInventoryChallan)
    {
        $challanSent = PrintReceiveInventoryChallan::where([
            'operation_name' => $printInventoryChallan->operation_name,
            'challan_no' => $printInventoryChallan->challan_no
        ])->count();

        if ($challanSent == 1) {
            $totalReceiveQty = 0;
            foreach ($printInventoryChallan->print_receive_inventories as $printInventory) {
                $bundleCard = $printInventory->bundle_card;
                $bundle_card_id = $printInventory->bundle_card_id;
                $buyerId = $bundleCard->buyer_id;
                $orderId = $bundleCard->order_id;
                $purchaseOrderId = $bundleCard->purchase_order_id;
                $colorId = $bundleCard->color_id;
                $sizeId = $bundleCard->size_id;
                $bundleQty = $bundleCard->quantity - $bundleCard->total_rejection - $bundleCard->print_factory_receive_rejection;
                $totalReceiveQty += $bundleQty;
                $printReceive = 0;
                $embroidaryReceive = 0;

                if ($printInventoryChallan->operation_name == PRNT) {
                    $printReceive = $bundleQty;
                } elseif ($printInventoryChallan->operation_name == EMBROIDARY) {
                    $embroidaryReceive = $bundleQty;
                }

                if ($embroidaryReceive > 0 || $printReceive > 0) {
                    $sendDate = $printInventoryChallan->updated_at->toDateString();
                    $this->updateDateWisePrintEmbrProductionReport($bundle_card_id, $buyerId, $orderId, $purchaseOrderId, $colorId, $sizeId, $printReceive, $embroidaryReceive, $sendDate);
                }
            }


        }
    }

    private function updateDateWisePrintEmbrProductionReport($bundle_card_id, $buyerId, $orderId, $purchaseOrderId, $colorId, $sizeId, $printReceive, $embroidaryReceive, $sendDate)
    {
        $dateAndSizeWiseProduction = DateWisePrintFactoryReport::where([
            'production_date' => $sendDate,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'size_id' => $sizeId,
            'bundle_card_id' => $bundle_card_id,
        ])->first();

        if (!$dateAndSizeWiseProduction) {
            $dateAndSizeWiseProduction = new DateWisePrintFactoryReport();
            $dateAndSizeWiseProduction->production_date = $sendDate;
            $dateAndSizeWiseProduction->buyer_id = $buyerId;
            $dateAndSizeWiseProduction->order_id = $orderId;
            $dateAndSizeWiseProduction->purchase_order_id = $purchaseOrderId;
            $dateAndSizeWiseProduction->color_id = $colorId;
            $dateAndSizeWiseProduction->size_id = $sizeId;
            $dateAndSizeWiseProduction->bundle_card_id = $bundle_card_id;
        }

        $dateAndSizeWiseProduction->print_received_qty += $printReceive;
        $dateAndSizeWiseProduction->embroidery_received_qty += $embroidaryReceive;
        $dateAndSizeWiseProduction->save();

        return true;
    }

    /**
     * Handle the print inventory challan "deleted" event.
     *
     * @param  \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan $printInventoryChallan
     * @return void
     */
    public function deleted(PrintReceiveInventoryChallan $printInventoryChallan)
    {
        //
    }

    /**
     * Handle the print inventory challan "restored" event.
     *
     * @param  \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan $printInventoryChallan
     * @return void
     */
    public function restored(PrintReceiveInventoryChallan $printInventoryChallan)
    {
        //
    }

    /**
     * Handle the print inventory challan "force deleted" event.
     *
     * @param  \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan $printInventoryChallan
     * @return void
     */
    public function forceDeleted(PrintReceiveInventoryChallan $printInventoryChallan)
    {
        //
    }
}
