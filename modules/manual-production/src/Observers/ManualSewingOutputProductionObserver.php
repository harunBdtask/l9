<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Observers;

use SkylarkSoft\GoRMG\ManualProduction\Models\ManualHourlySewingProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDailyProductionReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateWiseSewingReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualTotalProductionReport;

class ManualSewingOutputProductionObserver
{
    /**
     * Handle the manualHourlySewingProduction "created" event.
     *
     * @param  ManualHourlySewingProduction $manualHourlySewingProduction
     * @return void
     */
    public function created(ManualHourlySewingProduction $manualHourlySewingProduction)
    {
        //
    }

    /**
     * Handle the manualHourlySewingProduction "saved" event.
     *
     * @param  ManualHourlySewingProduction $manualHourlySewingProduction
     * @return void
     */
    public function saved(ManualHourlySewingProduction $manualHourlySewingProduction)
    {
        $this->updateManualTotalProductionReport($manualHourlySewingProduction);
        $this->updateManualDailyProductionReport($manualHourlySewingProduction);
        $this->updateManualDateWiseSewingReport($manualHourlySewingProduction);
    }

    private function updateManualTotalProductionReport($manualHourlySewingProduction)
    {
        $production_query = ManualHourlySewingProduction::query()->where([
            'factory_id' => $manualHourlySewingProduction->factory_id,
            'subcontract_factory_id' => $manualHourlySewingProduction->subcontract_factory_id,
            'buyer_id' => $manualHourlySewingProduction->buyer_id,
            'order_id' => $manualHourlySewingProduction->order_id,
            'garments_item_id' => $manualHourlySewingProduction->garments_item_id,
            'purchase_order_id' => $manualHourlySewingProduction->purchase_order_id,
            'color_id' => $manualHourlySewingProduction->color_id,
            'size_id' => $manualHourlySewingProduction->size_id,
        ]);
        $production_qty = $production_query->sum('production_qty');
        $rejection_qty = $production_query->sum('rejection_qty');

        $report = ManualTotalProductionReport::query()
            ->where([
                'factory_id' => $manualHourlySewingProduction->factory_id,
                'subcontract_factory_id' => $manualHourlySewingProduction->subcontract_factory_id,
                'buyer_id' => $manualHourlySewingProduction->buyer_id,
                'order_id' => $manualHourlySewingProduction->order_id,
                'garments_item_id' => $manualHourlySewingProduction->garments_item_id,
                'purchase_order_id' => $manualHourlySewingProduction->purchase_order_id,
                'color_id' => $manualHourlySewingProduction->color_id,
                'size_id' => $manualHourlySewingProduction->size_id,
            ])->first();
        if (!$report) {
            $report = new ManualTotalProductionReport();
            $report->factory_id = $manualHourlySewingProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualHourlySewingProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualHourlySewingProduction->buyer_id ?? null;
            $report->order_id = $manualHourlySewingProduction->order_id ?? null;
            $report->garments_item_id = $manualHourlySewingProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualHourlySewingProduction->purchase_order_id ?? null;
            $report->color_id = $manualHourlySewingProduction->color_id ?? null;
            $report->size_id = $manualHourlySewingProduction->size_id ?? null;
        }

        $report->sewing_output_qty = $production_qty;
        $report->sewing_rejection_qty = $rejection_qty;
        $report->save();
    }


    private function updateManualDailyProductionReport($manualHourlySewingProduction)
    {
        $production_query = ManualHourlySewingProduction::query()->where([
            'production_date' => $manualHourlySewingProduction->production_date,
            'factory_id' => $manualHourlySewingProduction->factory_id,
            'subcontract_factory_id' => $manualHourlySewingProduction->subcontract_factory_id,
            'buyer_id' => $manualHourlySewingProduction->buyer_id,
            'order_id' => $manualHourlySewingProduction->order_id,
            'garments_item_id' => $manualHourlySewingProduction->garments_item_id,
            'purchase_order_id' => $manualHourlySewingProduction->purchase_order_id,
            'color_id' => $manualHourlySewingProduction->color_id,
            'size_id' => $manualHourlySewingProduction->size_id,
        ]);
        $production_qty = $production_query->sum('production_qty');
        $rejection_qty = $production_query->sum('rejection_qty');

        $report = ManualDailyProductionReport::query()
            ->where([
                'production_date' => $manualHourlySewingProduction->production_date,
                'factory_id' => $manualHourlySewingProduction->factory_id,
                'subcontract_factory_id' => $manualHourlySewingProduction->subcontract_factory_id,
                'buyer_id' => $manualHourlySewingProduction->buyer_id,
                'order_id' => $manualHourlySewingProduction->order_id,
                'garments_item_id' => $manualHourlySewingProduction->garments_item_id,
                'purchase_order_id' => $manualHourlySewingProduction->purchase_order_id,
                'color_id' => $manualHourlySewingProduction->color_id,
                'size_id' => $manualHourlySewingProduction->size_id,
            ])->first();
        if (!$report) {
            $report = new ManualDailyProductionReport();
            $report->production_date = $manualHourlySewingProduction->production_date ?? null;
            $report->factory_id = $manualHourlySewingProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualHourlySewingProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualHourlySewingProduction->buyer_id ?? null;
            $report->order_id = $manualHourlySewingProduction->order_id ?? null;
            $report->garments_item_id = $manualHourlySewingProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualHourlySewingProduction->purchase_order_id ?? null;
            $report->color_id = $manualHourlySewingProduction->color_id ?? null;
            $report->size_id = $manualHourlySewingProduction->size_id ?? null;
        }

        $report->sewing_output_qty = $production_qty;
        $report->sewing_rejection_qty = $rejection_qty;
        $report->save();
    }

