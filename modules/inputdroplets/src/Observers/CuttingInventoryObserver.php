<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Observers;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\ColorSizeSummaryReportService;
use SkylarkSoft\GoRMG\Inputdroplets\Actions\DailyChallanWiseInputAction;
use SkylarkSoft\GoRMG\Inputdroplets\DTO\DailyChallanWiseInputDTO;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DailyChallanSizeWiseInput;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DailyChallanWiseInput;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DateWiseSewingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\LineSizeWiseSewingReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateFloorWisePrintEmbrReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintEmbrProductionReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintProductionReport;

class CuttingInventoryObserver
{
    /**
     * Handle the cutting inventory "created" event.
     *
     * @param \SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory $cuttingInventory
     * @return void
     */
    public function created(CuttingInventory $cuttingInventory)
    {
        $bundlecard = $cuttingInventory->bundlecard;

        $cuttingFloorId = $bundlecard->cutting_floor_id;
        $buyerId = $bundlecard->buyer_id;
        $orderId = $bundlecard->order_id;
        $garmentsItemId = $bundlecard->garments_item_id;
        $purchaseOrderId = $bundlecard->purchase_order_id;
        $colorId = $bundlecard->color_id;
        $sizeId = $bundlecard->size_id;
        $receive_date = operationDate();

        $print_status = $cuttingInventory->print_status;
        $data = [
            'bundlecard' => $bundlecard,
            'receive_date' => $receive_date,
            'cuttingFloorId' => $cuttingFloorId,
            'buyerId' => $buyerId,
            'orderId' => $orderId,
            'garmentsItemId' => $garmentsItemId,
            'purchaseOrderId' => $purchaseOrderId,
            'colorId' => $colorId,
            'sizeId' => $sizeId,
            'print_status' => $print_status,
        ];
        if ($cuttingInventory->print_status == 1) {

            $receive_qty = $bundlecard->quantity - $bundlecard->total_rejection - $bundlecard->print_rejection;
            $data['receive_qty'] = $receive_qty;
            // Update TotalProductionReport Table
            $this->updateTotalProductionReportForPrintEmbrReceive($buyerId, $orderId, $garmentsItemId, $purchaseOrderId, $colorId, $receive_qty, $print_status);
            // For Updating Date and Color Production Report
            $this->updateColorAndDateWiseProductionReportForPrintEmbrReceive($receive_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $receive_qty, $print_status);
            // For Updating Date and Size Production Report
            $this->updateDateWisePrintEmbrProductionReport($receive_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $sizeId, $receive_qty, $print_status);
            $this->updateDateFloorWisePrintEmbrReport($data);
            // For Updating Print Production Report For Print Receive
            $this->updateDateWisePrintProductionReportForPrintReceive($bundlecard, $receive_date);
        } elseif ($cuttingInventory->print_status == 2) {
            $receive_qty = $bundlecard->quantity - $bundlecard->total_rejection - $bundlecard->embroidary_rejection;
            $data['receive_qty'] = $receive_qty;

            // Update TotalProductionReport Table
            $this->updateTotalProductionReportForPrintEmbrReceive($buyerId, $orderId, $garmentsItemId, $purchaseOrderId, $colorId, $receive_qty, $print_status);
            // For Updating Date and Color Production Report
            $this->updateColorAndDateWiseProductionReportForPrintEmbrReceive($receive_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $receive_qty, $print_status);
            // For Updating Date and Size Production Report
            $this->updateDateWisePrintEmbrProductionReport($receive_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $sizeId, $receive_qty, $print_status);
            $this->updateDateFloorWisePrintEmbrReport($data);
        }
    }

    private function updateTotalProductionReportForPrintEmbrReceive($buyerId, $orderId, $garmentsItemId, $purchaseOrderId, $colorId, $receive_qty, $print_status)
    {
        $printReport = TotalProductionReport::where([
            'order_id' => $orderId,
            'garments_item_id' => $garmentsItemId,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId
        ])->first();

        if (!$printReport) {
            $printReport = new TotalProductionReport();

            $printReport->buyer_id = $buyerId;
            $printReport->order_id = $orderId;
            $printReport->garments_item_id = $garmentsItemId;
            $printReport->purchase_order_id = $purchaseOrderId;
            $printReport->color_id = $colorId;
        }
        if ($print_status == 1) {
            $printReport->todays_received += $receive_qty;
            $printReport->total_received += $receive_qty;
        } else {
            $printReport->todays_embroidary_received += $receive_qty;
            $printReport->total_embroidary_received += $receive_qty;
        }

        $printReport->save();

        return true;
    }

