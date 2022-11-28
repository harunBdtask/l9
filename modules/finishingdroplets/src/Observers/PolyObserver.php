<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Observers;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\Poly;
use Carbon\Carbon;

class PolyObserver
{
    /**
     * Handle the poly "created" event.
     *
     * @param  \App\Poly  $poly
     * @return void
     */
    public function created(Poly $poly)
    {

        $this->updateTotalProductionReport($poly);
        $this->updateDateAndColorWiseReport($poly);
    }

    private function updateTotalProductionReport($poly)
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

        if ($poly->production_date == Carbon::today()->toDateString()) {
            $totalProductionReport->todays_poly += $poly->poly_qty;
            $totalProductionReport->todays_poly_rejection += $poly->poly_rejection_qty;

            $totalProductionReport->todays_iron += $poly->iron_qty;
            $totalProductionReport->todays_iron_rejection += $poly->iron_rejection_qty;

            $totalProductionReport->todays_packing += $poly->packing_qty;
            $totalProductionReport->todays_packing_rejection += $poly->packing_rejection_qty;
        }
        
        $totalProductionReport->total_poly += $poly->poly_qty;        
        $totalProductionReport->total_poly_rejection += $poly->poly_rejection_qty;
        
        $totalProductionReport->total_iron += $poly->iron_qty;        
        $totalProductionReport->total_iron_rejection += $poly->iron_rejection_qty;
       
