<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Observers;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateWiseCuttingProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\ColorSizeSummaryReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\ColorSizeSummaryReportService;

class BundleCardObserver
{
    /**
     * Handle the bundle card "created" event.
     *
     * @param \SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard $bundleCard
     * @return void
     */
    public function created(BundleCard $bundleCard)
    {
        //
    }

    public function updating(BundleCard $bundleCard)
    {
        // if ($bundleCard->isDirty('status') && $bundleCard->status == 1) {

        //     $garmentsItemId = $bundleCard->garments_item_id;
        //     $purchaseOrderId = $bundleCard->purchase_order_id;
        //     $orderId = $bundleCard->order_id;
        //     $colorId = $bundleCard->color_id;

        //     $cuttingReport = TotalProductionReport::where([
        //         'order_id' => $orderId,
        //         'garments_item_id' => $garmentsItemId,
        //         'purchase_order_id' => $purchaseOrderId,
        //         'color_id' => $colorId
        //     ])->first();

        //     if (!$cuttingReport) {
        //         $cuttingReport = new TotalProductionReport();

        //         $cuttingReport->buyer_id = $bundleCard->buyer_id;
        //         $cuttingReport->order_id = $orderId;
        //         $cuttingReport->garments_item_id = $garmentsItemId;
        //         $cuttingReport->purchase_order_id = $purchaseOrderId;
        //         $cuttingReport->color_id = $colorId;
        //     }

        //     $cuttingReport = $this->updateCuttingQuantity($bundleCard, $cuttingReport);
        //     $cuttingReport = $this->updateCuttingRejection($bundleCard, $cuttingReport);

        //     $cuttingReport->save();

        //     // For Color Size Summary Report
        //     (new ColorSizeSummaryReportService())->make($bundleCard)->cuttingProduction($bundleCard)->saveOrUpdate();
        //     // For Date Wise Cutting Report
        //     $this->dateWiseCuttingProductionUpdate($bundleCard);
        //     // For date and color wise report
        //     $this->dateAndColorWiseProductionUpdate($bundleCard);
        //     // For date and table wise report
        //     $this->dateTableWiseCutProductionReportUpdate($bundleCard);
        // }
    }

    private function dateTableWiseCutProductionReportUpdate($bundleCard)
    {
        $orderId = $bundleCard->order_id;
        $garmentsItemId = $bundleCard->garments_item_id;
        $purchaseOrderId = $bundleCard->purchase_order_id;
        $colorId = $bundleCard->color_id;

        $dateTableWiseCutProductionReports = DateTableWiseCutProductionReport::where([
            'production_date' => $bundleCard->cutting_date,
            'cutting_table_id' => $bundleCard->cutting_table_id,
            'order_id' => $orderId,
            'garments_item_id' => $garmentsItemId,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'size_id' => $bundleCard->size_id,
        ])->first();

        if (!$dateTableWiseCutProductionReports) {
            $dateTableWiseCutProductionReports = new DateTableWiseCutProductionReport();

            $dateTableWiseCutProductionReports->production_date = $bundleCard->cutting_date;
            $dateTableWiseCutProductionReports->cutting_floor_id = $bundleCard->cutting_floor_id;
            $dateTableWiseCutProductionReports->cutting_table_id = $bundleCard->cutting_table_id;
            $dateTableWiseCutProductionReports->buyer_id = $bundleCard->buyer_id;
            $dateTableWiseCutProductionReports->order_id = $orderId;
            $dateTableWiseCutProductionReports->garments_item_id = $garmentsItemId;
            $dateTableWiseCutProductionReports->purchase_order_id = $purchaseOrderId;
            $dateTableWiseCutProductionReports->color_id = $colorId;
            $dateTableWiseCutProductionReports->size_id = $bundleCard->size_id;
        }

        $dateTableWiseCutProductionReports = $this->updateDateTableWiseCuttingQuantity($bundleCard, $dateTableWiseCutProductionReports);
        $dateTableWiseCutProductionReports = $this->updateDateTableWiseCuttingRejection($bundleCard, $dateTableWiseCutProductionReports);

        return $dateTableWiseCutProductionReports->save();
    }

    private function updateDateTableWiseCuttingQuantity(BundleCard $bundleCard, DateTableWiseCutProductionReport $dateTableWiseCutProductionReports)
    {
        $original = $bundleCard->getOriginal();
        if ($bundleCard->isDirty('status') && $bundleCard->status == 1) {
            $dateTableWiseCutProductionReports->cutting_qty += $bundleCard->quantity;
        }

        return $dateTableWiseCutProductionReports;
    }

