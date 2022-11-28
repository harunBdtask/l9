<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Handlers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\Inputdroplets\Services\PrintRcvInputCacheKeyService;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateFloorWisePrintEmbrReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintEmbrProductionReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintProductionReport;

class CuttingRejectionBeforePrintSendRejectionHandler
{
    protected $request, $operation_date;

    public function __construct($request)
    {
        $this->operation_date = operationDate();
        $this->request = $request;
    }

    public function handle()
    {
        try {
            $cuttingInventory = CuttingInventory::with('bundlecard')->findOrFail($this->request->id);
            $bundlecard = $cuttingInventory->bundlecard;

            if ($bundlecard && $bundlecard->quantity > $this->request->print_rejection + $bundlecard->total_rejection) {
                DB::beginTransaction();
                if ($cuttingInventory->print_status == 1) {
                    // For Print
                    $bundle_card_rejection_column = 'print_rejection';
                    $this->updateDateWisePrintProductionReport($bundlecard, $this->request);
                } else {
                    // For Embroidery
                    $bundle_card_rejection_column = 'embroidary_rejection';
                }
                DB::table('bundle_cards')
                    ->where('id', $cuttingInventory->bundle_card_id)
                    ->update([
                        $bundle_card_rejection_column => $this->request->print_rejection
                    ]);
                $this->updateTotalProductionReport($bundlecard, $this->request, $cuttingInventory->print_status);
                $this->updateDateAndColorWiseProductionsForPrintRejection($bundlecard, $this->request, $cuttingInventory->print_status);
                $this->updateDateWisePrintEmbrProductionsForPrintRejection($bundlecard, $this->request, $cuttingInventory->print_status);
                $this->updateDateFloorWisePrintEmbrReportForPrintRejection($bundlecard, $this->request, $cuttingInventory->print_status);
                Session::flash('success', S_UPDATE_MSG);
                DB::commit();
                $this->updatePrintRcvCacheData($cuttingInventory->bundle_card_id, $bundle_card_rejection_column, $this->request->print_rejection);
                if (request('type') == 'tag') {
                    $redirect = 'add-bundle-to-tag?tag-no=' . $cuttingInventory->challan_no;
                } else {
                    $redirect = 'bundle-received-from-print';
                }
            } else {
                Session::flash('error', 'Sorry!! Rejection must be less than bundlecard quantity');
            }
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('success', $e->getMessage());
        }

        return $redirect ?? null;
    }

    private function updatePrintRcvCacheData($bundle_card_id, $bundle_card_rejection_column, $print_rejection)
    {
        (new PrintRcvInputCacheKeyService)->setItemStatus(1)->updateCacheData($bundle_card_id, $bundle_card_rejection_column, $print_rejection);
    }

    private function updateTotalProductionReport($bundlecard, $request, $print_status)
    {
        $printReport = TotalProductionReport::where([
            'order_id' => $bundlecard->order_id,
            'garments_item_id' => $bundlecard->garments_item_id,
            'purchase_order_id' => $bundlecard->purchase_order_id,
            'color_id' => $bundlecard->color_id
        ])->first();

        if (!$printReport) {
            $printReport = new TotalProductionReport();
            $printReport->buyer_id = $bundlecard->buyer_id;
            $printReport->order_id = $bundlecard->order_id;
            $printReport->garments_item_id = $bundlecard->garments_item_id;
            $printReport->purchase_order_id = $bundlecard->purchase_order_id;
            $printReport->color_id = $bundlecard->color_id;
        }

        if ($print_status == 1) {
            $printReport->todays_received -= $request->print_rejection;
            $printReport->total_received -= $request->print_rejection;
            $printReport->todays_print_rejection += $request->print_rejection;
            $printReport->total_print_rejection += $request->print_rejection;
        } else {
            $printReport->todays_embroidary_received -= $request->print_rejection;
            $printReport->total_embroidary_received -= $request->print_rejection;
            $printReport->todays_embroidary_rejection += $request->print_rejection;
            $printReport->total_embroidary_rejection += $request->print_rejection;
        }
        $printReport->save();
    }

