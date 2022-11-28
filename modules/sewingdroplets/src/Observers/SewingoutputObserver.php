<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Observers;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\ColorSizeSummaryReportService;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DateWiseSewingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Inputdroplets\Models\LineSizeWiseSewingReport;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput;

class SewingoutputObserver
{
    /**
     * Handle the sewingoutput "created" event.
     *
     * @param \SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput $sewingoutput
     * @return void
     */
    public function created(Sewingoutput $sewingoutput)
    {
        $bundleCard = $sewingoutput->bundlecard;
        $qty = $bundleCard->quantity
            - $bundleCard->total_rejection
            - $bundleCard->print_rejection
            - $bundleCard->embroidary_rejection
            - $bundleCard->sewing_rejection;

        // For updating Color Size Summary Report Table
        (new ColorSizeSummaryReportService())->make($bundleCard)->sewingOutput($qty)->saveOrUpdate();
        // For dating Date Wise Sewing Report Table
        $this->updateTotalProductionReport($sewingoutput, $bundleCard, $qty);
        // For Updating Date Wise Sewing Report
        $production_date = $sewingoutput->updated_at->toDateString();
        $this->updateDateWiseSewingProductionReportForSewingOutput($sewingoutput, $qty, $production_date);
        // For updating hourly prodcution report report
        $this->updateHourlySewingProductionReport($bundleCard, $sewingoutput, $qty, $production_date);
        // For updating date and color wise report
        $this->updateColorAndDateWiseProductionReport($bundleCard, $qty, $production_date);
        // For Updating Date Wise Sewing Production
        $this->updateFinishingProductionReportForSewingOutput($sewingoutput, $qty, $production_date);
        $this->updateLineSizeWiseSewingReportForSewingOutput($sewingoutput, $qty, $production_date);

    }

    private function updateTotalProductionReport($sewingoutput, $bundleCard, $qty)
    {
        $orderId = $bundleCard->order_id;
        $garmentsItemId = $bundleCard->garments_item_id;
        $purchaseOrderId = $sewingoutput->purchase_order_id;
        $colorId = $sewingoutput->color_id;

        $sewingOutput = TotalProductionReport::where([
            'order_id' => $orderId,
            'garments_item_id' => $garmentsItemId,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId
        ])->first();

        if (!$sewingOutput) {
            $sewingOutput = new TotalProductionReport();
            $sewingOutput->buyer_id = $bundleCard->buyer_id;
            $sewingOutput->order_id = $orderId;
            $sewingOutput->garments_item_id = $garmentsItemId;
            $sewingOutput->purchase_order_id = $purchaseOrderId;
            $sewingOutput->color_id = $colorId;
        }

        $sewingOutput->todays_sewing_output += $qty;
        $sewingOutput->total_sewing_output += $qty;
        $sewingOutput->save();

        return true;
    }

    private function updateHourlySewingProductionReport($bundleCard, $sewingoutput, $qty, $production_date)
    {
        $hour = date('H');
        $current_hour = (int)$hour;
        $column_name = 'hour_' . $current_hour;

        $hourlyReport = HourlySewingProductionReport::where([
            'production_date' => $production_date,
            'line_id' => $sewingoutput->line_id,
            'order_id' => $bundleCard->order_id,
            'garments_item_id' => $bundleCard->garments_item_id,
            'purchase_order_id' => $sewingoutput->purchase_order_id,
            'color_id' => $sewingoutput->color_id,
        ])->first();

        if (!$hourlyReport) {

            $hourlyReport = new HourlySewingProductionReport();
            $hourlyReport->production_date = $production_date;
            $hourlyReport->floor_id = $sewingoutput->line->floor_id;
            $hourlyReport->line_id = $sewingoutput->line_id;
            $hourlyReport->buyer_id = $sewingoutput->purchaseOrder->buyer_id;
            $hourlyReport->order_id = $sewingoutput->purchaseOrder->order_id;
            $hourlyReport->garments_item_id = $bundleCard->garments_item_id;
            $hourlyReport->purchase_order_id = $sewingoutput->purchase_order_id;
            $hourlyReport->color_id = $sewingoutput->color_id;
        }

        $hourlyReport->$column_name += $qty;
        $hourlyReport->sewing_rejection += $sewingoutput->bundlecard->sewing_rejection;
        $hourlyReport->factory_id = $sewingoutput->factory_id;
        $hourlyReport->save();

        return true;
    }

