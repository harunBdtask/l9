<?php

namespace SkylarkSoft\GoRMG\Washingdroplets\Observers;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Washingdroplets\Models\Washing;
use Carbon\Carbon;

class WashingObserver
{
    /**
     * Handle the washing "created" event.
     *
     * @param  \SkylarkSoft\GoRMG\Washingdroplets\Models\Washing $washing
     * @return void
     */
    public function created(Washing $washing)
    {
        $bundleCard = $washing->bundlecard;
        $washSentQty = $bundleCard->quantity 
            - $bundleCard->total_rejection 
            - $bundleCard->print_rejection 
            - $bundleCard->embroidary_rejection 
            - $bundleCard->sewing_rejection;

        $this->updateTotalProductionReportForWashingSent($washing, $washSentQty);      
        $this->updateDateAndColorWiseProductionForWashingSent($washing, $washSentQty);
    }

    public function updateTotalProductionReportForWashingSent($washing, $washSentQty)
    {
        $washingReport = TotalProductionReport::where([
            'purchase_order_id' => $washing->purchase_order_id, 
            'color_id' => $washing->color_id
        ])->first();

        if (!$washingReport) {
            $washingReport = new TotalProductionReport();
            $washingReport->buyer_id = $washing->buyer_id;
            $washingReport->order_id = $washing->order_id;
            $washingReport->purchase_order_id = $washing->purchase_order_id;
            $washingReport->color_id = $washing->color_id;
        }
        /*if ($washingReport->updated_at && $washingReport->updated_at->toDateString() == Carbon::today()->toDateString()) {
            $washingReport->todays_washing_sent += $washSentQty;
        }*/
        $washingReport->todays_washing_sent += $washSentQty;
        $washingReport->total_washing_sent += $washSentQty;
        $washingReport->save();

        return true;
    }

    private function updateDateAndColorWiseProductionForWashingSent($washing, $washSentQty)
    {
        $washingDate = $washing->updated_at->toDateString();
        $purchaseOrderId = $washing->purchase_order_id;
        $colorId = $washing->color_id;

        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $washingDate,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
        ])->first();

        if (!$dateAndColorWiseProduction) {
            $dateAndColorWiseProduction = new DateAndColorWiseProduction();

            $dateAndColorWiseProduction->buyer_id = $washing->buyer_id;
            $dateAndColorWiseProduction->order_id = $washing->order_id;
            $dateAndColorWiseProduction->purchase_order_id = $purchaseOrderId;
            $dateAndColorWiseProduction->color_id = $colorId;
            $dateAndColorWiseProduction->production_date = $washingDate;
        }
        $dateAndColorWiseProduction->washing_sent_qty += $washSentQty;
        $save = $dateAndColorWiseProduction->save();

        return true;
    }
    /**
     * Handle the washing "updated" event.
     *
     * @param  \SkylarkSoft\GoRMG\Washingdroplets\Models\Washing $washing
     * @return void
     */
    public function updated(Washing $washing)
    {
        if ($washing->isDirty('received_status') && $washing->received_status == 1) {
            $bundleCard = $washing->bundlecard;

            $washReceiveQty = $bundleCard->quantity 
                - $bundleCard->total_rejection 
                - $bundleCard->print_rejection 
                - $bundleCard->embroidary_rejection 
                - $bundleCard->sewing_rejection;
            
            $this->updateTotalProductionReportForWashingReceived($washing, $washReceiveQty);           
            $this->updateDateAndColorWiseProductionForWashingReceived($washing, $washReceiveQty);
        }
    }

    public function updateTotalProductionReportForWashingReceived($washing, $washReceiveQty)
    {
        $washingReport = TotalProductionReport::where([
            'purchase_order_id' => $washing->purchase_order_id, 
            'color_id' => $washing->color_id
        ])->first();

        if (!$washingReport) {
            $washingReport = new TotalProductionReport();
            $washingReport->buyer_id = $washing->buyer_id;
            $washingReport->order_id = $washing->order_id;
            $washingReport->purchase_order_id = $washing->purchase_order_id;
            $washingReport->color_id = $washing->color_id;
        }

        if ($washingReport->updated_at && $washingReport->updated_at->toDateString() == Carbon::today()->toDateString()) {
            $washingReport->todays_washing_received += $washReceiveQty;  
        }

        $washingReport->total_washing_received += $washReceiveQty;
        $washingReport->save();

        return true;
    }    

    private function updateDateAndColorWiseProductionForWashingReceived($washing, $washReceiveQty)
    {
        $washingDate = $washing->updated_at->toDateString();
        $purchaseOrderId = $washing->purchase_order_id;
        $colorId = $washing->color_id;

        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $washingDate,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
        ])->first();

        if (!$dateAndColorWiseProduction) {
            $dateAndColorWiseProduction = new DateAndColorWiseProduction();

            $dateAndColorWiseProduction->buyer_id = $washing->buyer_id;
            $dateAndColorWiseProduction->order_id = $washing->order_id;
            $dateAndColorWiseProduction->purchase_order_id = $purchaseOrderId;
            $dateAndColorWiseProduction->color_id = $colorId;
            $dateAndColorWiseProduction->production_date = $washingDate;
        }
        $dateAndColorWiseProduction->washing_received_qty += $washReceiveQty;
        $dateAndColorWiseProduction->save();

        return true;
    }
    /**
     * Handle the washing "deleted" event.
     *
     * @param  \SkylarkSoft\GoRMG\Washingdroplets\Models\Washing $washing
     * @return void
     */
    public function deleted(Washing $washing)
    {
        //
    }

    /**
     * Handle the washing "restored" event.
     *
     * @param  \SkylarkSoft\GoRMG\Washingdroplets\Models\Washing $washing
     * @return void
     */
    public function restored(Washing $washing)
    {
        //
    }

    /**
     * Handle the washing "force deleted" event.
     *
     * @param  \SkylarkSoft\GoRMG\Washingdroplets\Models\Washing $washing
     * @return void
     */
    public function forceDeleted(Washing $washing)
    {
        //
    }
}