    private function updateManualDateWiseSewingReport($manualHourlySewingProduction)
    {
        $production_query = ManualHourlySewingProduction::query()->where([
            'production_date' => $manualHourlySewingProduction->production_date,
            'factory_id' => $manualHourlySewingProduction->factory_id,
            'subcontract_factory_id' => $manualHourlySewingProduction->subcontract_factory_id,
            'buyer_id' => $manualHourlySewingProduction->buyer_id,
            'order_id' => $manualHourlySewingProduction->order_id,
            'garments_item_id' => $manualHourlySewingProduction->garments_item_id,
            'purchase_order_id' => $manualHourlySewingProduction->purchase_order_id,
            'color_id' => $manualHourlySewingProduction->color_id,
            'size_id' => $manualHourlySewingProduction->size_id,
            'floor_id' => $manualHourlySewingProduction->floor_id,
            'line_id' => $manualHourlySewingProduction->line_id,
            'sub_sewing_floor_id' => $manualHourlySewingProduction->sub_sewing_floor_id,
            'sub_sewing_line_id' => $manualHourlySewingProduction->sub_sewing_line_id,
        ]);
        $production_qty = $production_query->sum('production_qty');
        $rejection_qty = $production_query->sum('rejection_qty');

        $report = ManualDateWiseSewingReport::query()
            ->where([
                'production_date' => $manualHourlySewingProduction->production_date,
                'factory_id' => $manualHourlySewingProduction->factory_id,
                'subcontract_factory_id' => $manualHourlySewingProduction->subcontract_factory_id,
                'buyer_id' => $manualHourlySewingProduction->buyer_id,
                'order_id' => $manualHourlySewingProduction->order_id,
                'garments_item_id' => $manualHourlySewingProduction->garments_item_id,
                'purchase_order_id' => $manualHourlySewingProduction->purchase_order_id,
                'color_id' => $manualHourlySewingProduction->color_id,
                'size_id' => $manualHourlySewingProduction->size_id,
                'floor_id' => $manualHourlySewingProduction->floor_id,
                'line_id' => $manualHourlySewingProduction->line_id,
                'sub_sewing_floor_id' => $manualHourlySewingProduction->sub_sewing_floor_id,
                'sub_sewing_line_id' => $manualHourlySewingProduction->sub_sewing_line_id,
            ])->first();
        if (!$report) {
            $report = new ManualDateWiseSewingReport();
            $report->production_date = $manualHourlySewingProduction->production_date ?? null;
            $report->factory_id = $manualHourlySewingProduction->factory_id ?? null;
            $report->subcontract_factory_id = $manualHourlySewingProduction->subcontract_factory_id ?? null;
            $report->buyer_id = $manualHourlySewingProduction->buyer_id ?? null;
            $report->order_id = $manualHourlySewingProduction->order_id ?? null;
            $report->garments_item_id = $manualHourlySewingProduction->garments_item_id ?? null;
            $report->purchase_order_id = $manualHourlySewingProduction->purchase_order_id ?? null;
            $report->color_id = $manualHourlySewingProduction->color_id ?? null;
            $report->size_id = $manualHourlySewingProduction->size_id ?? null;
            $report->floor_id = $manualHourlySewingProduction->floor_id ?? null;
            $report->line_id = $manualHourlySewingProduction->line_id ?? null;
            $report->sub_sewing_floor_id = $manualHourlySewingProduction->sub_sewing_floor_id ?? null;
            $report->sub_sewing_line_id = $manualHourlySewingProduction->sub_sewing_line_id ?? null;
        }

        $report->sewing_output_qty = $production_qty;
        $report->sewing_rejection_qty = $rejection_qty;
        $report->save();
    }

    /**
     * Handle the manualHourlySewingProduction "updated" event.
     *
     * @param  ManualHourlySewingProduction $manualHourlySewingProduction
     * @return void
     */
    public function updated(ManualHourlySewingProduction $manualHourlySewingProduction)
    {
        //
    }

    /**
     * Handle the manualHourlySewingProduction "deleted" event.
     *
     * @param  ManualHourlySewingProduction $manualHourlySewingProduction
     * @return void
     */
    public function deleted(ManualHourlySewingProduction $manualHourlySewingProduction)
    {
        //
    }

    /**
     * Handle the manualHourlySewingProduction "restored" event.
     *
     * @param  ManualHourlySewingProduction $manualHourlySewingProduction
     * @return void
     */
    public function restored(ManualHourlySewingProduction $manualHourlySewingProduction)
    {
        //
    }

    /**
     * Handle the manualHourlySewingProduction "force deleted" event.
     *
     * @param  ManualHourlySewingProduction $manualHourlySewingProduction
     * @return void
     */
    public function forceDeleted(ManualHourlySewingProduction $manualHourlySewingProduction)
    {
        //
    }
}
