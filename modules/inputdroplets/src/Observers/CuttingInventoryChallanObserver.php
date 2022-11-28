<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Observers;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\ColorSizeSummaryReportService;
use SkylarkSoft\GoRMG\Inputdroplets\Actions\DailyChallanWiseInputAction;
use SkylarkSoft\GoRMG\Inputdroplets\DTO\DailyChallanWiseInputDTO;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DailyChallanSizeWiseInput;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DailyChallanWiseInput;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DateWiseSewingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\LineSizeWiseSewingReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;


class CuttingInventoryChallanObserver
{
    /**
     * Handle the cutting inventory challan "created" event.
     *
     * @param \SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan $cuttingInventoryChallan
     * @return void
     */
    public function created(CuttingInventoryChallan $cuttingInventoryChallan)
    {
        if ($cuttingInventoryChallan->type == 'challan') {

            $cuttingInventories = $cuttingInventoryChallan->cutting_inventory;
            foreach ($cuttingInventories as $cuttingInventory) {

                $buyerId = $cuttingInventory->bundlecard->buyer_id;
                $orderId = $cuttingInventory->bundlecard->order_id;
                $garmentsItemId = $cuttingInventory->bundlecard->garments_item_id;
                $purchaseOrderId = $cuttingInventory->bundlecard->purchase_order_id;
                $colorId = $cuttingInventory->bundlecard->color_id;
                $sizeId = $cuttingInventory->bundlecard->size_id;
                $lineId = $cuttingInventoryChallan->line_id;
                $floorId = Line::where('id', $lineId)->first()->floor_id;

                $bundleQty = $cuttingInventory->bundlecard->quantity
                    - $cuttingInventory->bundlecard->total_rejection
                    - $cuttingInventory->bundlecard->print_rejection
                    - $cuttingInventory->bundlecard->embroidary_rejection;

                $scanning_user = $cuttingInventoryChallan->created_by;
                $production_date = $cuttingInventoryChallan->input_date;
                // Update total production report
                $this->updateTotalProductionReport($buyerId, $orderId, $garmentsItemId, $purchaseOrderId, $colorId, $bundleQty);
                // For Updating Date & color Wise Sewing Production
                $this->updateColorAndDateWiseProductionReport($buyerId, $orderId, $purchaseOrderId, $colorId, $production_date, $bundleQty);
                // For Updating Date Wise Sewing Production
                $this->updateSewingProductionReportForSewingInput($buyerId, $orderId, $purchaseOrderId, $colorId, $production_date, $lineId, $floorId, $bundleQty);
                // For Updating Date Wise Sewing Production
                $this->updateFinishingProductionReportForSewingInput($buyerId, $orderId, $purchaseOrderId, $colorId, $production_date, $lineId, $floorId, $bundleQty);
                $this->updateLineSizeWiseSewingReportForSewingInput($buyerId, $orderId, $purchaseOrderId, $colorId, $sizeId, $production_date, $lineId, $floorId, $bundleQty);
                // For Color Size Summary Report
                (new ColorSizeSummaryReportService())->make($cuttingInventory->bundlecard)->inputProduction($bundleQty)->saveOrUpdate();
                // For challan wise input report table

                $dateChallanInputDto = (new DailyChallanWiseInputDTO())->setFloorId($floorId)
                    ->setCuttingInventory($cuttingInventory)
                    ->setLineId($lineId)
                    ->setProductionDate($production_date);

                DailyChallanWiseInputAction::make(
                    $dateChallanInputDto->setDailyChallanInputModel(new DailyChallanWiseInput())
                )->setBundleQty($bundleQty)->storeAndUpdate();

                DailyChallanWiseInputAction::make(
                    $dateChallanInputDto->setDailyChallanInputModel(new DailyChallanSizeWiseInput())
                )->setBundleQty($bundleQty)->storeAndUpdate();
            }
        }
        DailyChallanWiseInput::query()->where('sewing_input', '<', 1)->delete();
        DailyChallanSizeWiseInput::query()->where('sewing_input', '<', 1)->delete();
    }