    private function updateFinishingProductionReportForSewingOutput($sewingoutput, $qty, $production_date)
    {
        $bundlecard = $sewingoutput->bundlecard;
        $purchaseOrderId = $sewingoutput->purchase_order_id;
        $colorId = $sewingoutput->color_id;
        $sewing_date = $production_date;
        $floor_id = $sewingoutput->line->floor->id;
        $line_id = $sewingoutput->line_id;

        $finishingProductionReport = FinishingProductionReport::where([
            'floor_id' => $floor_id,
            'line_id' => $line_id,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'production_date' => $sewing_date,
        ])->first();

        if (!$finishingProductionReport) {
            $finishingProductionReport = new FinishingProductionReport();
            $finishingProductionReport->floor_id = $floor_id;
            $finishingProductionReport->line_id = $line_id;
            $finishingProductionReport->buyer_id = $bundlecard->buyer_id;
            $finishingProductionReport->purchase_order_id = $purchaseOrderId;
            $finishingProductionReport->order_id = $bundlecard->order_id;
            $finishingProductionReport->color_id = $colorId;
            $finishingProductionReport->production_date = $sewing_date;
        }

        $finishingProductionReport->sewing_output += $qty;
        $finishingProductionReport->save();
    }

    private function updateLineSizeWiseSewingReportForSewingOutput($sewingoutput, $qty, $production_date)
    {
        $bundlecard = $sewingoutput->bundlecard;
        $purchaseOrderId = $sewingoutput->purchase_order_id;
        $colorId = $sewingoutput->color_id;
        $sizeId = $bundlecard->size_id;
        $sewing_date = $production_date;
        $floor_id = $sewingoutput->line->floor->id;
        $line_id = $sewingoutput->line_id;

        $report = LineSizeWiseSewingReport::where([
            'floor_id' => $floor_id,
            'line_id' => $line_id,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'size_id' => $sizeId,
            'production_date' => $sewing_date,
        ])->first();

        if (!$report) {
            $report = new LineSizeWiseSewingReport();
            $report->floor_id = $floor_id;
            $report->line_id = $line_id;
            $report->buyer_id = $bundlecard->buyer_id;
            $report->purchase_order_id = $purchaseOrderId;
            $report->order_id = $bundlecard->order_id;
            $report->color_id = $colorId;
            $report->size_id = $sizeId;
            $report->production_date = $sewing_date;
        }

        $report->sewing_output += $qty;
        $report->save();
    }

