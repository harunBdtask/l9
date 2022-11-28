<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Observers;

use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateFloorWisePrintEmbrReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintEmbrProductionReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintProductionReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use Carbon\Carbon;
use DB;

class PrintInventoryChallanObserver
{
    /**
     * Handle the print inventory challan "created" event.
     *
     * @param  \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan $printInventoryChallan
     * @return void
     */
    public function created(PrintInventoryChallan $printInventoryChallan)
    {
        $challanSent = PrintInventoryChallan::where([
            'operation_name' => $printInventoryChallan->operation_name,
            'challan_no' => $printInventoryChallan->challan_no
        ])->count();

        if ($challanSent == 1
            && $printInventoryChallan->status == 1) {
            $totalSendQty = 0;
            foreach ($printInventoryChallan->print_inventory as $printInventory) {

                $bundleCard = $printInventory->bundle_card;
                $cuttingFloorId = $bundleCard->cutting_floor_id;
                $buyerId = $bundleCard->buyer_id;
                $orderId = $bundleCard->order_id;
                $garmentsItemId = $bundleCard->garments_item_id;
                $purchaseOrderId = $bundleCard->purchase_order_id;
                $colorId = $bundleCard->color_id;
                $sizeId = $bundleCard->size_id;
                $bundleQty = $bundleCard->quantity - $bundleCard->total_rejection;
                $totalSendQty += $bundleQty;

                $printSent = 0;
                $embroidarySent = 0;

                if ($printInventoryChallan->operation_name == PRNT) {
                    $printSent = $bundleQty;
                } elseif ($printInventoryChallan->operation_name == EMBROIDARY) {
                    $embroidarySent = $bundleQty;
                }

                if ($embroidarySent > 0 || $printSent > 0) {
                    $sendDate = $printInventoryChallan->updated_at->toDateString();
                    $data = [
                        'bundleCard' => $bundleCard,
                        'sendDate' => $sendDate,
                        'cuttingFloorId' => $cuttingFloorId,
                        'buyerId' => $buyerId,
                        'orderId' => $orderId,
                        'garmentsItemId' => $garmentsItemId,
                        'purchaseOrderId' => $purchaseOrderId,
                        'colorId' => $colorId,
                        'sizeId' => $sizeId,
                        'printSent' => $printSent,
                        'embroidarySent' => $embroidarySent,
                    ];
                    // Update total production report
                    $this->updateTotalProductionReport($buyerId, $orderId, $garmentsItemId, $purchaseOrderId, $colorId, $printSent, $embroidarySent);
                    // For updating print production report table
                    $this->updateDateWisePrintProductionReportForPrintEmbrSend($buyerId, $orderId, $purchaseOrderId, $colorId, $printSent, $embroidarySent, $sendDate);
                    // For Updating Date & color Wise Print Embroidery Send
                    $this->updateDateAndColorWiseProductionReport($buyerId, $orderId, $purchaseOrderId, $colorId, $printSent, $embroidarySent, $sendDate);
                    // For Updating Date & Size Wise Print Embroidery Send
                    $this->updateDateWisePrintEmbrProductionReport($buyerId, $orderId, $purchaseOrderId, $colorId, $sizeId, $printSent, $embroidarySent, $sendDate);
                    $this->updateDateFloorWisePrintEmbrReport($data);
                }
            }
            DB::table('print_inventory_challans')->where('id', $printInventoryChallan->id)
                ->update(['send_total_qty' => $totalSendQty]);
        }
    }

    private function updateTotalProductionReport($buyerId, $orderId, $garmentsItemId, $purchaseOrderId, $colorId, $printSent, $embroidarySent)
    {
        $totalProductionReport = TotalProductionReport::where([
            'order_id' => $orderId,
            'garments_item_id' => $garmentsItemId,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId
        ])->first();

        if (!$totalProductionReport) {
            $totalProductionReport = new TotalProductionReport();
            $totalProductionReport->buyer_id = $buyerId;
            $totalProductionReport->order_id = $orderId;
            $totalProductionReport->garments_item_id = $garmentsItemId;
            $totalProductionReport->purchase_order_id = $purchaseOrderId;
            $totalProductionReport->color_id = $colorId;
        }
        $totalProductionReport->todays_sent += $printSent;
        $totalProductionReport->total_sent += $printSent;
        $totalProductionReport->todays_embroidary_sent += $embroidarySent;
        $totalProductionReport->total_embroidary_sent += $embroidarySent;
        $totalProductionReport->save();

        return true;
    }