        $totalProductionReport->total_packing += $poly->packing_qty;        
        $totalProductionReport->total_packing_rejection += $poly->packing_rejection_qty;
        $totalProductionReport->save();  
    }

    private function updateDateAndColorWiseReport($poly)
    {
        $orderId = $poly->order_id;
        $colorId = $poly->color_id;
        $productionDate = $poly->production_date;

        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $productionDate,
            'order_id' => $orderId,
            'color_id' => $colorId,
        ])->first();

        if (!$dateAndColorWiseProduction) {
            $dateAndColorWiseProduction = new DateAndColorWiseProduction();
            $dateAndColorWiseProduction->buyer_id = $poly->buyer_id;            
            $dateAndColorWiseProduction->order_id = $orderId;
            $dateAndColorWiseProduction->purchase_order_id = $poly->purchase_order_id;
            $dateAndColorWiseProduction->color_id = $colorId;
            $dateAndColorWiseProduction->production_date = $productionDate;
        }

        $dateAndColorWiseProduction->poly_qty += $poly->poly_qty;
        $dateAndColorWiseProduction->poly_rejection += $poly->poly_rejection_qty;

        $dateAndColorWiseProduction->iron_qty += $poly->iron_qty;
        $dateAndColorWiseProduction->iron_rejection_qty += $poly->iron_rejection_qty;

        $dateAndColorWiseProduction->packing_qty += $poly->packing_qty;
        $dateAndColorWiseProduction->packing_rejection_qty += $poly->packing_rejection_qty;

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
        if($poly->isDirty('poly_qty') 
            || $poly->isDirty('poly_rejection_qty') 
            || $poly->isDirty('iron_qty') 
            || $poly->isDirty('iron_rejection_qty') 
            || $poly->isDirty('packing_qty') 
            || $poly->isDirty('packing_rejection_qty')) {

            $this->updateTotalProductionReportUpdate($poly);
            $this->updateDateAndColorWiseReportForPolyUpdate($poly);
        }
    }

    private function updateTotalProductionReportUpdate($poly)
    {
        $original = $poly->getOriginal();
        $oldPolyQty = $original['poly_qty'];
        $oldIronQty = $original['iron_qty'];
        $oldPackingQty = $original['packing_qty'];

        $newPolyQty = $poly->poly_qty;
        $newIronQty = $poly->iron_qty;
        $newPackingQty = $poly->packing_qty;

        $oldPolyRejectionQty = $original['poly_rejection_qty'];
        $oldIronRejectionQty = $original['iron_rejection_qty'];
        $oldPackingRejectionQty = $original['packing_rejection_qty'];

        $newPolyRejectionQty = $poly->poly_rejection_qty;
        $newIronRejectionQty = $poly->iron_rejection_qty;
        $newPackingRejectionQty = $poly->packing_rejection_qty;

        $totalProductionReport = TotalProductionReport::where([
            'order_id' => $poly->order_id,
            'color_id' => $poly->color_id
        ])->first();

        if($totalProductionReport) {
            if ($poly->production_date == Carbon::today()->toDateString()) {
                $totalProductionReport->todays_poly -= $oldPolyQty;
                $totalProductionReport->todays_poly += $newPolyQty;
                $totalProductionReport->todays_poly_rejection -= $oldPolyRejectionQty;
                $totalProductionReport->todays_poly_rejection += $newPolyRejectionQty;

                $totalProductionReport->todays_iron -= $oldIronQty;
                $totalProductionReport->todays_iron += $newIronQty;
                $totalProductionReport->todays_iron_rejection -= $oldIronRejectionQty;
                $totalProductionReport->todays_iron_rejection += $newIronRejectionQty;

                $totalProductionReport->todays_packing -= $oldPackingQty;
                $totalProductionReport->todays_packing += $newPackingQty;
                $totalProductionReport->todays_packing_rejection -= $oldPackingRejectionQty;
                $totalProductionReport->todays_packing_rejection += $newPackingRejectionQty;
            }

            $totalProductionReport->total_poly -= $oldPolyQty;
            $totalProductionReport->total_poly += $newPolyQty;
            $totalProductionReport->total_poly_rejection -= $oldPolyRejectionQty;
            $totalProductionReport->total_poly_rejection += $newPolyRejectionQty;

            $totalProductionReport->total_iron -= $oldIronQty;
            $totalProductionReport->total_iron += $newIronQty;
            $totalProductionReport->total_iron_rejection -= $oldIronRejectionQty;
            $totalProductionReport->total_iron_rejection += $newIronRejectionQty;

            $totalProductionReport->total_packing -= $oldPackingQty;
            $totalProductionReport->total_packing += $newPackingQty;
            $totalProductionReport->total_packing_rejection -= $oldPackingRejectionQty;
            $totalProductionReport->total_packing_rejection += $newPackingRejectionQty;
            $totalProductionReport->save();
        }
    }

    private function updateDateAndColorWiseReportForPolyUpdate($poly)
    {       
        $original = $poly->getOriginal();
        $oldPolyQty = $original['poly_qty'];
        $oldIronQty = $original['iron_qty'];
        $oldPackingQty = $original['packing_qty'];

        $newPolyQty = $poly->poly_qty;
        $newIronQty = $poly->iron_qty;
        $newPackingQty = $poly->packing_qty;

        $oldPolyRejectionQty = $original['poly_rejection_qty'];
        $oldIronRejectionQty = $original['iron_rejection_qty'];
        $oldPackingRejectionQty = $original['packing_rejection_qty'];

        $newPolyRejectionQty = $poly->poly_rejection_qty;
        $newIronRejectionQty = $poly->iron_rejection_qty;
        $newPackingRejectionQty = $poly->packing_rejection_qty;

        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $poly->production_date,
            'order_id' => $poly->order_id,
            'color_id' => $poly->color_id
        ])->first();

        if ($dateAndColorWiseProduction) {
            $dateAndColorWiseProduction->poly_qty -= $oldPolyQty;
            $dateAndColorWiseProduction->poly_qty += $newPolyQty;

            $dateAndColorWiseProduction->iron_qty -= $oldIronQty;
            $dateAndColorWiseProduction->iron_qty += $newIronQty;

            $dateAndColorWiseProduction->packing_qty -= $oldPackingQty;
            $dateAndColorWiseProduction->packing_qty += $newPackingQty;

            $dateAndColorWiseProduction->poly_rejection -= $oldPolyRejectionQty;
            $dateAndColorWiseProduction->poly_rejection += $newPolyRejectionQty;

            $dateAndColorWiseProduction->iron_rejection_qty -= $oldIronRejectionQty;
            $dateAndColorWiseProduction->iron_rejection_qty += $newIronRejectionQty;

            $dateAndColorWiseProduction->packing_rejection_qty -= $oldPackingRejectionQty;
            $dateAndColorWiseProduction->packing_rejection_qty += $newPackingRejectionQty;
            $dateAndColorWiseProduction->save();

            return true;
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
        // Dotal production update for delete
        $this->updateTotalProductionReportForDelete($poly);
        // Date wise poly update for delete
        $this->updateDateAndColorWiseReportForDelete($poly);
    }

    private function updateTotalProductionReportForDelete($poly)
    {
        $totalProductionReport = TotalProductionReport::where([
            'order_id' => $poly->order_id, 
            'color_id' => $poly->color_id
        ])->first();
        
        if ($poly->production_date == Carbon::today()->toDateString()) {
            $totalProductionReport->decrement('todays_poly', $poly->poly_qty);
            $totalProductionReport->decrement('todays_poly_rejection', $poly->poly_rejection_qty);

            $totalProductionReport->decrement('todays_iron', $poly->iron_qty);
            $totalProductionReport->decrement('todays_iron_rejection', $poly->iron_rejection_qty);

            $totalProductionReport->decrement('todays_packing', $poly->packing_qty);
            $totalProductionReport->decrement('todays_packing_rejection', $poly->packing_rejection_qty);
        }

        $totalProductionReport->decrement('total_poly', $poly->poly_qty);
        $totalProductionReport->decrement('total_poly_rejection', $poly->poly_rejection_qty);

        $totalProductionReport->decrement('total_iron', $poly->iron_qty);
        $totalProductionReport->decrement('total_iron_rejection', $poly->iron_rejection_qty);

        $totalProductionReport->decrement('total_packing', $poly->packing_qty);
        $totalProductionReport->decrement('total_packing_rejection', $poly->packing_rejection_qty);
        $totalProductionReport->save();

        return true;
    }

    private function updateDateAndColorWiseReportForDelete($poly)
    {
        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $poly->production_date,
            'order_id' => $poly->order_id,
            'color_id' => $poly->color_id,
        ])->first();

        if ($dateAndColorWiseProduction) {
            $dateAndColorWiseProduction->poly_qty -= $poly->poly_qty;          
            $dateAndColorWiseProduction->poly_rejection -= $poly->poly_rejection_qty;

            $dateAndColorWiseProduction->iron_qty -= $poly->iron_qty;          
            $dateAndColorWiseProduction->iron_rejection_qty -= $poly->iron_rejection_qty;

            $dateAndColorWiseProduction->packing_qty -= $poly->packing_qty;          
            $dateAndColorWiseProduction->packing_rejection_qty -= $poly->packing_rejection_qty;
            $dateAndColorWiseProduction->save();
        }

        return true;
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
