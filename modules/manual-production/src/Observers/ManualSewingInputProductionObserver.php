<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Observers;

use SkylarkSoft\GoRMG\ManualProduction\Models\ManualSewingInputProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDailyProductionReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateWiseSewingReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualTotalProductionReport;

class ManualSewingInputProductionObserver
{
    /**
     * Handle the manualSewingInputProduction "created" event.
     *
     * @param  ManualSewingInputProduction $manualSewingInputProduction
     * @return void
     */
    public function created(ManualSewingInputProduction $manualSewingInputProduction)
    {
        //
    }

    /**
     * Handle the manualSewingInputProduction "saved" event.
     *
     * @param  ManualSewingInputProduction $manualSewingInputProduction
     * @return void
     */
    public function saved(ManualSewingInputProduction $manualSewingInputProduction)
    {
        $this->updateManualTotalProductionReport($manualSewingInputProduction);
        $this->updateManualDailyProductionReport($manualSewingInputProduction);
        $this->updateManualDateWiseSewingReport($manualSewingInputProduction);
    }

    private function updateManualTotalProductionReport($manualSewingInputProduction)
    {
        $production_query = ManualSewingInputProduction::query()->where([
            'factory_id' => $manualSewingInputProduction->factory_id,
            'subcontract_factory_id' => $manualSewingInputProduction->subcontract_factory_id,
            'buyer_id' => $manualSewingInputProduction->buyer_id,
            'order_id' => $manualSewingInputProduction->order_id,
            'garments_item_id' => $manualSewingInputProduction->garments_item_id,
            'purchase_order_id' => $manualSewingInputProduction->purchase_order_id,
            'color_id' => $manualSewingInputProduction->color_id,
            'size_id' => $manualSewingInputProduction->size_id,
        ]);
        $production_qty = $production_query->sum('production_qty');

        $report = ManualTotalProductionReport::query()
            ->where([
                'factory_id' => $manualSewingInputProduction->factory_id,
                'subcontract_factory_id' => $manualSewingInputProduction->subcontract_factory_id,
                'buyer_id' => $manualSewingInputProduction->buyer_id,
                'order_id' => $manualSewingInputProduction->order_id,
                'garments_item_id' => $manualSewingInputProduction->garments_item_id,
                'purchase_order_id' => $manualSewingInputProduction->purchase_order_id,
                'color_id' => $manualSewingInputProduction->color_id,
                'size_id' => $manualSewingInputProduction->size_id,
            ])->first();
        if (!$report) {
            $report = new ManualTotalProductionReport();
            $report->factory_id = $manualSewingInputProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualSewingInputProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualSewingInputProduction->buyer_id ?? null;
            $report->order_id = $manualSewingInputProduction->order_id ?? null;
            $report->garments_item_id = $manualSewingInputProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualSewingInputProduction->purchase_order_id ?? null;
            $report->color_id = $manualSewingInputProduction->color_id ?? null;
            $report->size_id = $manualSewingInputProduction->size_id ?? null;
        }

        $report->input_qty = $production_qty;
        $report->save();
    }


    private function updateManualDailyProductionReport($manualSewingInputProduction)
    {
        $production_query = ManualSewingInputProduction::query()->where([
            'production_date' => $manualSewingInputProduction->production_date,
            'factory_id' => $manualSewingInputProduction->factory_id,
            'subcontract_factory_id' => $manualSewingInputProduction->subcontract_factory_id,
            'buyer_id' => $manualSewingInputProduction->buyer_id,
            'order_id' => $manualSewingInputProduction->order_id,
            'garments_item_id' => $manualSewingInputProduction->garments_item_id,
            'purchase_order_id' => $manualSewingInputProduction->purchase_order_id,
            'color_id' => $manualSewingInputProduction->color_id,
            'size_id' => $manualSewingInputProduction->size_id,
        ]);
        $production_qty = $production_query->sum('production_qty');

        $report = ManualDailyProductionReport::query()
            ->where([
                'production_date' => $manualSewingInputProduction->production_date,
                'factory_id' => $manualSewingInputProduction->factory_id,
                'subcontract_factory_id' => $manualSewingInputProduction->subcontract_factory_id,
                'buyer_id' => $manualSewingInputProduction->buyer_id,
                'order_id' => $manualSewingInputProduction->order_id,
                'garments_item_id' => $manualSewingInputProduction->garments_item_id,
                'purchase_order_id' => $manualSewingInputProduction->purchase_order_id,
                'color_id' => $manualSewingInputProduction->color_id,
                'size_id' => $manualSewingInputProduction->size_id,
            ])->first();
        if (!$report) {
            $report = new ManualDailyProductionReport();
            $report->production_date = $manualSewingInputProduction->production_date ?? null;
            $report->factory_id = $manualSewingInputProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualSewingInputProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualSewingInputProduction->buyer_id ?? null;
            $report->order_id = $manualSewingInputProduction->order_id ?? null;
            $report->garments_item_id = $manualSewingInputProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualSewingInputProduction->purchase_order_id ?? null;
            $report->color_id = $manualSewingInputProduction->color_id ?? null;
            $report->size_id = $manualSewingInputProduction->size_id ?? null;
        }

        $report->input_qty = $production_qty;
        $report->save();
    }

