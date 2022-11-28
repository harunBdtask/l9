<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Observers;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\Poly;
use Carbon\Carbon;

class ShipmentObserver
{
    /**
     * Handle the poly "created" event.
     *
     * @param  \App\Poly  $poly
     * @return void
     */
    public function created(Poly $poly)
    {        
        $polyQty = $poly->poly_qty;
        $rejection = $poly->rejection_qty; 

        $this->updateTotalProductionReport($poly, $polyQty, $rejection);
        $this->updateDateAndColorWiseReport($poly, $polyQty, $rejection);
    }

    private function updateTotalProductionReport($poly, $polyQty, $rejection)
    {
        $totalProductionReport = TotalProductionReport::where([
            'purchase_order_id' => $poly->purchase_order_id,
            'color_id' => $poly->color_id
        ])->first();    
        
        if (!$totalProductionReport) {
            $totalProductionReport = new TotalProductionReport();
            $totalProductionReport->buyer_id = $poly->buyer_id;            
            $totalProductionReport->order_id = $poly->order_id;
            $totalProductionReport->purchase_order_id = $poly->purchase_order_id;
            $totalProductionReport->color_id = $poly->color_id;
        }
        $totalProductionReport->todays_poly += $polyQty;
        $totalProductionReport->total_poly += $polyQty;
        $totalProductionReport->todays_poly_rejection += $rejection;
        $totalProductionReport->total_poly_rejection += $rejection;
        $totalProductionReport->save();  
    }

    private function updateDateAndColorWiseReport($poly, $polyQty, $rejection)
    {
        $orderId = $poly->order_id;
        $colorId = $poly->color_id;

        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => date('Y-m-d'),
            'order_id' => $orderId,
            'color_id' => $colorId,
        ])->first();

        if (!$dateAndColorWiseProduction) {
            $dateAndColorWiseProduction = new DateAndColorWiseProduction();

            $dateAndColorWiseProduction->buyer_id = $poly->buyer_id;            
            $dateAndColorWiseProduction->order_id = $orderId;
            $dateAndColorWiseProduction->purchase_order_id = $poly->purchase_order_id;
            $dateAndColorWiseProduction->color_id = $colorId;
            $dateAndColorWiseProduction->production_date = date('Y-m-d');
        }

        $dateAndColorWiseProduction->poly_qty += $polyQty;        
        $dateAndColorWiseProduction->poly_rejection += $rejection;
        $dateAndColorWiseProduction->save();
    }

    /**
     * Handle the poly "updated" event.
     *
     * @param  \App\Poly  $poly
     * @return void
     */
    public function updated(Poly $poly)
    {
        $original = $poly->getOriginal();
        $oldPolyQty = $original['poly_qty'];
        $newPolyQty = $poly->poly_qty;
        $oldRejectionQty = $original['rejection_qty'];
        $newRejectionQty = $poly->rejection_qty;

        if($poly->isDirty('poly_qty') || $poly->isDirty('rejection_qty')) {
            $this->updateTotalProductionReportUpdate($poly, $oldPolyQty, $newPolyQty, $oldRejectionQty, $newRejectionQty);
            $this->updateDateAndColorWiseReportForPolyUpdate($poly, $oldPolyQty, $newPolyQty, $oldRejectionQty, $newRejectionQty);
        }
    }

    private function updateTotalProductionReportUpdate($poly, $oldPolyQty, $newPolyQty, $oldRejectionQty, $newRejectionQty)
    {
        $totalProductionReport = TotalProductionReport::where([
            'order_id' => $poly->order_id,
            'color_id' => $poly->color_id
        ])->first();

        if($totalProductionReport) {
            if ($poly->updated_at->toDateString() == Carbon::today()->toDateString()) {
                $totalProductionReport->todays_poly -= $oldPolyQty;
                $totalProductionReport->todays_poly += $newPolyQty;
                $totalProductionReport->todays_poly_rejection -= $oldRejectionQty;
                $totalProductionReport->todays_poly_rejection += $newRejectionQty;
            }

            $totalProductionReport->total_poly -= $oldPolyQty;
            $totalProductionReport->total_poly += $newPolyQty;
            $totalProductionReport->total_poly_rejection -= $oldRejectionQty;
            $totalProductionReport->total_poly_rejection += $newRejectionQty;
            $totalProductionReport->save();
        }
    }

    private function updateDateAndColorWiseReportForPolyUpdate($poly, $oldPolyQty, $newPolyQty, $oldRejectionQty, $newRejectionQty)
    {
        $orderId = $poly->order_id;
        $colorId = $poly->color_id;

        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $poly->created_at->toDateString(),
            'order_id' => $orderId,
            'color_id' => $colorId,
        ])->first();

        if ($dateAndColorWiseProduction) {
            $dateAndColorWiseProduction->poly_qty -= $oldPolyQty;
            $dateAndColorWiseProduction->poly_qty += $newPolyQty;
            $dateAndColorWiseProduction->poly_rejection -= $oldRejectionQty;
            $dateAndColorWiseProduction->poly_rejection += $newRejectionQty;
            $dateAndColorWiseProduction->save();
        }
    }

    /**
     * Handle the poly cartoon "deleted" event.
     *
     * @param  \SkylarkSoft\GoRMG\Finishingdroplets\Models\PolyCartoon  $poly
     * @return void
     */
    public function deleted(Poly $poly)
    {
        $poly_qty = $poly->poly_qty;
       
        $totalProductionReport = TotalProductionReport::where([
            'order_id' => $poly->order_id, 
            'color_id' => $poly->color_id
        ])->first();
        
        if ($poly->created_at && $poly->created_at->toDateString() == Carbon::today()->toDateString()) {

            $totalProductionReport->decrement('todays_poly', $poly_qty ?? 0);
            $totalProductionReport->decrement('todays_poly_rejection', $poly->rejection_qty ?? 0);
        }

        $totalProductionReport->decrement('total_poly', $poly_qty ?? 0);
        $totalProductionReport->decrement('total_poly_rejection', $poly->rejection_qty ?? 0);
        $totalProductionReport->save();

        // Date wise poly update
        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $poly->created_at->toDateString(),
            'order_id' => $poly->order_id,
            'color_id' => $poly->color_id,
        ])->first();

        if ($dateAndColorWiseProduction) {
            $dateAndColorWiseProduction->poly_qty -= $poly_qty;          
            $dateAndColorWiseProduction->poly_rejection -= $poly->rejection_qty;
            $dateAndColorWiseProduction->save();
        }
    }


    /**
     * Handle the poly "restored" event.
     *
     * @param  \App\Poly  $poly
     * @return void
     */
    public function restored(Poly $poly)
    {
        //
    }

    /**
     * Handle the poly "force deleted" event.
     *
     * @param  \App\Poly  $poly
     * @return void
     */
    public function forceDeleted(Poly $poly)
    {
        //
    }
}