    private function updateDateTableWiseCuttingRejection(BundleCard $bundleCard, DateTableWiseCutProductionReport $dateTableWiseCutProductionReports)
    {
        $original = $bundleCard->getOriginal();
        if ($bundleCard->isDirty('total_rejection')) {
            $dateTableWiseCutProductionReports->cutting_rejection_qty -= $original['total_rejection'];
            $dateTableWiseCutProductionReports->cutting_rejection_qty += $bundleCard->total_rejection;
        }

        return $dateTableWiseCutProductionReports;
    }

    private function dateAndColorWiseProductionUpdate($bundleCard)
    {
        $orderId = $bundleCard->order_id;
        $purchaseOrderId = $bundleCard->purchase_order_id;
        $colorId = $bundleCard->color_id;

        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $bundleCard->cutting_date,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
        ])->first();

        if (!$dateAndColorWiseProduction) {
            $dateAndColorWiseProduction = new DateAndColorWiseProduction();

            $dateAndColorWiseProduction->buyer_id = $bundleCard->buyer_id;
            $dateAndColorWiseProduction->order_id = $orderId;
            $dateAndColorWiseProduction->purchase_order_id = $purchaseOrderId;
            $dateAndColorWiseProduction->color_id = $colorId;
            $dateAndColorWiseProduction->production_date = $bundleCard->cutting_date;
        }

        $dateAndColorWiseProduction = $this->updateColorAndDateWiseCuttingQuantity($bundleCard, $dateAndColorWiseProduction);
        $dateAndColorWiseProduction = $this->updateColorAndDateWiseCuttingRejection($bundleCard, $dateAndColorWiseProduction);

        $dateAndColorWiseProduction->save();
    }

    private function updateColorAndDateWiseCuttingQuantity(BundleCard $bundleCard, DateAndColorWiseProduction $dateAndColorWiseProduction)
    {
        $original = $bundleCard->getOriginal();
        if ($bundleCard->isDirty('status') && $bundleCard->status == 1) {
            $dateAndColorWiseProduction->cutting_qty += $bundleCard->quantity;
        }

        /*$original = $bundleCard->getOriginal();
        $dateAndColorWiseProduction->cutting_qty += $bundleCard->quantity;*/

        return $dateAndColorWiseProduction;
    }

    private function updateColorAndDateWiseCuttingRejection(BundleCard $bundleCard, DateAndColorWiseProduction $dateAndColorWiseProduction)
    {
        $original = $bundleCard->getOriginal();
        if ($bundleCard->isDirty('total_rejection')) {
            $dateAndColorWiseProduction->cutting_rejection_qty -= $original['total_rejection'];
            $dateAndColorWiseProduction->cutting_rejection_qty += $bundleCard->total_rejection;
        }

        return $dateAndColorWiseProduction;
    }

    private function updateCuttingQuantity(BundleCard $bundleCard, TotalProductionReport $cuttingReport)
    {
        $original = $bundleCard->getOriginal();

        if ($bundleCard->isDirty('status') && $bundleCard->status == 1) {
            $cuttingReport->total_cutting += $bundleCard->quantity;
            $cuttingReport->todays_cutting += $bundleCard->quantity;
        }

        if ($bundleCard->isDirty('quantity') && $bundleCard->status == 1) {
            $cuttingReport->total_cutting -= $original['quantity'];
            $cuttingReport->total_cutting += $bundleCard->quantity;

            if ($bundleCard->cutting_date == Carbon::today()->toDateString()) {
                $cuttingReport->todays_cutting -= $original['quantity'];
                $cuttingReport->todays_cutting += $bundleCard->quantity;
            }
        }

        return $cuttingReport;
    }

    private function updateCuttingRejection(BundleCard $bundleCard, TotalProductionReport $cuttingReport)
    {
        $original = $bundleCard->getOriginal();

        if ($bundleCard->isDirty('total_rejection')) {
            $cuttingReport->total_cutting_rejection -= $original['total_rejection'];
            $cuttingReport->total_cutting_rejection += $bundleCard->total_rejection;

            //if ($bundleCard->cutting_date == Carbon::today()->toDateString()) {
            $cuttingReport->todays_cutting_rejection -= $original['total_rejection'];
            $cuttingReport->todays_cutting_rejection += $bundleCard->total_rejection;
            //}
        }

        return $cuttingReport;
    }

    public function dateWiseCuttingProductionUpdate($bundlecard)
    {
        $cutting_date = $bundlecard->cutting_date;
        $cutting_floor_id = $bundlecard->cutting_floor_id;
        $cutting_table_id = $bundlecard->cutting_table_id;
        $purchaseOrderId = $bundlecard->purchase_order_id;

        $cutting_details_data = [];
        $cutting_details_data[] = [
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $bundlecard->color_id,
            'size_id' => $bundlecard->size_id,
            'cutting_qty' => $bundlecard->quantity,
            'cutting_rejection' => $bundlecard->total_rejection,
        ];

        $date_wise_cutting_production_report = DateWiseCuttingProductionReport::where([
            'cutting_date' => $cutting_date,
            'cutting_floor_id' => $cutting_floor_id,
            'cutting_table_id' => $cutting_table_id
        ])->first();

        if (!$date_wise_cutting_production_report) {
            $date_wise_cutting_production_report = new DateWiseCuttingProductionReport();
            $date_wise_cutting_production_report->cutting_date = $cutting_date;
            $date_wise_cutting_production_report->cutting_floor_id = $cutting_floor_id;
            $date_wise_cutting_production_report->cutting_table_id = $cutting_table_id;
            $date_wise_cutting_production_report->cutting_details = $cutting_details_data;
            $date_wise_cutting_production_report->total_cutting = $bundlecard->quantity ?? 0;
            $date_wise_cutting_production_report->total_rejection = $bundlecard->total_rejection ?? 0;
            $date_wise_cutting_production_report->save();
        } else {
            $cutting_details_existing_data = $date_wise_cutting_production_report->cutting_details;
            $total_cutting = $date_wise_cutting_production_report->total_cutting + $bundlecard->quantity;
            $total_rejection = $date_wise_cutting_production_report->total_rejection + $bundlecard->total_rejection;

            foreach ($cutting_details_existing_data as $key => $cutting_detail) {
                $is_detail_exist = 0;
                if ($cutting_detail['purchase_order_id'] == $purchaseOrderId && $cutting_detail['color_id'] == $bundlecard->color_id && $cutting_detail['size_id'] == $bundlecard->size_id) {
                    $is_detail_exist = 1;
                    $cutting_details_existing_data[$key] = [
                        'purchase_order_id' => $purchaseOrderId,
                        'color_id' => $bundlecard->color_id,
                        'size_id' => $bundlecard->size_id,
                        'cutting_qty' => $bundlecard->quantity + $cutting_detail['cutting_qty'] ?? 0,
                        'cutting_rejection' => $bundlecard->total_rejection + $cutting_detail['cutting_rejection'] ?? 0,
                    ];
                    break;
                }
            }
            if ($is_detail_exist == 0) {
                $cutting_details_existing_data = array_merge($cutting_details_existing_data, $cutting_details_data);
            }
            $date_wise_cutting_production_report->total_cutting = $total_cutting ?? 0;
            $date_wise_cutting_production_report->total_rejection = $total_rejection ?? 0;
            $date_wise_cutting_production_report->cutting_details = $cutting_details_existing_data;
            $date_wise_cutting_production_report->save();
        }
        return true;
    }

    /**
     * Handle the bundle card "updated" event.
     *
     * @param \SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard $bundleCard
     * @return void
     */
    public function updated(BundleCard $bundleCard)
    {
        //
    }

    /**
     * Handle the bundle card "deleted" event.
     *
     * @param \SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard $bundleCard
     * @return void
     */
    public function deleted(BundleCard $bundleCard)
    {
        if ($bundleCard->status && $bundleCard->details->is_regenerated == 0) {
            // TotalProductionReport
            $this->updateTotalProductionReportForDelete($bundleCard);
            // DateAndColorWiseReport
            $this->updateDateAndColorWiseReportForDelete($bundleCard);
            // DateTableWiseReport
            $this->updateDateTableWiseCutProductionReportForDelete($bundleCard);
            // DateWiseCuttingReport
            $this->updateDateWiseCuttingReportForDelete($bundleCard);
            // ColorSizeSummaryReport
            $this->updateColorSizeSummaryReportForCuttingProductionDelete($bundleCard);
        }
    }

    private function updateTotalProductionReportForDelete($bundleCard)
    {
        $cuttingReport = TotalProductionReport::where([
            'order_id' => $bundleCard->order_id,
            'garments_item_id' => $bundleCard->garments_item_id,
            'purchase_order_id' => $bundleCard->purchase_order_id,
            'color_id' => $bundleCard->color_id
        ])->first();

        $qty = $bundleCard->quantity ?? 0;
        $rejection = $bundleCard->total_rejection ?? 0;

        if ($cuttingReport) {
            if ($bundleCard->cutting_date == Carbon::today()->format('Y-m-d')) {
                $cuttingReport->todays_cutting -= $qty;
                $cuttingReport->todays_cutting_rejection -= $rejection;
            }
            $cuttingReport->total_cutting -= $qty;
            $cuttingReport->total_cutting_rejection -= $rejection;
            $cuttingReport->save();
        }

        return true;
    }

    private function updateDateAndColorWiseReportForDelete($bundleCard)
    {
        $cutting_date = $bundleCard->cutting_date;
        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $cutting_date,
            'purchase_order_id' => $bundleCard->purchase_order_id,
            'color_id' => $bundleCard->color_id,
        ])->first();

        $qty = $bundleCard->quantity ?? 0;
        $rejection = $bundleCard->total_rejection ?? 0;

        if ($dateAndColorWiseProduction) {
            $dateAndColorWiseProduction->cutting_qty -= $qty;
            $dateAndColorWiseProduction->cutting_rejection_qty -= $rejection;
            $dateAndColorWiseProduction->save();
        }

        return true;
    }

    private function updateDateTableWiseCutProductionReportForDelete($bundleCard)
    {
        $dateAndColorWiseProduction = DateTableWiseCutProductionReport::where([
            'production_date' => $bundleCard->cutting_date,
            'cutting_table_id' => $bundleCard->cutting_table_id,
            'order_id' => $bundleCard->order_id,
            'garments_item_id' => $bundleCard->garments_item_id,
            'purchase_order_id' => $bundleCard->purchase_order_id,
            'color_id' => $bundleCard->color_id,
            'size_id' => $bundleCard->size_id,
        ])->first();

        $qty = $bundleCard->quantity ?? 0;
        $rejection = $bundleCard->total_rejection ?? 0;

        if ($dateAndColorWiseProduction) {
            $dateAndColorWiseProduction->cutting_qty -= $qty;
            $dateAndColorWiseProduction->cutting_rejection_qty -= $rejection;
            $dateAndColorWiseProduction->save();
        }

        return true;
    }

    private function updateDateWiseCuttingReportForDelete($bundleCard)
    {
        $cutting_date = $bundleCard->cutting_date;
        $cutting_floor_id = $bundleCard->cutting_floor_id;
        $cutting_table_id = $bundleCard->cutting_table_id;
        $purchaseOrderId = $bundleCard->purchase_order_id;

        $qty = $bundleCard->quantity ?? 0;
        $rejection = $bundleCard->total_rejection ?? 0;

        $date_wise_cutting_production_report = DateWiseCuttingProductionReport::where([
            'cutting_date' => $cutting_date,
            'cutting_floor_id' => $cutting_floor_id,
            'cutting_table_id' => $cutting_table_id
        ])->first();

        if ($date_wise_cutting_production_report) {
            $cutting_details_existing_data = $date_wise_cutting_production_report->cutting_details;
            $total_cutting = $date_wise_cutting_production_report->total_cutting;
            $total_rejection = $date_wise_cutting_production_report->total_rejection;

            foreach ($cutting_details_existing_data as $key => $cutting_detail) {
                $is_detail_exist = 0;

                if (
                    $cutting_detail['purchase_order_id'] == $purchaseOrderId
                    && $cutting_detail['color_id'] == $bundleCard->color_id
                    && $cutting_detail['size_id'] == $bundleCard->size_id
                ) {

                    $is_detail_exist = 1;
                    $cutting_details_existing_data[$key] = [
                        'purchase_order_id' => $purchaseOrderId,
                        'color_id' => $cutting_detail['color_id'],
                        'size_id' => $cutting_detail['size_id'],
                        'cutting_qty' => $cutting_detail['cutting_qty'] - $qty ?? 0,
                        'cutting_rejection' => $cutting_detail['cutting_rejection'] - $rejection ?? 0,
                    ];
                    break;
                }
            }
            if ($is_detail_exist == 1) {
                $date_wise_cutting_production_report->total_cutting = $total_cutting - $qty ?? 0;
                $date_wise_cutting_production_report->total_rejection = $total_rejection - $rejection ?? 0;
                $date_wise_cutting_production_report->cutting_details = $cutting_details_existing_data;
                $date_wise_cutting_production_report->save();
            }
        }
        return true;
    }

    private function updateColorSizeSummaryReportForCuttingProductionDelete($bundleCard)
    {
        $purchaseOrderId = $bundleCard->purchase_order_id;
        $colorId = $bundleCard->color_id;
        $sizeId = $bundleCard->size_id;
        $bundleQty = $bundleCard->quantity ?? 0;
        $rejection = $bundleCard->total_rejection ?? 0;

        $cuttingReport = ColorSizeSummaryReport::where([
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'size_id' => $sizeId
        ])->first();

        if ($cuttingReport) {
            $cuttingReport->total_cutting -= $bundleQty;
            $cuttingReport->total_cutting_rejection -= $rejection;
            $cuttingReport->save();
        }
    }

    /**
     * Handle the bundle card "restored" event.
     *
     * @param \SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard $bundleCard
     * @return void
     */
    public function restored(BundleCard $bundleCard)
    {
        //
    }

    /**
     * Handle the bundle card "force deleted" event.
     *
     * @param \SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard $bundleCard
     * @return void
     */
    public function forceDeleted(BundleCard $bundleCard)
    {
        //
    }
}