    private function updateTotalProductionReport($buyerId, $orderId, $garmentsItemId, $purchaseOrderId, $colorId, $bundleQty)
    {
        $totalReport = TotalProductionReport::where([
            'purchase_order_id' => $orderId,
            'garments_item_id' => $garmentsItemId,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId
        ])->first();

        if (!$totalReport) {
            $totalReport = new TotalProductionReport();
            $totalReport->buyer_id = $buyerId;
            $totalReport->order_id = $orderId;
            $totalReport->garments_item_id = $garmentsItemId;
            $totalReport->purchase_order_id = $purchaseOrderId;
            $totalReport->color_id = $colorId;
        }

        $totalReport->todays_input += $bundleQty;
        $totalReport->total_input += $bundleQty;
        $totalReport->save();

        return true;
    }

    private function updateFinishingProductionReportForSewingInput($buyerId, $orderId, $purchaseOrderId, $colorId, $production_date, $lineId, $floorId, $bundleQty)
    {
        $finishingProductionReport = FinishingProductionReport::where([
            'floor_id' => $floorId,
            'line_id' => $lineId,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'production_date' => $production_date
        ])->first();

        if (!$finishingProductionReport) {
            $finishingProductionReport = new FinishingProductionReport();
            $finishingProductionReport->floor_id = $floorId;
            $finishingProductionReport->line_id = $lineId;
            $finishingProductionReport->buyer_id = $buyerId;
            $finishingProductionReport->order_id = $orderId;
            $finishingProductionReport->purchase_order_id = $purchaseOrderId;
            $finishingProductionReport->color_id = $colorId;
            $finishingProductionReport->production_date = $production_date;
        }

        $finishingProductionReport->sewing_input += $bundleQty;
        $finishingProductionReport->save();
    }
    
    private function updateLineSizeWiseSewingReportForSewingInput($buyerId, $orderId, $purchaseOrderId, $colorId, $sizeId, $production_date, $lineId, $floorId, $bundleQty)
    {
        $rreport = LineSizeWiseSewingReport::where([
            'production_date' => $production_date,
            'floor_id' => $floorId,
            'line_id' => $lineId,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'size_id' => $sizeId,
        ])->first();

        if (!$rreport) {
            $rreport = new LineSizeWiseSewingReport();
            $rreport->production_date = $production_date;
            $rreport->floor_id = $floorId;
            $rreport->line_id = $lineId;
            $rreport->buyer_id = $buyerId;
            $rreport->order_id = $orderId;
            $rreport->purchase_order_id = $purchaseOrderId;
            $rreport->color_id = $colorId;
            $rreport->size_id = $sizeId;
        }

        $rreport->sewing_input += $bundleQty;
        $rreport->save();
    }