    public function updateDateWisePrintProductionReport($bundlecard, $request)
    {
        $print_details_data = [];
        $print_details_data[] = [
            'buyer_id' => $bundlecard->buyer_id,
            'order_id' => $bundlecard->order_id,
            'purchase_order_id' => $bundlecard->purchase_order_id,
            'color_id' => $bundlecard->color_id,
            'print_sent' => 0,
            'print_received' => $bundlecard->quantity - $bundlecard->total_rejection - $request->print_rejection,
            'print_rejection' => $request->print_rejection ?? 0,
        ];

        $date_wise_print_production_report = DateWisePrintProductionReport::where('print_date', $this->operation_date)->first();

        if ($date_wise_print_production_report) {
            $print_details_existing_data = $date_wise_print_production_report->print_details;
            $total_print_sent = $date_wise_print_production_report->total_print_sent;
            $total_print_received = $date_wise_print_production_report->total_print_received
                - $request->print_rejection;
            $total_print_rejection = $date_wise_print_production_report->total_print_rejection
                + $request->print_rejection;

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
                        'print_received' => $print_detail['print_received'] - $request->print_rejection,
                        'print_rejection' => $print_detail['print_rejection'] + $request->print_rejection,
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

    private function updateDateAndColorWiseProductionsForPrintRejection($bundlecard, $request, $print_status)
    {
        $purchaseOrderId = $bundlecard->purchase_order_id;
        $colorId = $bundlecard->color_id;

        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $this->operation_date,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
        ])->first();

        if (!$dateAndColorWiseProduction) {
            $dateAndColorWiseProduction = new DateAndColorWiseProduction();

            $dateAndColorWiseProduction->buyer_id = $bundlecard->buyer_id;
            $dateAndColorWiseProduction->order_id = $bundlecard->order_id;
            $dateAndColorWiseProduction->purchase_order_id = $purchaseOrderId;
            $dateAndColorWiseProduction->color_id = $colorId;
            $dateAndColorWiseProduction->production_date = $this->operation_date;
        }
        if ($print_status == 1) {
            $dateAndColorWiseProduction->print_received_qty -= $request->print_rejection;
            $dateAndColorWiseProduction->print_rejection_qty += $request->print_rejection;
        } else {
            $dateAndColorWiseProduction->embroidary_received_qty -= $request->print_rejection;
            $dateAndColorWiseProduction->embroidary_rejection_qty += $request->print_rejection;
        }
        $dateAndColorWiseProduction->save();

        return true;
    }

    private function updateDateWisePrintEmbrProductionsForPrintRejection($bundlecard, $request, $print_status)
    {
        $purchaseOrderId = $bundlecard->purchase_order_id;
        $colorId = $bundlecard->color_id;

        $dateAndSizeWiseProduction = DateWisePrintEmbrProductionReport::where([
            'production_date' => $this->operation_date,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'size_id' => $bundlecard->size_id,
        ])->first();

        if ($dateAndSizeWiseProduction) {
            if ($print_status == 1) {
                $dateAndSizeWiseProduction->print_received_qty -= $request->print_rejection;
                $dateAndSizeWiseProduction->print_rejection_qty += $request->print_rejection;
            } else {
                $dateAndSizeWiseProduction->embroidery_received_qty -= $request->print_rejection;
                $dateAndSizeWiseProduction->embroidery_rejection_qty += $request->print_rejection;
            }
            $dateAndSizeWiseProduction->save();
        }

        return true;
    }

    private function updateDateFloorWisePrintEmbrReportForPrintRejection($bundlecard, $request, $print_status)
    {
        $purchaseOrderId = $bundlecard->purchase_order_id;
        $colorId = $bundlecard->color_id;

        $dateFloorWisePrintEmbrReport = DateFloorWisePrintEmbrReport::where([
            'production_date' => $this->operation_date,
            'cutting_floor_id' => $bundlecard->cutting_floor_id,
            'garments_item_id' => $bundlecard->garments_item_id,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
        ])->first();

        if ($dateFloorWisePrintEmbrReport) {
            if ($print_status == 1) {
                $dateFloorWisePrintEmbrReport->print_received_qty -= $request->print_rejection;
                $dateFloorWisePrintEmbrReport->print_rejection_qty += $request->print_rejection;
            } else {
                $dateFloorWisePrintEmbrReport->embroidery_received_qty -= $request->print_rejection;
                $dateFloorWisePrintEmbrReport->embroidery_rejection_qty += $request->print_rejection;
            }
            $dateFloorWisePrintEmbrReport->save();
        }

        return true;
    }
}
