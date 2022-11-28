<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Observers;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\HourWiseFinishingProduction;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;

class HourWiseFinishingProductionObserver
{
    const HOURS = [
        'hour_0',
        'hour_1',
        'hour_2',
        'hour_3',
        'hour_4',
        'hour_5',
        'hour_6',
        'hour_7',
        'hour_8',
        'hour_9',
        'hour_10',
        'hour_11',
        'hour_12',
        'hour_13',
        'hour_14',
        'hour_15',
        'hour_16',
        'hour_17',
        'hour_18',
        'hour_19',
        'hour_20',
        'hour_21',
        'hour_22',
        'hour_23',
    ];

    const COLUMNS = [
        'poly', 
        'poly_rejection', 
        'iron', 
        'iron_rejection', 
        'packing', 
        'packing_rejection'
    ];

    const DATE_COLOR_REPORT_COLUMN_MAPPING = [
        'poly' => 'poly_qty',
        'poly_rejection' => 'poly_rejection',
        'iron' => 'iron_qty', 
        'iron_rejection' => 'iron_rejection_qty', 
        'packing' => 'packing_qty', 
        'packing_rejection' => 'packing_rejection_qty'
    ];

    const TOTAL_PRODUCTION_REPORT_COLUMN_MAPPING = [
        'poly' => 'total_poly',
        'poly_rejection' => 'total_poly_rejection',
        'iron' => 'total_iron', 
        'iron_rejection' => 'total_iron_rejection', 
        'packing' => 'total_packing', 
        'packing_rejection' => 'total_packing_rejection'
    ];

    const TOTAL_TODAY_PRODUCTION_REPORT_COLUMN_MAPPING = [
        'poly' => 'todays_poly',
        'poly_rejection' => 'todays_poly_rejection',
        'iron' => 'todays_iron', 
        'iron_rejection' => 'todays_iron_rejection', 
        'packing' => 'todays_packing', 
        'packing_rejection' => 'todays_packing_rejection'
    ];

    /**
     * Handle the poly "created" event.
     *
     * @param  HourWiseFinishingProduction  $hourWiseFinishingProduction
     * @return void
     */
    public function created(HourWiseFinishingProduction $hourWiseFinishingProduction)
    {
        $productionType = $hourWiseFinishingProduction->production_type;
        if (\in_array($productionType, self::COLUMNS)) {
            $this->updateTotalProductionReport($hourWiseFinishingProduction);
            $this->updateDateAndColorWiseProductionReport($hourWiseFinishingProduction);
        }
    }

    private function updateTotalProductionReport($hourWiseFinishingProduction)
    {
        $totalColumn = self::TOTAL_PRODUCTION_REPORT_COLUMN_MAPPING[$hourWiseFinishingProduction->production_type];
        $todayColumn = self::TOTAL_TODAY_PRODUCTION_REPORT_COLUMN_MAPPING[$hourWiseFinishingProduction->production_type];
        $productionQty = 0;
        foreach (self::HOURS as $hour) {
            $productionQty += (int)$hourWiseFinishingProduction->{$hour} > 0 ? (int)$hourWiseFinishingProduction->{$hour} : 0;
        }
        $report = TotalProductionReport::query()
            ->where([
                'garments_item_id' => $hourWiseFinishingProduction->item_id,
                'purchase_order_id' => $hourWiseFinishingProduction->po_id,
                'color_id' => $hourWiseFinishingProduction->color_id,
            ])
            ->first();
        if (!$report) {
            $report = new TotalProductionReport();
            $report->buyer_id = $hourWiseFinishingProduction->buyer_id;
            $report->garments_item_id = $hourWiseFinishingProduction->item_id;
            $report->order_id = $hourWiseFinishingProduction->order_id;
            $report->purchase_order_id = $hourWiseFinishingProduction->po_id;
            $report->color_id = $hourWiseFinishingProduction->color_id;
        }
        if ($hourWiseFinishingProduction->production_date == \now()->toDateString()) {
            $report->{$todayColumn} += $productionQty;
        }
        $report->{$totalColumn} += $productionQty;
        $report->save();
    }