    public function updateColorAndDateWiseProductionReport($bundleCard, $qty, $production_date)
    {
        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'purchase_order_id' => $bundleCard->purchase_order_id,
            'color_id' => $bundleCard->color_id,
            'production_date' => $production_date,
        ])->first();

        if (!$dateAndColorWiseProduction) {
            $dateAndColorWiseProduction = new DateAndColorWiseProduction;
            $dateAndColorWiseProduction->buyer_id = $bundleCard->buyer_id;
            $dateAndColorWiseProduction->order_id = $bundleCard->order_id;
            $dateAndColorWiseProduction->purchase_order_id = $bundleCard->purchase_order_id;
            $dateAndColorWiseProduction->color_id = $bundleCard->color_id;
            $dateAndColorWiseProduction->production_date = $production_date;
        }
        $dateAndColorWiseProduction->sewing_output_qty += $qty;
        $dateAndColorWiseProduction->save();

        return true;
    }

    public function updateDateWiseSewingProductionReportForSewingOutput($sewingoutput, $qty, $production_date)
    {
        $bundlecard = $sewingoutput->bundlecard;
        $purchaseOrderId = $sewingoutput->purchase_order_id;
        $colorId = $sewingoutput->color_id;
        $floor_id = $sewingoutput->line->floor->id;
        $line_id = $sewingoutput->line_id;

        $sewing_details_data = [];
        $sewing_details_data[] = [
            'buyer_id' => $bundlecard->buyer_id,
            'order_id' => $bundlecard->order_id,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'sewing_input' => 0,
            'sewing_output' => $qty ?? 0,
            'sewing_rejection' => 0,
        ];

        $date_wise_sewing_production_report = DateWiseSewingProductionReport::where([
            'floor_id' => $floor_id,
            'line_id' => $line_id,
            'sewing_date' => $production_date
        ])->first();

        if (!$date_wise_sewing_production_report) {
            $date_wise_sewing_production_report = new DateWiseSewingProductionReport();
            $date_wise_sewing_production_report->floor_id = $floor_id;
            $date_wise_sewing_production_report->line_id = $line_id;
            $date_wise_sewing_production_report->sewing_date = $production_date;
            $date_wise_sewing_production_report->sewing_details = $sewing_details_data;
            $date_wise_sewing_production_report->total_sewing_input = 0;
            $date_wise_sewing_production_report->total_sewing_output = $qty ?? 0;
            $date_wise_sewing_production_report->total_sewing_rejection = 0;
            $date_wise_sewing_production_report->save();
        } else {
            $sewing_details_existing_data = $date_wise_sewing_production_report->sewing_details;
            $total_sewing_input = $date_wise_sewing_production_report->total_sewing_input;
            $total_sewing_output = $date_wise_sewing_production_report->total_sewing_output;
            $total_sewing_rejection = $date_wise_sewing_production_report->total_sewing_rejection;
            $total_sewing_output += $qty;
            foreach ($sewing_details_existing_data as $key => $sewing_detail) {
                $is_detail_exist = 0;
                if ($sewing_detail['purchase_order_id'] == $purchaseOrderId && $sewing_detail['color_id'] == $colorId) {
                    $is_detail_exist = 1;
                    $sewing_details_existing_data[$key] = [
                        'buyer_id' => $sewing_detail['buyer_id'],
                        'order_id' => $sewing_detail['order_id'],
                        'purchase_order_id' => $sewing_detail['purchase_order_id'],
                        'color_id' => $sewing_detail['color_id'],
                        'sewing_input' => $sewing_detail['sewing_input'] ?? 0,
                        'sewing_output' => $sewing_detail['sewing_output'] + $qty ?? 0,
                        'sewing_rejection' => $sewing_detail['sewing_rejection'] ?? 0,
                    ];
                    break;
                }
            }
            if ($is_detail_exist == 0) {
                $sewing_details_existing_data = array_merge($sewing_details_existing_data, $sewing_details_data);
            }
            $date_wise_sewing_production_report->total_sewing_input = $total_sewing_input ?? 0;
            $date_wise_sewing_production_report->total_sewing_output = $total_sewing_output ?? 0;
            $date_wise_sewing_production_report->total_sewing_rejection = $total_sewing_rejection ?? 0;
            $date_wise_sewing_production_report->sewing_details = $sewing_details_existing_data;
            $date_wise_sewing_production_report->save();
        }
        return 1;
    }

    /**
     * Handle the sewingoutput "updated" event.
     *
     * @param \SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput $sewingoutput
     * @return void
     */
    public function updated(Sewingoutput $sewingoutput)
    {
        // start hourly prodcution report observer report
        /*$order = $sewingoutput->order;
        $column_name = 'hour_' . date('H');

        $qty = $sewingoutput->bundlecard->quantity
            - $sewingoutput->bundlecard->total_rejection
            - $sewingoutput->bundlecard->print_rejection
            - $sewingoutput->bundlecard->sewing_rejection;

        if ($order->factory_id) {

            $hourlyReport = HourlySewingProductionReport::where([
                'production_date' => Carbon::now()->toDateString(),
                'line_id' => $sewingoutput->line_id,
                'purchase_order_id' => $sewingoutput->purchase_order_id,
                'color_id' => $sewingoutput->color_id,
            ])->first();

            if (!$hourlyReport) {

                $hourlyReport = new HourlySewingProductionReport();
                $hourlyReport->production_date = Carbon::now()->toDateString();
                $hourlyReport->floor_id = $sewingoutput->line->floor_id ?? NULL;
                $hourlyReport->line_id = $sewingoutput->line_id;
                $hourlyReport->buyer_id = $sewingoutput->purchaseOrder->buyer_id;
                $hourlyReport->order_id = $sewingoutput->purchaseOrder->order_id;
                $hourlyReport->purchase_order_id = $sewingoutput->purchase_order_id;
                $hourlyReport->color_id = $sewingoutput->color_id;
            }

            $hourlyReport->$column_name += $qty;
            $hourlyReport->sewing_rejection += $sewingoutput->bundlecard->sewing_rejection;
            $hourlyReport->factory_id = $order->factory_id ?? NULL;
            $hourlyReport->save();
        }*/
    }

    /**
     * Handle the sewingoutput "deleted" event.
     *
     * @param \SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput $sewingoutput
     * @return void
     */
    public function deleted(Sewingoutput $sewingoutput)
    {
        //
    }

    /**
     * Handle the sewingoutput "restored" event.
     *
     * @param \SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput $sewingoutput
     * @return void
     */
    public function restored(Sewingoutput $sewingoutput)
    {
        //
    }

    /**
     * Handle the sewingoutput "force deleted" event.
     *
     * @param \SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput $sewingoutput
     * @return void
     */
    public function forceDeleted(Sewingoutput $sewingoutput)
    {
        //
    }
}