    private function updateColorAndDateWiseProductionReport($buyerId, $orderId, $purchaseOrderId, $colorId, $production_date, $bundleQty)
    {
        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'production_date' => $production_date,
        ])->first();

        if (!$dateAndColorWiseProduction) {
            $dateAndColorWiseProduction = new DateAndColorWiseProduction;
            $dateAndColorWiseProduction->buyer_id = $buyerId;
            $dateAndColorWiseProduction->order_id = $orderId;
            $dateAndColorWiseProduction->purchase_order_id = $purchaseOrderId;
            $dateAndColorWiseProduction->color_id = $colorId;
            $dateAndColorWiseProduction->production_date = $production_date;
        }

        $dateAndColorWiseProduction->input_qty += $bundleQty;
        $dateAndColorWiseProduction->save();
    }

    private function updateSewingProductionReportForSewingInput($buyerId, $orderId, $purchaseOrderId, $colorId, $production_date, $lineId, $floorId, $bundleQty)
    {
        $sewing_details_data = [];
        $sewing_details_data[] = [
            'buyer_id' => $buyerId,
            'order_id' => $orderId,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'sewing_input' => $bundleQty ?? 0,
            'sewing_output' => 0,
            'sewing_rejection' => 0,
        ];
        $date_wise_sewing_production_report = DateWiseSewingProductionReport::where([
            'floor_id' => $floorId,
            'line_id' => $lineId,
            'sewing_date' => $production_date
        ])->first();
        if (!$date_wise_sewing_production_report) {
            $date_wise_sewing_production_report = new DateWiseSewingProductionReport();
            $date_wise_sewing_production_report->floor_id = $floorId;
            $date_wise_sewing_production_report->line_id = $lineId;
            $date_wise_sewing_production_report->sewing_date = $production_date;
            $date_wise_sewing_production_report->sewing_details = $sewing_details_data;
            $date_wise_sewing_production_report->total_sewing_input = $bundleQty ?? 0;
            $date_wise_sewing_production_report->total_sewing_output = 0;
            $date_wise_sewing_production_report->total_sewing_rejection = 0;
            $date_wise_sewing_production_report->save();
        } else {
            $sewing_details_existing_data = $date_wise_sewing_production_report->sewing_details;
            $total_sewing_input = $date_wise_sewing_production_report->total_sewing_input;
            $total_sewing_output = $date_wise_sewing_production_report->total_sewing_output;
            $total_sewing_rejection = $date_wise_sewing_production_report->total_sewing_rejection;
            $total_sewing_input += $bundleQty;
            foreach ($sewing_details_existing_data as $key => $sewing_detail) {
                $is_detail_exist = 0;
                if ($sewing_detail['purchase_order_id'] == $purchaseOrderId && $sewing_detail['color_id'] == $colorId) {
                    $is_detail_exist = 1;
                    $sewing_details_existing_data[$key] = [
                        'buyer_id' => $sewing_detail['buyer_id'],
                        'purchase_order_id' => $sewing_detail['purchase_order_id'],
                        'order_id' => $sewing_detail['order_id'],
                        'color_id' => $sewing_detail['color_id'],
                        'sewing_input' => $sewing_detail['sewing_input'] + $bundleQty ?? 0,
                        'sewing_output' => $sewing_detail['sewing_output'] ?? 0,
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

        return true;
    }

    /**
     * Handle the cutting inventory challan "updated" event.
     *
     * @param \SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan $cuttingInventoryChallan
     * @return void
     */
    public function updated(CuttingInventoryChallan $cuttingInventoryChallan)
    {
        if ($cuttingInventoryChallan->isDirty('type') && $cuttingInventoryChallan->type == 'challan') {

            $cuttingInventories = $cuttingInventoryChallan->cutting_inventory;
            foreach ($cuttingInventories as $key => $cuttingInventory) {

                $buyerId = $cuttingInventory->bundlecard->buyer_id;
                $orderId = $cuttingInventory->bundlecard->order_id;
                $garmentsItemId = $cuttingInventory->bundlecard->garments_item_id;
                $purchaseOrderId = $cuttingInventory->bundlecard->purchase_order_id;
                $colorId = $cuttingInventory->bundlecard->color_id;
                $sizeId = $cuttingInventory->bundlecard->size_id;
                $lineId = $cuttingInventoryChallan->line_id;
                $floorId = Line::where('id', $lineId)->first()->floor_id;

                $bundleQty = $cuttingInventory->bundlecard->quantity
                    - $cuttingInventory->bundlecard->total_rejection
                    - $cuttingInventory->bundlecard->print_rejection
                    - $cuttingInventory->bundlecard->embroidary_rejection;

                $scanning_user = $cuttingInventoryChallan->created_by;
                $production_date = $cuttingInventoryChallan->input_date;
                // Update total production report
                $this->updateTotalProductionReport($buyerId, $orderId, $garmentsItemId, $purchaseOrderId, $colorId, $bundleQty);
                // For Updating Date & color Wise Sewing Production
                $this->updateColorAndDateWiseProductionReport($buyerId, $orderId, $purchaseOrderId, $colorId, $production_date, $bundleQty);
                // For Updating Date Wise Sewing Production
                $this->updateSewingProductionReportForSewingInput($buyerId, $orderId, $purchaseOrderId, $colorId, $production_date, $lineId, $floorId, $bundleQty);
                // For Updating Date Wise Sewing Production
                $this->updateFinishingProductionReportForSewingInput($buyerId, $orderId, $purchaseOrderId, $colorId, $production_date, $lineId, $floorId, $bundleQty);
                $this->updateLineSizeWiseSewingReportForSewingInput($buyerId, $orderId, $purchaseOrderId, $colorId, $sizeId, $production_date, $lineId, $floorId, $bundleQty);
                // For challan wise input report table

                $dateChallanInputDto = (new DailyChallanWiseInputDTO())->setFloorId($floorId)
                    ->setCuttingInventory($cuttingInventory)
                    ->setLineId($lineId)
                    ->setProductionDate($production_date);

                DailyChallanWiseInputAction::make(
                    $dateChallanInputDto->setDailyChallanInputModel(new DailyChallanWiseInput())
                )->setBundleQty($bundleQty)->storeAndUpdate();

                DailyChallanWiseInputAction::make(
                    $dateChallanInputDto->setDailyChallanInputModel(new DailyChallanSizeWiseInput())
                )->setBundleQty($bundleQty)->storeAndUpdate();
            }
        }
        if ($cuttingInventoryChallan->getOriginal('type') != 'tag'
            && $cuttingInventoryChallan->type == 'challan'
            && $cuttingInventoryChallan->isDirty('line_id')) {
            // FOR UPDATING LINE
            $old_line_id = $cuttingInventoryChallan->getOriginal('line_id');
            $old_floor_id = Line::where('id', $old_line_id)->first()->floor_id;
            $current_line_id = $cuttingInventoryChallan->line_id;
            $current_floor_id = Line::where('id', $current_line_id)->first()->floor_id;

            $cuttingInventories = $cuttingInventoryChallan->cutting_inventory;
            $input_date = $cuttingInventoryChallan->input_date;
            foreach ($cuttingInventories as $key => $cuttingInventory) {
                $buyerId = $cuttingInventory->bundlecard->buyer_id;
                $orderId = $cuttingInventory->bundlecard->order_id;
                $purchaseOrderId = $cuttingInventory->bundlecard->purchase_order_id;
                $colorId = $cuttingInventory->bundlecard->color_id;
                $sizeId = $cuttingInventory->bundlecard->size_id;

                $bundleQty = $cuttingInventory->bundlecard->quantity
                    - $cuttingInventory->bundlecard->total_rejection
                    - $cuttingInventory->bundlecard->print_rejection
                    - $cuttingInventory->bundlecard->embroidary_rejection;

                // For Updating Daily Sewing Production
                $this->updateSewingProductionReportForLineChange($input_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $old_line_id, $current_line_id, $old_floor_id, $current_floor_id, $bundleQty);
                // For Updating Date Wise Sewing Production
                $this->updateFinishingProductionReportForLineChange($input_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $old_line_id, $current_line_id, $old_floor_id, $current_floor_id, $bundleQty);
                $this->updateLineSizeWiseSewingReportForLineChange($input_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $sizeId, $old_line_id, $current_line_id, $old_floor_id, $current_floor_id, $bundleQty);

                // Daily Challan Wise Input
                $dateChallanInputDto = (new DailyChallanWiseInputDTO())->setFloorId($old_floor_id)
                    ->setCuttingInventory($cuttingInventory)
                    ->setLineId($old_line_id)
                    ->setProductionDate($input_date);

                DailyChallanWiseInputAction::make(
                    $dateChallanInputDto->setDailyChallanInputModel(new DailyChallanWiseInput())
                )->decreaseBundleQty($bundleQty)->storeAndUpdate();

                DailyChallanWiseInputAction::make(
                    $dateChallanInputDto->setDailyChallanInputModel(new DailyChallanSizeWiseInput())
                )->decreaseBundleQty($bundleQty)->storeAndUpdate();

                $dateChallanInputDto->setFloorId($current_floor_id)->setLineId($current_line_id);

                DailyChallanWiseInputAction::make(
                    $dateChallanInputDto->setDailyChallanInputModel(new DailyChallanWiseInput())
                )->setBundleQty($bundleQty)->storeAndUpdate();

                DailyChallanWiseInputAction::make(
                    $dateChallanInputDto->setDailyChallanInputModel(new DailyChallanSizeWiseInput())
                )->setBundleQty($bundleQty)->storeAndUpdate();
            }
        }
        DailyChallanWiseInput::query()->where('sewing_input', '<', 1)->delete();
        DailyChallanSizeWiseInput::query()->where('sewing_input', '<', 1)->delete();
    }

    private function updateFinishingProductionReportForLineChange($input_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $old_line_id, $current_line_id, $old_floor_id, $current_floor_id, $bundleQty)
    {
        // FOR OLD LINE
        $oldFinishingProductionReport = FinishingProductionReport::where([
            'floor_id' => $old_floor_id,
            'line_id' => $old_line_id,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'production_date' => $input_date,
        ])->first();

        if ($oldFinishingProductionReport) {
            if ($oldFinishingProductionReport->sewing_input >= $bundleQty) {
                $oldFinishingProductionReport->sewing_input -= $bundleQty;
            } else {
                $oldFinishingProductionReport->sewing_input = 0;
            }
            $oldFinishingProductionReport->save();
        }

        // FOR NEW LINE
        $floor_id = $current_floor_id;
        $line_id = $current_line_id;

        $finishingProductionReport = FinishingProductionReport::where([
            'floor_id' => $floor_id,
            'line_id' => $line_id,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'production_date' => $input_date,
        ])->first();

        if (!$finishingProductionReport) {
            $finishingProductionReport = new FinishingProductionReport;
            $finishingProductionReport->floor_id = $floor_id;
            $finishingProductionReport->line_id = $line_id;
            $finishingProductionReport->buyer_id = $buyerId;
            $finishingProductionReport->purchase_order_id = $purchaseOrderId;
            $finishingProductionReport->order_id = $orderId;
            $finishingProductionReport->color_id = $colorId;
            $finishingProductionReport->production_date = $input_date;
        }

        $finishingProductionReport->sewing_input += $bundleQty;
        $finishingProductionReport->save();
    }
    
    private function updateLineSizeWiseSewingReportForLineChange($input_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $sizeId, $old_line_id, $current_line_id, $old_floor_id, $current_floor_id, $bundleQty)
    {
        // FOR OLD LINE
        $oldReport = LineSizeWiseSewingReport::where([
            'floor_id' => $old_floor_id,
            'line_id' => $old_line_id,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'size_id' => $sizeId,
            'production_date' => $input_date,
        ])->first();

        if ($oldReport) {
            if ($oldReport->sewing_input >= $bundleQty) {
                $oldReport->sewing_input -= $bundleQty;
            } else {
                $oldReport->sewing_input = 0;
            }
            $oldReport->save();
        }

        // FOR NEW LINE
        $floor_id = $current_floor_id;
        $line_id = $current_line_id;

        $finishingProductionReport = LineSizeWiseSewingReport::where([
            'floor_id' => $floor_id,
            'line_id' => $line_id,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'size_id' => $sizeId,
            'production_date' => $input_date,
        ])->first();

        if (!$finishingProductionReport) {
            $finishingProductionReport = new LineSizeWiseSewingReport();
            $finishingProductionReport->floor_id = $floor_id;
            $finishingProductionReport->line_id = $line_id;
            $finishingProductionReport->buyer_id = $buyerId;
            $finishingProductionReport->purchase_order_id = $purchaseOrderId;
            $finishingProductionReport->order_id = $orderId;
            $finishingProductionReport->color_id = $colorId;
            $finishingProductionReport->size_id = $sizeId;
            $finishingProductionReport->production_date = $input_date;
        }

        $finishingProductionReport->sewing_input += $bundleQty;
        $finishingProductionReport->save();
    }

    private function updateSewingProductionReportForLineChange($input_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $old_line_id, $current_line_id, $old_floor_id, $current_floor_id, $bundleQty)
    {
        // FOR OLD LINE
        $old_date_wise_sewing_production_report = DateWiseSewingProductionReport::where([
            'floor_id' => $old_floor_id,
            'line_id' => $old_line_id,
            'sewing_date' => $input_date
        ])->first();

        if ($old_date_wise_sewing_production_report) {
            $sewing_details_existing_data = $old_date_wise_sewing_production_report->sewing_details;
            $total_sewing_input = $old_date_wise_sewing_production_report->total_sewing_input;
            if ($total_sewing_input >= $bundleQty) {
                $total_sewing_input -= $bundleQty;
            } else {
                $total_sewing_input = 0;
            }

            foreach ($sewing_details_existing_data as $key => $sewing_detail) {
                $is_detail_exist = 0;
                if ($sewing_detail['purchase_order_id'] == $purchaseOrderId && $sewing_detail['color_id'] == $colorId) {
                    $is_detail_exist = 1;
                    $sewing_details_existing_data[$key] = [
                        'buyer_id' => $sewing_detail['buyer_id'],
                        'purchase_order_id' => $sewing_detail['purchase_order_id'],
                        'order_id' => $sewing_detail['order_id'],
                        'color_id' => $sewing_detail['color_id'],
                        'sewing_input' => $sewing_detail['sewing_input'] >= $bundleQty ? $sewing_detail['sewing_input'] - $bundleQty : 0,
                        'sewing_output' => $sewing_detail['sewing_output'] ?? 0,
                        'sewing_rejection' => $sewing_detail['sewing_rejection'] ?? 0,
                    ];
                    break;
                }
            }
            if ($is_detail_exist == 1) {
                $old_date_wise_sewing_production_report->total_sewing_input = $total_sewing_input ?? 0;
                $old_date_wise_sewing_production_report->sewing_details = $sewing_details_existing_data;
                $old_date_wise_sewing_production_report->save();
            }
        }
        // FOR NEW LINE
        $floor_id = $current_floor_id;
        $line_id = $current_line_id;

        $sewing_details_data = [];
        $sewing_details_data[] = [
            'buyer_id' => $buyerId,
            'purchase_order_id' => $purchaseOrderId,
            'order_id' => $orderId,
            'color_id' => $colorId,
            'sewing_input' => $bundleQty ?? 0,
            'sewing_output' => 0,
            'sewing_rejection' => 0,
        ];
        $date_wise_sewing_production_report = DateWiseSewingProductionReport::where([
            'floor_id' => $floor_id,
            'line_id' => $line_id,
            'sewing_date' => $input_date
        ])->first();
        if (!$date_wise_sewing_production_report) {
            $date_wise_sewing_production_report = new DateWiseSewingProductionReport();
            $date_wise_sewing_production_report->floor_id = $floor_id;
            $date_wise_sewing_production_report->line_id = $line_id;
            $date_wise_sewing_production_report->sewing_date = $input_date;
            $date_wise_sewing_production_report->sewing_details = $sewing_details_data;
            $date_wise_sewing_production_report->total_sewing_input = $bundleQty ?? 0;
            $date_wise_sewing_production_report->save();
        } else {
            $sewing_details_existing_data = $date_wise_sewing_production_report->sewing_details;
            $total_sewing_input = $date_wise_sewing_production_report->total_sewing_input;
            $total_sewing_input += $bundleQty;
            foreach ($sewing_details_existing_data as $key => $sewing_detail) {
                $is_detail_exist = 0;
                if ($sewing_detail['purchase_order_id'] == $purchaseOrderId && $sewing_detail['color_id'] == $colorId) {
                    $is_detail_exist = 1;
                    $sewing_details_existing_data[$key] = [
                        'buyer_id' => $sewing_detail['buyer_id'],
                        'purchase_order_id' => $sewing_detail['purchase_order_id'],
                        'order_id' => $sewing_detail['order_id'],
                        'color_id' => $sewing_detail['color_id'],
                        'sewing_input' => $sewing_detail['sewing_input'] + $bundleQty ?? 0,
                        'sewing_output' => $sewing_detail['sewing_output'] ?? 0,
                        'sewing_rejection' => $sewing_detail['sewing_rejection'] ?? 0,
                    ];
                    break;
                }
            }
            if ($is_detail_exist == 0) {
                $sewing_details_existing_data = array_merge($sewing_details_existing_data, $sewing_details_data);
            }
            $date_wise_sewing_production_report->total_sewing_input = $total_sewing_input ?? 0;
            $date_wise_sewing_production_report->sewing_details = $sewing_details_existing_data;
            $date_wise_sewing_production_report->save();
        }
    }

    /**
     * Handle the cutting inventory challan "deleted" event.
     *
     * @param \SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan $cuttingInventoryChallan
     * @return void
     */
    public function deleted(CuttingInventoryChallan $cuttingInventoryChallan)
    {

    }

    /**
     * Handle the cutting inventory challan "restored" event.
     *
     * @param \SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan $cuttingInventoryChallan
     * @return void
     */
    public function restored(CuttingInventoryChallan $cuttingInventoryChallan)
    {
        //
    }

    /**
     * Handle the cutting inventory challan "force deleted" event.
     *
     * @param \SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan $cuttingInventoryChallan
     * @return void
     */
    public function forceDeleted(CuttingInventoryChallan $cuttingInventoryChallan)
    {
        //
    }
}