    private function updateDateAndColorWiseProductionReport($hourWiseFinishingProduction)
    {
        $column = self::DATE_COLOR_REPORT_COLUMN_MAPPING[$hourWiseFinishingProduction->production_type];
        $productionQty = 0;
        foreach (self::HOURS as $hour) {
            $productionQty += (int)$hourWiseFinishingProduction->{$hour} > 0 ? (int)$hourWiseFinishingProduction->{$hour} : 0;
        }
        $dateAndColorWiseProduction = DateAndColorWiseProduction::query()
            ->where([
                'production_date' => $hourWiseFinishingProduction->production_date,
                'purchase_order_id' => $hourWiseFinishingProduction->po_id,
                'color_id' => $hourWiseFinishingProduction->color_id,
            ])
            ->first();
        if (!$dateAndColorWiseProduction) {
            $dateAndColorWiseProduction = new DateAndColorWiseProduction();
            $dateAndColorWiseProduction->production_date = $hourWiseFinishingProduction->production_date;
            $dateAndColorWiseProduction->buyer_id = $hourWiseFinishingProduction->buyer_id;
            $dateAndColorWiseProduction->order_id = $hourWiseFinishingProduction->order_id;
            $dateAndColorWiseProduction->purchase_order_id = $hourWiseFinishingProduction->po_id;
            $dateAndColorWiseProduction->color_id = $hourWiseFinishingProduction->color_id;
        }
        $dateAndColorWiseProduction->{$column} += $productionQty;
        $dateAndColorWiseProduction->save();
    }

    /**
     * Handle the poly "updated" event.
     *
     * @param  HourWiseFinishingProduction  $hourWiseFinishingProduction
     * @return void
     */
    public function updated(HourWiseFinishingProduction $hourWiseFinishingProduction)
    {
        $productionType = $hourWiseFinishingProduction->production_type;
        if (\in_array($productionType, self::COLUMNS)) {
            $original = $hourWiseFinishingProduction->getOriginal();

            $this->updateOldQtyTotalProductionReport($original);
            $this->updateTotalProductionReport($hourWiseFinishingProduction);
            $this->updateOldQtyDateAndColorWiseProductionReport($original);
            $this->updateDateAndColorWiseProductionReport($hourWiseFinishingProduction);
        }
    }

    private function updateOldQtyTotalProductionReport($hourWiseFinishingProduction)
    {
        $totalColumn = self::TOTAL_PRODUCTION_REPORT_COLUMN_MAPPING[$hourWiseFinishingProduction['production_type']];
        $todayColumn = self::TOTAL_TODAY_PRODUCTION_REPORT_COLUMN_MAPPING[$hourWiseFinishingProduction['production_type']];
        $productionQty = 0;
        foreach (self::HOURS as $hour) {
            $productionQty += (int)$hourWiseFinishingProduction[$hour] > 0 ? (int)$hourWiseFinishingProduction[$hour] : 0;
        }
        $report = TotalProductionReport::query()
            ->where([
                'garments_item_id' => $hourWiseFinishingProduction['item_id'],
                'purchase_order_id' => $hourWiseFinishingProduction['po_id'],
                'color_id' => $hourWiseFinishingProduction['color_id'],
            ])->first();
        if ($report && $report->{$totalColumn} > 0) {
            if ($hourWiseFinishingProduction['production_date'] == \now()->toDateString() && $report->{$todayColumn} > 0) {
                $report->{$todayColumn} -= $productionQty;
            }
            $report->{$totalColumn} -= $productionQty;
            $report->save();
        }
    }
    
