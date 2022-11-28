<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Observers;

use SkylarkSoft\GoRMG\ManualProduction\Models\ManualCuttingProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDailyProductionReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualTotalProductionReport;

class ManualCuttingProductionObserver
{
    /**
     * Handle the manualCuttingProduction "created" event.
     *
     * @param  ManualCuttingProduction $manualCuttingProduction
     * @return void
     */
    public function created(ManualCuttingProduction $manualCuttingProduction)
    {
        //
    }

    /**
     * Handle the manualCuttingProduction "saved" event.
     *
     * @param  ManualCuttingProduction $manualCuttingProduction
     * @return void
     */
    public function saved(ManualCuttingProduction $manualCuttingProduction)
    {
        $this->updateManualTotalProductionReport($manualCuttingProduction);
        $this->updateManualDailyProductionReport($manualCuttingProduction);
    }

    private function updateManualTotalProductionReport($manualCuttingProduction)
    {
        $manual_cut_prod_query = ManualCuttingProduction::query()->where([
            'factory_id' => $manualCuttingProduction->factory_id,
            'subcontract_factory_id' => $manualCuttingProduction->subcontract_factory_id,
            'buyer_id' => $manualCuttingProduction->buyer_id,
            'order_id' => $manualCuttingProduction->order_id,
            'garments_item_id' => $manualCuttingProduction->garments_item_id,
            'purchase_order_id' => $manualCuttingProduction->purchase_order_id,
            'color_id' => $manualCuttingProduction->color_id,
            'size_id' => $manualCuttingProduction->size_id,
        ]);
        $cutting_qty = $manual_cut_prod_query->sum('production_qty');
        $cutting_rejection_qty = $manual_cut_prod_query->sum('rejection_qty');

        $report = ManualTotalProductionReport::query()
            ->where([
                'factory_id' => $manualCuttingProduction->factory_id,
                'subcontract_factory_id' => $manualCuttingProduction->subcontract_factory_id,
                'buyer_id' => $manualCuttingProduction->buyer_id,
                'order_id' => $manualCuttingProduction->order_id,
                'garments_item_id' => $manualCuttingProduction->garments_item_id,
                'purchase_order_id' => $manualCuttingProduction->purchase_order_id,
                'color_id' => $manualCuttingProduction->color_id,
                'size_id' => $manualCuttingProduction->size_id,
            ])->first();
        if (!$report) {
            $report = new ManualTotalProductionReport();
            $report->factory_id = $manualCuttingProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualCuttingProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualCuttingProduction->buyer_id ?? null;
            $report->order_id = $manualCuttingProduction->order_id ?? null;
            $report->garments_item_id = $manualCuttingProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualCuttingProduction->purchase_order_id ?? null;
            $report->color_id = $manualCuttingProduction->color_id ?? null;
            $report->size_id = $manualCuttingProduction->size_id ?? null;
        }

        $report->cutting_qty = $cutting_qty;
        $report->cutting_rejection_qty = $cutting_rejection_qty;
        $report->save();
    }


    private function updateManualDailyProductionReport($manualCuttingProduction)
    {
        $manual_cut_prod_query = ManualCuttingProduction::query()->where([
            'production_date' => $manualCuttingProduction->production_date,
            'factory_id' => $manualCuttingProduction->factory_id,
            'subcontract_factory_id' => $manualCuttingProduction->subcontract_factory_id,
            'buyer_id' => $manualCuttingProduction->buyer_id,
            'order_id' => $manualCuttingProduction->order_id,
            'garments_item_id' => $manualCuttingProduction->garments_item_id,
            'purchase_order_id' => $manualCuttingProduction->purchase_order_id,
            'color_id' => $manualCuttingProduction->color_id,
            'size_id' => $manualCuttingProduction->size_id,
        ]);
        $cutting_qty = $manual_cut_prod_query->sum('production_qty');
        $cutting_rejection_qty = $manual_cut_prod_query->sum('rejection_qty');

        $report = ManualDailyProductionReport::query()
            ->where([
                'production_date' => $manualCuttingProduction->production_date,
                'factory_id' => $manualCuttingProduction->factory_id,
                'subcontract_factory_id' => $manualCuttingProduction->subcontract_factory_id,
                'buyer_id' => $manualCuttingProduction->buyer_id,
                'order_id' => $manualCuttingProduction->order_id,
                'garments_item_id' => $manualCuttingProduction->garments_item_id,
                'purchase_order_id' => $manualCuttingProduction->purchase_order_id,
                'color_id' => $manualCuttingProduction->color_id,
                'size_id' => $manualCuttingProduction->size_id,
            ])->first();
        if (!$report) {
            $report = new ManualDailyProductionReport();
            $report->production_date = $manualCuttingProduction->production_date ?? null;
            $report->factory_id = $manualCuttingProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualCuttingProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualCuttingProduction->buyer_id ?? null;
            $report->order_id = $manualCuttingProduction->order_id ?? null;
            $report->garments_item_id = $manualCuttingProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualCuttingProduction->purchase_order_id ?? null;
            $report->color_id = $manualCuttingProduction->color_id ?? null;
            $report->size_id = $manualCuttingProduction->size_id ?? null;
        }

        $report->cutting_qty = $cutting_qty;
        $report->cutting_rejection_qty = $cutting_rejection_qty;
        $report->save();
    }
    /**
     * Handle the manualCuttingProduction "updated" event.
     *
     * @param  ManualCuttingProduction $manualCuttingProduction
     * @return void
     */
    public function updated(ManualCuttingProduction $manualCuttingProduction)
    {
        //
    }

    /**
     * Handle the manualCuttingProduction "deleted" event.
     *
     * @param  ManualCuttingProduction $manualCuttingProduction
     * @return void
     */
    public function deleted(ManualCuttingProduction $manualCuttingProduction)
    {
        //
    }

    /**
     * Handle the manualCuttingProduction "restored" event.
     *
     * @param  ManualCuttingProduction $manualCuttingProduction
     * @return void
     */
    public function restored(ManualCuttingProduction $manualCuttingProduction)
    {
        //
    }

    /**
     * Handle the manualCuttingProduction "force deleted" event.
     *
     * @param  ManualCuttingProduction $manualCuttingProduction
     * @return void
     */
    public function forceDeleted(ManualCuttingProduction $manualCuttingProduction)
    {
        //
    }
}