    private function updateManualDateWiseSewingReport($manualSewingInputProduction)
    {
        $production_query = ManualSewingInputProduction::query()->where([
            'production_date' => $manualSewingInputProduction->production_date,
            'factory_id' => $manualSewingInputProduction->factory_id,
            'subcontract_factory_id' => $manualSewingInputProduction->subcontract_factory_id,
            'buyer_id' => $manualSewingInputProduction->buyer_id,
            'order_id' => $manualSewingInputProduction->order_id,
            'garments_item_id' => $manualSewingInputProduction->garments_item_id,
            'purchase_order_id' => $manualSewingInputProduction->purchase_order_id,
            'color_id' => $manualSewingInputProduction->color_id,
            'size_id' => $manualSewingInputProduction->size_id,
            'floor_id' => $manualSewingInputProduction->floor_id,
            'line_id' => $manualSewingInputProduction->line_id,
            'sub_sewing_floor_id' => $manualSewingInputProduction->sub_sewing_floor_id,
            'sub_sewing_line_id' => $manualSewingInputProduction->sub_sewing_line_id,
        ]);
        $production_qty = $production_query->sum('production_qty');

        $report = ManualDateWiseSewingReport::query()
            ->where([
                'production_date' => $manualSewingInputProduction->production_date,
                'factory_id' => $manualSewingInputProduction->factory_id,
                'subcontract_factory_id' => $manualSewingInputProduction->subcontract_factory_id,
                'buyer_id' => $manualSewingInputProduction->buyer_id,
                'order_id' => $manualSewingInputProduction->order_id,
                'garments_item_id' => $manualSewingInputProduction->garments_item_id,
                'purchase_order_id' => $manualSewingInputProduction->purchase_order_id,
                'color_id' => $manualSewingInputProduction->color_id,
                'size_id' => $manualSewingInputProduction->size_id,
                'floor_id' => $manualSewingInputProduction->floor_id,
                'line_id' => $manualSewingInputProduction->line_id,
                'sub_sewing_floor_id' => $manualSewingInputProduction->sub_sewing_floor_id,
                'sub_sewing_line_id' => $manualSewingInputProduction->sub_sewing_line_id,
            ])->first();
        if (!$report) {
            $report = new ManualDateWiseSewingReport();
            $report->production_date = $manualSewingInputProduction->production_date ?? null;
            $report->factory_id = $manualSewingInputProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualSewingInputProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualSewingInputProduction->buyer_id ?? null;
            $report->order_id = $manualSewingInputProduction->order_id ?? null;
            $report->garments_item_id = $manualSewingInputProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualSewingInputProduction->purchase_order_id ?? null;
            $report->color_id = $manualSewingInputProduction->color_id ?? null;
            $report->size_id = $manualSewingInputProduction->size_id ?? null;
            $report->floor_id = $manualSewingInputProduction->floor_id ?? null;
            $report->line_id = $manualSewingInputProduction->line_id ?? null;
            $report->sub_sewing_floor_id = $manualSewingInputProduction->sub_sewing_floor_id ?? null;
            $report->sub_sewing_line_id = $manualSewingInputProduction->sub_sewing_line_id ?? null;
        }

        $report->input_qty = $production_qty;
        $report->save();
    }

    /**
     * Handle the manualSewingInputProduction "updated" event.
     *
     * @param  ManualSewingInputProduction $manualSewingInputProduction
     * @return void
     */
    public function updated(ManualSewingInputProduction $manualSewingInputProduction)
    {
        //
    }

    /**
     * Handle the manualSewingInputProduction "deleted" event.
     *
     * @param  ManualSewingInputProduction $manualSewingInputProduction
     * @return void
     */
    public function deleted(ManualSewingInputProduction $manualSewingInputProduction)
    {
        //
    }

    /**
     * Handle the manualSewingInputProduction "restored" event.
     *
     * @param  ManualSewingInputProduction $manualSewingInputProduction
     * @return void
     */
    public function restored(ManualSewingInputProduction $manualSewingInputProduction)
    {
        //
    }

    /**
     * Handle the manualSewingInputProduction "force deleted" event.
     *
     * @param  ManualSewingInputProduction $manualSewingInputProduction
     * @return void
     */
    public function forceDeleted(ManualSewingInputProduction $manualSewingInputProduction)
    {
        //
    }
}