    private function updateOldQtyDateAndColorWiseProductionReport($hourWiseFinishingProduction)
    {
        $column = self::DATE_COLOR_REPORT_COLUMN_MAPPING[$hourWiseFinishingProduction['production_type']];
        $productionQty = 0;
        foreach (self::HOURS as $hour) {
            $productionQty += (int)$hourWiseFinishingProduction[$hour] > 0 ? (int)$hourWiseFinishingProduction[$hour] : 0;
        }
        $dateAndColorWiseProduction = DateAndColorWiseProduction::query()
            ->where([
                'production_date' => $hourWiseFinishingProduction['production_date'],
                'purchase_order_id' => $hourWiseFinishingProduction['po_id'],
                'color_id' => $hourWiseFinishingProduction['color_id'],
            ])->first();
        if ($dateAndColorWiseProduction && $dateAndColorWiseProduction->{$column} > 0) {
            $dateAndColorWiseProduction->{$column} -= $productionQty;
            $dateAndColorWiseProduction->save();
        }
    }

    /**
     * Handle the poly cartoon "deleted" event.
     *
     * @param  \SkylarkSoft\GoRMG\Finishingdroplets\Models\PolyCartoon  $hourWiseFinishingProduction
     * @return void
     */
    public function deleted(HourWiseFinishingProduction $hourWiseFinishingProduction)
    {
        $productionType = $hourWiseFinishingProduction->production_type;
        if (\in_array($productionType, self::COLUMNS)) {
            $this->decreaseQtyTotalProductionReport($hourWiseFinishingProduction);
            $this->decreaseQtyDateAndColorWiseProductionReport($hourWiseFinishingProduction);
        }
    }

    private function decreaseQtyTotalProductionReport($hourWiseFinishingProduction)
    {
        $totalColumn = self::TOTAL_PRODUCTION_REPORT_COLUMN_MAPPING[$hourWiseFinishingProduction->production_type];
        $todayColumn = self::TOTAL_TODAY_PRODUCTION_REPORT_COLUMN_MAPPING[$hourWiseFinishingProduction->production_type];
        $productionQty = 0;
        foreach (self::HOURS as $hour) {
            $productionQty += (int)$hourWiseFinishingProduction->{$hour} > 0 ? (int)$hourWiseFinishingProduction->{$hour} : 0;
        }
        $report = TotalProductionReport::query()
            ->where([
                'garments_item_id' => $hourWiseFinishingProduction->item_id,
                'purchase_order_id' => $hourWiseFinishingProduction->po_id,
                'color_id' => $hourWiseFinishingProduction->color_id,
            ])->first();
        if ($report) {
            if ($hourWiseFinishingProduction->production_date == \now()->toDateString()) {
                $report->{$todayColumn} -= $productionQty;
            }
            $report->{$totalColumn} -= $productionQty;
            $report->save();
        }
    }
    
    private function decreaseQtyDateAndColorWiseProductionReport($hourWiseFinishingProduction)
    {
        $column = self::DATE_COLOR_REPORT_COLUMN_MAPPING[$hourWiseFinishingProduction->production_type];
        $productionQty = 0;
        foreach (self::HOURS as $hour) {
            $productionQty += (int)$hourWiseFinishingProduction->{$hour} > 0 ? (int)$hourWiseFinishingProduction->{$hour} : 0;
        }
        $dateAndColorWiseProduction = DateAndColorWiseProduction::query()
            ->where([
                'production_date' => $hourWiseFinishingProduction->production_date,
                'purchase_order_id' => $hourWiseFinishingProduction->po_id,
                'color_id' => $hourWiseFinishingProduction->color_id,
            ])->first();
        if ($dateAndColorWiseProduction) {
            $dateAndColorWiseProduction->{$column} -= $productionQty;
            $dateAndColorWiseProduction->save();
        }
    }

    /**
     * Handle the poly "restored" event.
     *
     * @param  HourWiseFinishingProduction  $hourWiseFinishingProduction
     * @return void
     */
    public function restored(HourWiseFinishingProduction $hourWiseFinishingProduction)
    {
        //
    }

    /**
     * Handle the poly "force deleted" event.
     *
     * @param  HourWiseFinishingProduction  $hourWiseFinishingProduction
     * @return void
     */
    public function forceDeleted(HourWiseFinishingProduction $hourWiseFinishingProduction)
    {
        //
    }
}