    private function updateColorAndDateWiseProductionReportForPrintEmbrReceive($receive_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $receive_qty, $print_status)
    {
        $production_date = $receive_date;
        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'production_date' => $production_date,
        ])->first();

        if (!$dateAndColorWiseProduction) {
            $dateAndColorWiseProduction = new DateAndColorWiseProduction();
            $dateAndColorWiseProduction->buyer_id = $buyerId;
            $dateAndColorWiseProduction->order_id = $orderId;
            $dateAndColorWiseProduction->purchase_order_id = $purchaseOrderId;
            $dateAndColorWiseProduction->color_id = $colorId;
            $dateAndColorWiseProduction->production_date = $production_date;
        }
        if ($print_status == 1) {
            $dateAndColorWiseProduction->print_received_qty += $receive_qty;
        } else {
            $dateAndColorWiseProduction->embroidary_received_qty += $receive_qty;
        }
        $dateAndColorWiseProduction->save();
        return true;
    }

    private function updateDateWisePrintEmbrProductionReport($receive_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $sizeId, $receive_qty, $print_status)
    {
        $production_date = $receive_date;
        $dateAndSizeWiseProduction = DateWisePrintEmbrProductionReport::where([
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'size_id' => $sizeId,
            'production_date' => $production_date,
        ])->first();

        if (!$dateAndSizeWiseProduction) {
            $dateAndSizeWiseProduction = new DateWisePrintEmbrProductionReport();
            $dateAndSizeWiseProduction->buyer_id = $buyerId;
            $dateAndSizeWiseProduction->order_id = $orderId;
            $dateAndSizeWiseProduction->purchase_order_id = $purchaseOrderId;
            $dateAndSizeWiseProduction->color_id = $colorId;
            $dateAndSizeWiseProduction->size_id = $sizeId;
            $dateAndSizeWiseProduction->production_date = $production_date;
        }
        if ($print_status == 1) {
            $dateAndSizeWiseProduction->print_received_qty += $receive_qty;
        } else {
            $dateAndSizeWiseProduction->embroidery_received_qty += $receive_qty;
        }
        $dateAndSizeWiseProduction->save();
        return true;
    }

    private function updateDateFloorWisePrintEmbrReport($data)
    {
        $buyerId = $data['buyerId'];
        $orderId = $data['orderId'];
        $garmentsItemId = $data['garmentsItemId'];
        $cuttingFloorId = $data['cuttingFloorId'];
        $colorId = $data['colorId'];
        $purchaseOrderId = $data['purchaseOrderId'];
        $receive_date = $data['receive_date'];
        $print_status = $data['print_status'];
        $receive_qty = $data['receive_qty'] ?? 0;

        $dateFloorWisePrintEmbrReport = DateFloorWisePrintEmbrReport::where([
            'production_date' => $receive_date,
            'cutting_floor_id' => $cuttingFloorId,
            'garments_item_id' => $garmentsItemId,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
        ])->first();

        if (!$dateFloorWisePrintEmbrReport) {
            $dateFloorWisePrintEmbrReport = new DateFloorWisePrintEmbrReport();
            $dateFloorWisePrintEmbrReport->production_date = $receive_date;
            $dateFloorWisePrintEmbrReport->cutting_floor_id = $cuttingFloorId;
            $dateFloorWisePrintEmbrReport->buyer_id = $buyerId;
            $dateFloorWisePrintEmbrReport->order_id = $orderId;
            $dateFloorWisePrintEmbrReport->garments_item_id = $garmentsItemId;
            $dateFloorWisePrintEmbrReport->purchase_order_id = $purchaseOrderId;
            $dateFloorWisePrintEmbrReport->color_id = $colorId;
        }
        if ($print_status == 1) {
            $dateFloorWisePrintEmbrReport->print_received_qty += $receive_qty;
        } else {
            $dateFloorWisePrintEmbrReport->embroidery_received_qty += $receive_qty;
        }
        $dateFloorWisePrintEmbrReport->save();
        return true;
    }

    private function updateDateWisePrintProductionReportForPrintReceive($bundlecard, $receive_date)
    {
        $print_details_data = [];
        $print_details_data[] = [
            'buyer_id' => $bundlecard->buyer_id,
            'order_id' => $bundlecard->order_id,
            'purchase_order_id' => $bundlecard->purchase_order_id,
            'color_id' => $bundlecard->color_id,
            'print_sent' => 0,
            'print_received' => $bundlecard->quantity - $bundlecard->total_rejection,
            'print_rejection' => 0,
        ];
        $date_wise_print_production_report = DateWisePrintProductionReport::where('print_date', $receive_date)
            ->first();

        if (!$date_wise_print_production_report) {
            $date_wise_print_production_report = new DateWisePrintProductionReport();
            $date_wise_print_production_report->print_date = $receive_date;
            $date_wise_print_production_report->print_details = $print_details_data;
            $date_wise_print_production_report->total_print_sent = 0;
            $date_wise_print_production_report->total_print_received = $bundlecard->quantity - $bundlecard->total_rejection;
            $date_wise_print_production_report->total_print_rejection = 0;
            $date_wise_print_production_report->save();
        } else {
            $print_details_existing_data = $date_wise_print_production_report->print_details;
            $total_print_received = $date_wise_print_production_report->total_print_received;
            $total_print_rejection = $date_wise_print_production_report->total_print_rejection;
            $total_print_received += $bundlecard->quantity - $bundlecard->total_rejection ?? 0;
            foreach ($print_details_existing_data as $key => $print_detail) {
                $is_detail_exist = 0;
                if ($print_detail['purchase_order_id'] == $bundlecard->purchase_order_id && $print_detail['color_id'] == $bundlecard->color_id) {
                    $is_detail_exist = 1;
                    $print_details_existing_data[$key] = [
                        'buyer_id' => $print_detail['buyer_id'],
                        'order_id' => $print_detail['order_id'],
                        'purchase_order_id' => $print_detail['purchase_order_id'],
                        'color_id' => $print_detail['color_id'],
                        'print_sent' => $print_detail['print_sent'] ?? 0,
                        'print_received' => $print_detail['print_received'] + $bundlecard->quantity - $bundlecard->total_rejection,
                        'print_rejection' => $print_detail['print_rejection'] ?? 0,
                    ];
                    break;
                }
            }
            if ($is_detail_exist == 0) {
                $print_details_existing_data = array_merge($print_details_existing_data, $print_details_data);
            }

            $date_wise_print_production_report->total_print_received = $total_print_received ?? 0;
            $date_wise_print_production_report->print_details = $print_details_existing_data;
            $date_wise_print_production_report->save();
        }

        return true;
    }

    /**
     * Handle the cutting inventory "updated" event.
     *
     * @param \SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory $cuttingInventory
     * @return void
     */
    public function updated(CuttingInventory $cuttingInventory)
    {
        //
    }

    /**
     * Handle the cutting inventory "deleted" event.
     *
     * @param \SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory $cuttingInventory
     * @return void
     */
    public function deleted(CuttingInventory $cuttingInventory)
    {
        if (isset($cuttingInventory->cutting_inventory_challan)) {
            $print_status = $cuttingInventory->print_status; // 0 = Solid, 1 = Print, 2 = Embroidery
            $cuttingInventoryChallan = $cuttingInventory->cutting_inventory_challan;

            $challan_type = $cuttingInventoryChallan->type;

            $bundleCard = $cuttingInventory->bundlecard;
            $input_date = $cuttingInventoryChallan->input_date;
            $print_receive_date = $bundleCard->print_received_date;
            $embroidery_receive_date = $bundleCard->embroidary_received_date;
            $buyerId = $bundleCard->buyer_id;
            $orderId = $bundleCard->order_id;
            $garmentsItemId = $bundleCard->garments_item_id;
            $purchaseOrderId = $bundleCard->purchase_order_id;
            $colorId = $bundleCard->color_id;
            $sizeId = $bundleCard->size_id;
            $current_line_id = $cuttingInventoryChallan->line_id;
            $current_floor_id = $current_line_id ? $cuttingInventoryChallan->line->floor->id : null;
            $bundleQty = $bundleCard->quantity - $bundleCard->total_rejection - $bundleCard->print_rejection - $bundleCard->embroidary_rejection;

            $orderWiseRep = TotalProductionReport::where([
                'order_id' => $orderId,
                'garments_item_id' => $garmentsItemId,
                'purchase_order_id' => $purchaseOrderId,
                'color_id' => $colorId
            ])->first();

            if ($orderWiseRep) {
                if ($challan_type == 'challan') {
                    if ($input_date == date('Y-m-d')) {
                        $orderWiseRep->decrement('todays_input', $bundleQty);
                    }
                    $orderWiseRep->decrement('total_input', $bundleQty);
                }

                if ($print_status == 1) {
                    // FOR PRINT RECEIVE
                    $orderWiseRep->decrement('total_received', $bundleQty);
                    $orderWiseRep->decrement('total_print_rejection', $bundleCard->print_rejection ?? 0);
                    if ($print_receive_date == date('Y-m-d')) {
                        // FOR TODAY RECEIVE
                        $orderWiseRep->decrement('todays_received', $bundleQty);
                        $orderWiseRep->decrement('todays_print_rejection', $bundleCard->print_rejection ?? 0);
                    }
                    DB::table('bundle_cards')
                        ->where('id', $cuttingInventory->bundle_card_id)
                        ->update([
                            'print_received_date' => null
                        ]);
                    $this->updateDateWisePrintProductionReportForPrintReceiveDelete($bundleCard, $bundleQty, $bundleCard->print_rejection ?? 0);
                } elseif ($print_status == 2) {
                    // FOR EMBROIDERY RECEIVE
                    $orderWiseRep->decrement('total_embroidary_received', $bundleQty);
                    $orderWiseRep->decrement('total_embroidary_rejection', $bundleCard->embroidary_rejection ?? 0);
                    if ($embroidery_receive_date == date('Y-m-d')) {
                        // FOR TODAY RECEIVE
                        $orderWiseRep->decrement('todays_embroidary_received', $bundleQty);
                        $orderWiseRep->decrement('todays_embroidary_rejection', $bundleCard->embroidary_rejection ?? 0);
                    }
                    DB::table('bundle_cards')
                        ->where('id', $cuttingInventory->bundle_card_id)
                        ->update([
                            'embroidary_received_date' => null
                        ]);
                }
            }

            $this->updateColorAndDateWiseForChallanDelete($bundleCard, $input_date, $purchaseOrderId, $colorId, $bundleQty, $print_status, $challan_type);
            $this->updateDateWisePrintEmbrProductionReportForChallanDelete($bundleCard, $input_date, $purchaseOrderId, $colorId, $sizeId, $bundleQty, $print_status);
            if ($challan_type == 'challan') {
                $this->updateSewingProductionReportForChallanDelete($input_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $current_line_id, $current_floor_id, $bundleQty, $print_status);
                $this->updateFinishingProductionReportForChallanDelete($input_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $current_line_id, $current_floor_id, $bundleQty, $print_status);
                $this->updateLineSizeWiseSewingReportForChallanDelete($input_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $sizeId, $current_line_id, $current_floor_id, $bundleQty, $print_status);
                (new ColorSizeSummaryReportService())->make($bundleCard)->inputChallanDelete($bundleQty)->saveOrUpdate();

                // For challan wise input report table
                $dateChallanInputDto = (new DailyChallanWiseInputDTO())->setFloorId($current_floor_id)
                    ->setCuttingInventory($cuttingInventory)
                    ->setLineId($current_line_id)
                    ->setProductionDate($input_date);

                DailyChallanWiseInputAction::make(
                    $dateChallanInputDto->setDailyChallanInputModel(new DailyChallanWiseInput())
                )->decreaseBundleQty($bundleQty)->storeAndUpdate();

                DailyChallanWiseInputAction::make(
                    $dateChallanInputDto->setDailyChallanInputModel(new DailyChallanSizeWiseInput())
                )->decreaseBundleQty($bundleQty)->storeAndUpdate();
            }
        }
        DailyChallanWiseInput::query()->where('sewing_input', '<', 1)->delete();
        DailyChallanSizeWiseInput::query()->where('sewing_input', '<', 1)->delete();
    }

    private function updateFinishingProductionReportForChallanDelete($input_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $current_line_id, $current_floor_id, $bundleQty, $print_status)
    {
        $date = $input_date;

        $oldFinishingProductionReport = FinishingProductionReport::where([
            'floor_id' => $current_floor_id,
            'line_id' => $current_line_id,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'production_date' => $date,
        ])->first();

        if ($oldFinishingProductionReport) {
            if ($oldFinishingProductionReport->sewing_input >= $bundleQty) {
                $oldFinishingProductionReport->sewing_input -= $bundleQty;
            } else {
                $oldFinishingProductionReport->sewing_input = 0;
            }
            $oldFinishingProductionReport->save();
        }
    }

    private function updateLineSizeWiseSewingReportForChallanDelete($input_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $sizeId, $current_line_id, $current_floor_id, $bundleQty, $print_status)
    {
        $date = $input_date;

        $oldReport = LineSizeWiseSewingReport::where([
            'floor_id' => $current_floor_id,
            'line_id' => $current_line_id,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'size_id' => $sizeId,
            'production_date' => $date,
        ])->first();

        if ($oldReport) {
            if ($oldReport->sewing_input >= $bundleQty) {
                $oldReport->sewing_input -= $bundleQty;
            } else {
                $oldReport->sewing_input = 0;
            }
            $oldReport->save();
        }
    }

    private function updateColorAndDateWiseForChallanDelete($bundleCard, $input_date, $purchaseOrderId, $colorId, $bundleQty, $print_status, $challan_type)
    {
        $dateAndColorWiseInfo = DateAndColorWiseProduction::where([
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'production_date' => $input_date
        ])->first();

        if ($dateAndColorWiseInfo) {
            if ($challan_type == 'challan') {
                $dateAndColorWiseInfo->decrement('input_qty', $bundleQty);
            }
            if ($print_status == 1) {
                // FOR PRINT RECEIVE
                $dateAndColorWiseInfo->decrement('print_received_qty', $bundleQty);
                $dateAndColorWiseInfo->decrement('print_rejection_qty', $bundleCard->print_rejection ?? 0);
            } elseif ($print_status == 2) {
                // FOR EMBROIDERY RECEIVE
                $dateAndColorWiseInfo->decrement('embroidary_received_qty', $bundleQty);
                $dateAndColorWiseInfo->decrement('embroidary_rejection_qty', $bundleCard->embroidary_rejection ?? 0);
            }
        }
    }

    private function updateDateWisePrintEmbrProductionReportForChallanDelete($bundleCard, $input_date, $purchaseOrderId, $colorId, $sizeId, $bundleQty, $print_status)
    {
        $dateAndSizeWiseProduction = DateWisePrintEmbrProductionReport::where([
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'size_id' => $sizeId,
            'production_date' => $input_date
        ])->first();

        if ($dateAndSizeWiseProduction) {
            if ($print_status == 1) {
                // FOR PRINT RECEIVE
                $dateAndSizeWiseProduction->decrement('print_received_qty', $bundleQty);
                $dateAndSizeWiseProduction->decrement('print_rejection_qty', $bundleCard->print_rejection ?? 0);
            } elseif ($print_status == 2) {
                // FOR EMBROIDERY RECEIVE
                $dateAndSizeWiseProduction->decrement('embroidery_received_qty', $bundleQty);
                $dateAndSizeWiseProduction->decrement('embroidery_rejection_qty', $bundleCard->embroidary_rejection ?? 0);
            }
        }
        return true;
    }

    private function updateSewingProductionReportForChallanDelete($input_date, $buyerId, $orderId, $purchaseOrderId, $colorId, $current_line_id, $current_floor_id, $bundleQty, $print_status)
    {
        $sewing_date = $input_date;
        $floor_id = $current_floor_id;
        $line_id = $current_line_id;
        $deleted_input_qty = $bundleQty;

        $date_wise_sewing_production_report = DateWiseSewingProductionReport::where([
            'floor_id' => $floor_id,
            'line_id' => $line_id,
            'sewing_date' => $sewing_date
        ])->first();

        if ($date_wise_sewing_production_report) {
            $sewing_details_existing_data = $date_wise_sewing_production_report->sewing_details;
            $total_sewing_input = $date_wise_sewing_production_report->total_sewing_input;
            $total_sewing_output = $date_wise_sewing_production_report->total_sewing_output;
            $total_sewing_rejection = $date_wise_sewing_production_report->total_sewing_rejection;
            $total_sewing_input -= $deleted_input_qty;
            foreach ($sewing_details_existing_data as $key => $sewing_detail) {
                $is_detail_exist = 0;
                if ($sewing_detail['purchase_order_id'] == $purchaseOrderId && $sewing_detail['color_id'] == $colorId) {
                    $is_detail_exist = 1;
                    $sewing_details_existing_data[$key] = [
                        'buyer_id' => $sewing_detail['buyer_id'],
                        'order_id' => $sewing_detail['order_id'],
                        'purchase_order_id' => $sewing_detail['purchase_order_id'],
                        'color_id' => $sewing_detail['color_id'],
                        'sewing_input' => $sewing_detail['sewing_input'] - $deleted_input_qty ?? 0,
                        'sewing_output' => $sewing_detail['sewing_output'] ?? 0,
                        'sewing_rejection' => $sewing_detail['sewing_rejection'] ?? 0,
                    ];
                    break;
                }
            }
            if ($is_detail_exist == 1) {
                $date_wise_sewing_production_report->total_sewing_input = $total_sewing_input ?? 0;
                $date_wise_sewing_production_report->total_sewing_output = $total_sewing_output ?? 0;
                $date_wise_sewing_production_report->total_sewing_rejection = $total_sewing_rejection ?? 0;
                $date_wise_sewing_production_report->sewing_details = $sewing_details_existing_data;
                $date_wise_sewing_production_report->save();
            }
        }

        return true;
    }

    private function updateDateWisePrintProductionReportForPrintReceiveDelete($bundlecard, $bundleQty, $print_rejection)
    {
        $print_receive_date = $bundlecard->print_received_date;
        $date_wise_print_production_report = DateWisePrintProductionReport::where('print_date', $print_receive_date)
            ->first();

        if ($date_wise_print_production_report) {

            $print_details_existing_data = $date_wise_print_production_report->print_details;
            $total_print_received = $date_wise_print_production_report->total_print_received;
            $total_print_rejection = $date_wise_print_production_report->total_print_rejection;
            $total_print_received -= $bundleQty;
            $total_print_rejection -= $print_rejection;

            foreach ($print_details_existing_data as $key => $print_detail) {
                $is_detail_exist = 0;
                if ($print_detail['purchase_order_id'] == $bundlecard->purchase_order_id && $print_detail['color_id'] == $bundlecard->color_id) {
                    $is_detail_exist = 1;
                    $print_details_existing_data[$key] = [
                        'buyer_id' => $print_detail['buyer_id'],
                        'order_id' => $print_detail['order_id'],
                        'purchase_order_id' => $print_detail['purchase_order_id'],
                        'color_id' => $print_detail['color_id'],
                        'print_sent' => $print_detail['print_sent'] ?? 0,
                        'print_received' => $print_detail['print_received'] - $bundleQty,
                        'print_rejection' => $print_detail['print_rejection'] - $print_rejection,
                    ];
                    break;
                }
            }
            if ($is_detail_exist == 1) {
                $date_wise_print_production_report->total_print_received = $total_print_received ?? 0;
                $date_wise_print_production_report->total_print_rejection = $total_print_rejection ?? 0;
                $date_wise_print_production_report->print_details = $print_details_existing_data;
                $date_wise_print_production_report->save();
            }
        }

        return true;
    }

    /**
     * Handle the cutting inventory "restored" event.
     *
     * @param \SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory $cuttingInventory
     * @return void
     */
    public function restored(CuttingInventory $cuttingInventory)
    {
        //
    }

    /**
     * Handle the cutting inventory "force deleted" event.
     *
     * @param \SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory $cuttingInventory
     * @return void
     */
    public function forceDeleted(CuttingInventory $cuttingInventory)
    {
        //
    }
}