    private function updateDateAndColorWiseProductionReport($buyerId, $orderId, $purchaseOrderId, $colorId, $printSent, $embroidarySent, $sendDate)
    {
        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $sendDate,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
        ])->first();

        if (!$dateAndColorWiseProduction) {
            $dateAndColorWiseProduction = new DateAndColorWiseProduction();
            $dateAndColorWiseProduction->buyer_id = $buyerId;
            $dateAndColorWiseProduction->order_id = $orderId;
            $dateAndColorWiseProduction->purchase_order_id = $purchaseOrderId;
            $dateAndColorWiseProduction->color_id = $colorId;
            $dateAndColorWiseProduction->production_date = $sendDate;
        }

        $dateAndColorWiseProduction->print_sent_qty += $printSent;
        $dateAndColorWiseProduction->embroidary_sent_qty += $embroidarySent;
        $dateAndColorWiseProduction->save();

        return true;
    }

    private function updateDateWisePrintEmbrProductionReport($buyerId, $orderId, $purchaseOrderId, $colorId, $sizeId, $printSent, $embroidarySent, $sendDate)
    {
        $dateAndSizeWiseProduction = DateWisePrintEmbrProductionReport::where([
            'production_date' => $sendDate,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'size_id' => $sizeId,
        ])->first();

        if (!$dateAndSizeWiseProduction) {
            $dateAndSizeWiseProduction = new DateWisePrintEmbrProductionReport();
            $dateAndSizeWiseProduction->production_date = $sendDate;
            $dateAndSizeWiseProduction->buyer_id = $buyerId;
            $dateAndSizeWiseProduction->order_id = $orderId;
            $dateAndSizeWiseProduction->purchase_order_id = $purchaseOrderId;
            $dateAndSizeWiseProduction->color_id = $colorId;
            $dateAndSizeWiseProduction->size_id = $sizeId;
        }

        $dateAndSizeWiseProduction->print_sent_qty += $printSent;
        $dateAndSizeWiseProduction->embroidery_sent_qty += $embroidarySent;
        $dateAndSizeWiseProduction->save();

        return true;
    }
    
    private function updateDateFloorWisePrintEmbrReport($data)
    {
        $sendDate = $data['sendDate'];
        $buyerId = $data['buyerId'];
        $orderId = $data['orderId'];
        $purchaseOrderId = $data['purchaseOrderId'];
        $colorId = $data['colorId'];
        $cuttingFloorId = $data['cuttingFloorId'];
        $garmentsItemId = $data['garmentsItemId'];
        $printSent = $data['printSent'] ?? 0;
        $embroidarySent = $data['embroidarySent'] ?? 0;
        
        $dateFloorWisePrintEmbrReport = DateFloorWisePrintEmbrReport::where([
            'production_date' => $sendDate,
            'cutting_floor_id' => $cuttingFloorId,
            'garments_item_id' => $garmentsItemId,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
        ])->first();

        if (!$dateFloorWisePrintEmbrReport) {
            $dateFloorWisePrintEmbrReport = new DateFloorWisePrintEmbrReport();
            $dateFloorWisePrintEmbrReport->production_date = $sendDate;
            $dateFloorWisePrintEmbrReport->cutting_floor_id = $cuttingFloorId;
            $dateFloorWisePrintEmbrReport->buyer_id = $buyerId;
            $dateFloorWisePrintEmbrReport->order_id = $orderId;
            $dateFloorWisePrintEmbrReport->garments_item_id = $garmentsItemId;
            $dateFloorWisePrintEmbrReport->purchase_order_id = $purchaseOrderId;
            $dateFloorWisePrintEmbrReport->color_id = $colorId;
        }

        $dateFloorWisePrintEmbrReport->print_sent_qty += $printSent;
        $dateFloorWisePrintEmbrReport->embroidery_sent_qty += $embroidarySent;
        $dateFloorWisePrintEmbrReport->save();

        return true;
    }

    private function updateDateWisePrintProductionReportForPrintEmbrSend($buyerId, $orderId, $purchaseOrderId, $colorId, $printSent, $embroidarySent, $sendDate)
    {
        $print_sent_date = $sendDate;

        $print_details_data = [];
        $print_details_data[] = [
            'buyer_id' => $buyerId,
            'order_id' => $orderId,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'print_sent' => $printSent ?? 0,
            'print_received' => 0,
            'print_rejection' => 0,
        ];

        $date_wise_print_production_report = DateWisePrintProductionReport::where('print_date', $print_sent_date)->first();
        if (!$date_wise_print_production_report) {
            $date_wise_print_production_report = new DateWisePrintProductionReport();
            $date_wise_print_production_report->print_date = $print_sent_date;
            $date_wise_print_production_report->print_details = $print_details_data;
            $date_wise_print_production_report->total_print_sent = $printSent ?? 0;
            $date_wise_print_production_report->total_print_received = 0;
            $date_wise_print_production_report->total_print_rejection = 0;
            $date_wise_print_production_report->save();
        } else {
            $print_details_existing_data = $date_wise_print_production_report->print_details;
            $total_print_sent = $date_wise_print_production_report->total_print_sent;
            $total_print_received = $date_wise_print_production_report->total_print_received;
            $total_print_rejection = $date_wise_print_production_report->total_print_rejection;
            $total_print_sent += $printSent;
            foreach ($print_details_existing_data as $key => $print_detail) {
                $is_detail_exist = 0;
                if ($print_detail['purchase_order_id'] == $purchaseOrderId && $print_detail['color_id'] == $colorId) {
                    $is_detail_exist = 1;
                    $print_details_existing_data[$key] = [
                        'buyer_id' => $print_detail['buyer_id'],
                        'order_id' => $print_detail['order_id'],
                        'purchase_order_id' => $print_detail['purchase_order_id'],
                        'color_id' => $print_detail['color_id'],
                        'print_sent' => $print_detail['print_sent'] + $printSent ?? 0,
                        'print_received' => $print_detail['print_received'] ?? 0,
                        'print_rejection' => $print_detail['print_rejection'] ?? 0,
                    ];
                    break;
                }
            }
            if ($is_detail_exist == 0) {
                $print_details_existing_data = array_merge($print_details_existing_data, $print_details_data);
            }
            $date_wise_print_production_report->total_print_sent = $total_print_sent ?? 0;
            $date_wise_print_production_report->total_print_received = $total_print_received ?? 0;
            $date_wise_print_production_report->total_print_rejection = $total_print_rejection ?? 0;
            $date_wise_print_production_report->print_details = $print_details_existing_data;
            $date_wise_print_production_report->save();
        }
        return true;
    }

    public function updateDateWisePrintProductionReport($printInventoryChallan, $qty)
    {
        $print_sent_date = $printInventoryChallan->updated_at->toDateString();

        $print_details_data = [];
        $print_details_data[] = [
            'buyer_id' => $printInventoryChallan->buyer_id,
            'order_id' => $printInventoryChallan->order_id,
            'purchase_order_id' => $printInventoryChallan->purchase_order_id,
            'color_id' => $printInventoryChallan->color_id,
            'print_sent' => $qty ?? 0,
            'print_received' => 0,
            'print_rejection' => 0,
        ];

        $date_wise_print_production_report = DateWisePrintProductionReport::where('print_date', $print_sent_date)->first();
        if (!$date_wise_print_production_report) {
            $date_wise_print_production_report = new DateWisePrintProductionReport();
            $date_wise_print_production_report->print_date = $print_sent_date;
            $date_wise_print_production_report->print_details = $print_details_data;
            $date_wise_print_production_report->total_print_sent = $qty ?? 0;
            $date_wise_print_production_report->total_print_received = 0;
            $date_wise_print_production_report->total_print_rejection = 0;
            $date_wise_print_production_report->save();
        } else {
            $print_details_existing_data = $date_wise_print_production_report->print_details;
            $total_print_sent = $date_wise_print_production_report->total_print_sent;
            $total_print_received = $date_wise_print_production_report->total_print_received;
            $total_print_rejection = $date_wise_print_production_report->total_print_rejection;
            $total_print_sent += $qty;
            foreach ($print_details_existing_data as $key => $print_detail) {
                $is_detail_exist = 0;
                if ($print_detail['purchase_order_id'] == $printInventoryChallan->purchase_order_id && $print_detail['color_id'] == $printInventoryChallan->color_id) {
                    $is_detail_exist = 1;
                    $print_details_existing_data[$key] = [
                        'buyer_id' => $print_detail['buyer_id'],
                        'order_id' => $print_detail['order_id'],
                        'purchase_order_id' => $print_detail['purchase_order_id'],
                        'color_id' => $print_detail['color_id'],
                        'print_sent' => $print_detail['print_sent'] + $qty ?? 0,
                        'print_received' => $print_detail['print_received'] ?? 0,
                        'print_rejection' => $print_detail['print_rejection'] ?? 0,
                    ];
                    break;
                }
            }
            if ($is_detail_exist == 0) {
                $print_details_existing_data = array_merge($print_details_existing_data, $print_details_data);
            }
            $date_wise_print_production_report->total_print_sent = $total_print_sent ?? 0;
            $date_wise_print_production_report->total_print_received = $total_print_received ?? 0;
            $date_wise_print_production_report->total_print_rejection = $total_print_rejection ?? 0;
            $date_wise_print_production_report->print_details = $print_details_existing_data;
            $date_wise_print_production_report->save();
        }
        return 1;
    }

    /**
     * Handle the print inventory challan "updated" event.
     *
     * @param  \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan $printInventoryChallan
     * @return void
     */
    public function updated(PrintInventoryChallan $printInventoryChallan)
    {
        //
    }

    /**
     * Handle the print inventory challan "deleted" event.
     *
     * @param  \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan $printInventoryChallan
     * @return void
     */
    public function deleted(PrintInventoryChallan $printInventoryChallan)
    {
        //
    }

    /**
     * Handle the print inventory challan "restored" event.
     *
     * @param  \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan $printInventoryChallan
     * @return void
     */
    public function restored(PrintInventoryChallan $printInventoryChallan)
    {
        //
    }

    /**
     * Handle the print inventory challan "force deleted" event.
     *
     * @param  \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan $printInventoryChallan
     * @return void
     */
    public function forceDeleted(PrintInventoryChallan $printInventoryChallan)
    {
        //
    }
}
