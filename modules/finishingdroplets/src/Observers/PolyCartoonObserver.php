<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Observers;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\PolyCartoon;
use Carbon\Carbon;

class PolyCartoonObserver
{
    /**
     * Handle the poly cartoon "created" event.
     *
     * @param  \SkylarkSoft\GoRMG\Finishingdroplets\Models\PolyCartoon  $polyCartoon
     * @return void
     */
    public function created(PolyCartoon $polyCartoon)
    {        
        $this->updateTotalProductionReport($polyCartoon);
        $this->updateDateAndColorWiseReport($polyCartoon);
    }

    private function updateTotalProductionReport($polyCartoon)
    {
        $purchaseOrderId = $polyCartoon->purchase_order_id;
        $colorId = $polyCartoon->color_id;

        $polyCartoonReport = TotalProductionReport::where([
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId
        ])->first();

        if (!$polyCartoonReport) {
            $polyCartoonReport = new TotalProductionReport();
            $polyCartoonReport->buyer_id = $polyCartoon->buyer_id;
            $polyCartoonReport->order_id = $polyCartoon->order_id;
            $polyCartoonReport->purchase_order_id = $purchaseOrderId;
            $polyCartoonReport->color_id = $colorId;
        }

        // for today
        $polyCartoonReport->todays_received_for_poly += $polyCartoon->received_qty;
        $polyCartoonReport->todays_poly += $polyCartoon->poly_qty;
        $polyCartoonReport->todays_pcs += $polyCartoon->poly_qty * $polyCartoon->qty_per_poly;
        $polyCartoonReport->todays_cartoon += $polyCartoon->cartoon_qty;
        
        // for total
        $polyCartoonReport->total_received_for_poly += $polyCartoon->received_qty;
        $polyCartoonReport->total_poly += $polyCartoon->poly_qty;
        $polyCartoonReport->total_pcs += $polyCartoon->poly_qty * $polyCartoon->qty_per_poly; 
        $polyCartoonReport->total_cartoon += $polyCartoon->cartoon_qty;
        $polyCartoonReport->save();
    }

    private function updateDateAndColorWiseReport($polyCartoon)
    {
        $purchaseOrderId = $polyCartoon->purchase_order_id;
        $colorId = $polyCartoon->color_id;

        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => date('Y-m-d'),
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
        ])->first();

        if (!$dateAndColorWiseProduction) {
            $dateAndColorWiseProduction = new DateAndColorWiseProduction();
            $dateAndColorWiseProduction->buyer_id = $polyCartoon->buyer_id;
            $dateAndColorWiseProduction->order_id = $polyCartoon->order_id;
            $dateAndColorWiseProduction->purchase_order_id = $purchaseOrderId;
            $dateAndColorWiseProduction->color_id = $colorId;
            $dateAndColorWiseProduction->production_date = date('Y-m-d');
        }
        
        $dateAndColorWiseProduction->received_for_poly += $polyCartoon->received_qty;
        $dateAndColorWiseProduction->poly_qty += $polyCartoon->poly_qty;
        $dateAndColorWiseProduction->total_pcs += $polyCartoon->poly_qty * $polyCartoon->qty_per_poly;
        $dateAndColorWiseProduction->total_cartoon += $polyCartoon->cartoon_qty;
        $dateAndColorWiseProduction->save();
    }
    /**
     * Handle the poly cartoon "updated" event.
     *
     * @param  \SkylarkSoft\GoRMG\Finishingdroplets\Models\PolyCartoon  $polyCartoon
     * @return void
     */
    public function updated(PolyCartoon $polyCartoon)
    {
        //
    }

    /**
     * Handle the poly cartoon "deleted" event.
     *
     * @param  \SkylarkSoft\GoRMG\Finishingdroplets\Models\PolyCartoon  $polyCartoon
     * @return void
     */
    public function deleted(PolyCartoon $polyCartoon)
    {
        $this->deleteTotalProduction($polyCartoon);
        $this->deleteDateAndColorWiseReport($polyCartoon);
    }

    private function deleteTotalProduction(PolyCartoon $polyCartoon)
    {
        $purchaseOrderId = $polyCartoon->purchase_order_id;
        $colorId = $polyCartoon->color_id;

        $polyCartoonReport = TotalProductionReport::where([
            'purchase_order_id' => $purchaseOrderId, 
            'color_id' => $colorId
        ])->first();

        if ($polyCartoon->created_at && $polyCartoon->created_at->toDateString() == Carbon::today()->toDateString()) {
            $polyCartoonReport->decrement('todays_received_for_poly', $polyCartoon->received_qty);
            $polyCartoonReport->decrement('todays_poly', $polyCartoon->poly_qty);
            $polyCartoonReport->decrement('todays_pcs', $polyCartoon->poly_qty * $polyCartoon->qty_per_poly);
            $polyCartoonReport->decrement('todays_cartoon', $polyCartoon->cartoon_qty);            
        } 
        
        $polyCartoonReport->decrement('total_received_for_poly', $polyCartoon->received_qty);
        $polyCartoonReport->decrement('total_poly', $polyCartoon->poly_qty);
        $polyCartoonReport->decrement('total_pcs', $polyCartoon->poly_qty * $polyCartoon->qty_per_poly);
        $polyCartoonReport->decrement('total_cartoon', $polyCartoon->cartoon_qty);
        $polyCartoonReport->save();
    }

    private function deleteDateAndColorWiseReport(PolyCartoon $polyCartoon)
    {
        $purchaseOrderId = $polyCartoon->purchase_order_id;
        $colorId = $polyCartoon->color_id;

        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $polyCartoon->created_at->toDateString(),
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
        ])->first();

        $dateAndColorWiseProduction->received_for_poly -= $polyCartoon->received_qty;
        $dateAndColorWiseProduction->poly_qty -= $polyCartoon->poly_qty;
        $dateAndColorWiseProduction->total_pcs -= $polyCartoon->poly_qty * $polyCartoon->qty_per_poly;
        $dateAndColorWiseProduction->total_cartoon -= $polyCartoon->cartoon_qty;
        $dateAndColorWiseProduction->save();
    }

    /**
     * Handle the poly cartoon "restored" event.
     *
     * @param  \SkylarkSoft\GoRMG\Finishingdroplets\Models\PolyCartoon  $polyCartoon
     * @return void
     */
    public function restored(PolyCartoon $polyCartoon)
    {
        //
    }

    /**
     * Handle the poly cartoon "force deleted" event.
     *
     * @param  \SkylarkSoft\GoRMG\Finishingdroplets\Models\PolyCartoon  $polyCartoon
     * @return void
     */
    public function forceDeleted(PolyCartoon $polyCartoon)
    {
        //
    }
}
