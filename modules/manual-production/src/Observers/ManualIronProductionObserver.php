<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Observers;

use SkylarkSoft\GoRMG\ManualProduction\Models\ManualIronProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDailyProductionReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualTotalProductionReport;

class ManualIronProductionObserver
{
    /**
     * Handle the manualIronProduction "created" event.
     *
     * @param  ManualIronProduction $manualIronProduction
     * @return void
     */
    public function created(ManualIronProduction $manualIronProduction)
    {
        $this->updateManualTotalProductionReport($manualIronProduction);
        $this->updateManualDailyProductionReport($manualIronProduction);
    }

    private function updateManualTotalProductionReport($manualIronProduction)
    {
        $prod_query = ManualIronProduction::query()->where([
            'factory_id' => $manualIronProduction->factory_id,
            'subcontract_factory_id' => $manualIronProduction->subcontract_factory_id,
            'buyer_id' => $manualIronProduction->buyer_id,
            'order_id' => $manualIronProduction->order_id,
            'garments_item_id' => $manualIronProduction->garments_item_id,
            'purchase_order_id' => $manualIronProduction->purchase_order_id,
            'color_id' => $manualIronProduction->color_id,
            'size_id' => $manualIronProduction->size_id,
        ]);
        $iron_qty = $prod_query->sum('production_qty');
        $iron_rejection_qty = $prod_query->sum('rejection_qty');

        $report = ManualTotalProductionReport::query()
            ->where([
                'factory_id' => $manualIronProduction->factory_id,
                'subcontract_factory_id' => $manualIronProduction->subcontract_factory_id,
                'buyer_id' => $manualIronProduction->buyer_id,
                'order_id' => $manualIronProduction->order_id,
                'garments_item_id' => $manualIronProduction->garments_item_id,
                'purchase_order_id' => $manualIronProduction->purchase_order_id,
                'color_id' => $manualIronProduction->color_id,
                'size_id' => $manualIronProduction->size_id,
            ])->first();
        if (!$report) {
            $report = new ManualTotalProductionReport();
            $report->factory_id = $manualIronProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualIronProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualIronProduction->buyer_id ?? null;
            $report->order_id = $manualIronProduction->order_id ?? null;
            $report->garments_item_id = $manualIronProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualIronProduction->purchase_order_id ?? null;
            $report->color_id = $manualIronProduction->color_id ?? null;
            $report->size_id = $manualIronProduction->size_id ?? null;
        }

        $report->iron_qty = $iron_qty;
        $report->iron_rejection_qty = $iron_rejection_qty;
        $report->save();
    }


    private function updateManualDailyProductionReport($manualIronProduction)
    {
        $prod_query = ManualIronProduction::query()->where([
            'production_date' => $manualIronProduction->production_date,
            'factory_id' => $manualIronProduction->factory_id,
            'subcontract_factory_id' => $manualIronProduction->subcontract_factory_id,
            'buyer_id' => $manualIronProduction->buyer_id,
            'order_id' => $manualIronProduction->order_id,
            'garments_item_id' => $manualIronProduction->garments_item_id,
            'purchase_order_id' => $manualIronProduction->purchase_order_id,
            'color_id' => $manualIronProduction->color_id,
            'size_id' => $manualIronProduction->size_id,
        ]);
        $iron_qty = $prod_query->sum('production_qty');
        $iron_rejection_qty = $prod_query->sum('rejection_qty');

        $report = ManualDailyProductionReport::query()
            ->where([
                'production_date' => $manualIronProduction->production_date,
                'factory_id' => $manualIronProduction->factory_id,
                'subcontract_factory_id' => $manualIronProduction->subcontract_factory_id,
                'buyer_id' => $manualIronProduction->buyer_id,
                'order_id' => $manualIronProduction->order_id,
                'garments_item_id' => $manualIronProduction->garments_item_id,
                'purchase_order_id' => $manualIronProduction->purchase_order_id,
                'color_id' => $manualIronProduction->color_id,
                'size_id' => $manualIronProduction->size_id,
            ])->first();
        if (!$report) {
            $report = new ManualDailyProductionReport();
            $report->production_date = $manualIronProduction->production_date ?? null;
            $report->factory_id = $manualIronProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualIronProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualIronProduction->buyer_id ?? null;
            $report->order_id = $manualIronProduction->order_id ?? null;
            $report->garments_item_id = $manualIronProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualIronProduction->purchase_order_id ?? null;
            $report->color_id = $manualIronProduction->color_id ?? null;
            $report->size_id = $manualIronProduction->size_id ?? null;
        }

        $report->iron_qty = $iron_qty;
        $report->iron_rejection_qty = $iron_rejection_qty;
        $report->save();
    }

    /**
     * Handle the manualIronProduction "saved" event.
     *
     * @param  ManualIronProduction $manualIronProduction
     * @return void
     */
    public function saved(ManualIronProduction $manualIronProduction)
    {
        //
    }

    /**
     * Handle the manualIronProduction "updated" event.
     *
     * @param  ManualIronProduction $manualIronProduction
     * @return void
     */
    public function updated(ManualIronProduction $manualIronProduction)
    {
        //
    }

    /**
     * Handle the manualIronProduction "deleted" event.
     *
     * @param  ManualIronProduction $manualIronProduction
     * @return void
     */
    public function deleted(ManualIronProduction $manualIronProduction)
    {
        //
    }

    /**
     * Handle the manualIronProduction "restored" event.
     *
     * @param  ManualIronProduction $manualIronProduction
     * @return void
     */
    public function restored(ManualIronProduction $manualIronProduction)
    {
        //
    }

    /**
     * Handle the manualIronProduction "force deleted" event.
     *
     * @param  ManualIronProduction $manualIronProduction
     * @return void
     */
    public function forceDeleted(ManualIronProduction $manualIronProduction)
    {
        //
    }
}
