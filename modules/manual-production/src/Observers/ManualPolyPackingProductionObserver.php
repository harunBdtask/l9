<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Observers;

use SkylarkSoft\GoRMG\ManualProduction\Models\ManualPolyPackingProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDailyProductionReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualTotalProductionReport;

class ManualPolyPackingProductionObserver
{
    /**
     * Handle the manualPolyPackingProduction "created" event.
     *
     * @param  ManualPolyPackingProduction $manualPolyPackingProduction
     * @return void
     */
    public function created(ManualPolyPackingProduction $manualPolyPackingProduction)
    {
        //
    }

    /**
     * Handle the manualPolyPackingProduction "saved" event.
     *
     * @param  ManualPolyPackingProduction $manualPolyPackingProduction
     * @return void
     */
    public function saved(ManualPolyPackingProduction $manualPolyPackingProduction)
    {
        $this->updateManualTotalProductionReport($manualPolyPackingProduction);
        $this->updateManualDailyProductionReport($manualPolyPackingProduction);
    }

    private function updateManualTotalProductionReport($manualPolyPackingProduction)
    {
        $prod_query = ManualPolyPackingProduction::query()->where([
            'factory_id' => $manualPolyPackingProduction->factory_id,
            'subcontract_factory_id' => $manualPolyPackingProduction->subcontract_factory_id,
            'buyer_id' => $manualPolyPackingProduction->buyer_id,
            'order_id' => $manualPolyPackingProduction->order_id,
            'garments_item_id' => $manualPolyPackingProduction->garments_item_id,
            'purchase_order_id' => $manualPolyPackingProduction->purchase_order_id,
            'color_id' => $manualPolyPackingProduction->color_id,
            'size_id' => $manualPolyPackingProduction->size_id,
        ]);
        $poly_qty = $prod_query->sum('production_qty');
        $poly_rejection_qty = $prod_query->sum('rejection_qty');

        $report = ManualTotalProductionReport::query()
            ->where([
                'factory_id' => $manualPolyPackingProduction->factory_id,
                'subcontract_factory_id' => $manualPolyPackingProduction->subcontract_factory_id,
                'buyer_id' => $manualPolyPackingProduction->buyer_id,
                'order_id' => $manualPolyPackingProduction->order_id,
                'garments_item_id' => $manualPolyPackingProduction->garments_item_id,
                'purchase_order_id' => $manualPolyPackingProduction->purchase_order_id,
                'color_id' => $manualPolyPackingProduction->color_id,
                'size_id' => $manualPolyPackingProduction->size_id,
            ])->first();
        if (!$report) {
            $report = new ManualTotalProductionReport();
            $report->factory_id = $manualPolyPackingProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualPolyPackingProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualPolyPackingProduction->buyer_id ?? null;
            $report->order_id = $manualPolyPackingProduction->order_id ?? null;
            $report->garments_item_id = $manualPolyPackingProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualPolyPackingProduction->purchase_order_id ?? null;
            $report->color_id = $manualPolyPackingProduction->color_id ?? null;
            $report->size_id = $manualPolyPackingProduction->size_id ?? null;
        }

        $report->poly_qty = $poly_qty;
        $report->poly_rejection_qty = $poly_rejection_qty;
        $report->save();
    }


    private function updateManualDailyProductionReport($manualPolyPackingProduction)
    {
        $prod_query = ManualPolyPackingProduction::query()->where([
            'production_date' => $manualPolyPackingProduction->production_date,
            'factory_id' => $manualPolyPackingProduction->factory_id,
            'subcontract_factory_id' => $manualPolyPackingProduction->subcontract_factory_id,
            'buyer_id' => $manualPolyPackingProduction->buyer_id,
            'order_id' => $manualPolyPackingProduction->order_id,
            'garments_item_id' => $manualPolyPackingProduction->garments_item_id,
            'purchase_order_id' => $manualPolyPackingProduction->purchase_order_id,
            'color_id' => $manualPolyPackingProduction->color_id,
            'size_id' => $manualPolyPackingProduction->size_id,
        ]);
        $poly_qty = $prod_query->sum('production_qty');
        $poly_rejection_qty = $prod_query->sum('rejection_qty');

        $report = ManualDailyProductionReport::query()
            ->where([
                'production_date' => $manualPolyPackingProduction->production_date,
                'factory_id' => $manualPolyPackingProduction->factory_id,
                'subcontract_factory_id' => $manualPolyPackingProduction->subcontract_factory_id,
                'buyer_id' => $manualPolyPackingProduction->buyer_id,
                'order_id' => $manualPolyPackingProduction->order_id,
                'garments_item_id' => $manualPolyPackingProduction->garments_item_id,
                'purchase_order_id' => $manualPolyPackingProduction->purchase_order_id,
                'color_id' => $manualPolyPackingProduction->color_id,
                'size_id' => $manualPolyPackingProduction->size_id,
            ])->first();
        if (!$report) {
            $report = new ManualDailyProductionReport();
            $report->production_date = $manualPolyPackingProduction->production_date ?? null;
            $report->factory_id = $manualPolyPackingProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualPolyPackingProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualPolyPackingProduction->buyer_id ?? null;
            $report->order_id = $manualPolyPackingProduction->order_id ?? null;
            $report->garments_item_id = $manualPolyPackingProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualPolyPackingProduction->purchase_order_id ?? null;
            $report->color_id = $manualPolyPackingProduction->color_id ?? null;
            $report->size_id = $manualPolyPackingProduction->size_id ?? null;
        }

        $report->poly_qty = $poly_qty;
        $report->poly_rejection_qty = $poly_rejection_qty;
        $report->save();
    }
    
    /**
     * Handle the manualPolyPackingProduction "updated" event.
     *
     * @param  ManualPolyPackingProduction $manualPolyPackingProduction
     * @return void
     */
    public function updated(ManualPolyPackingProduction $manualPolyPackingProduction)
    {
        //
    }

    /**
     * Handle the manualPolyPackingProduction "deleted" event.
     *
     * @param  ManualPolyPackingProduction $manualPolyPackingProduction
     * @return void
     */
    public function deleted(ManualPolyPackingProduction $manualPolyPackingProduction)
    {
        //
    }

    /**
     * Handle the manualPolyPackingProduction "restored" event.
     *
     * @param  ManualPolyPackingProduction $manualPolyPackingProduction
     * @return void
     */
    public function restored(ManualPolyPackingProduction $manualPolyPackingProduction)
    {
        //
    }

    /**
     * Handle the manualPolyPackingProduction "force deleted" event.
     *
     * @param  ManualPolyPackingProduction $manualPolyPackingProduction
     * @return void
     */
    public function forceDeleted(ManualPolyPackingProduction $manualPolyPackingProduction)
    {
        //
    }
}
