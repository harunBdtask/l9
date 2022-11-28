<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Actions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;

class InputTagScanAction
{
    protected $request;

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest(Request $request): InputTagScanAction
    {
        $this->request = $request;
        return $this;
    }

    public function handle()
    {
        $requestData = $this->getRequest();
        $bundleCardId = ltrim(substr($requestData->bundle_card_id, 1, 9), 0);
        $challanNo = $requestData->challan_no;
        try {
            DB::beginTransaction();
            $bundleCard = BundleCard::with([
                'details:id,is_manual',
                'buyer:id,name',
                'order:id,style_name',
                'purchaseOrder:id,po_no',
                'color:id,name',
                'size:id,name',
                'lot:id,lot_no'
            ])
                ->where(['id' => $bundleCardId, 'status' => ACTIVE])
                ->first();
            $checkBundleIsScannable = $this->isScannable($bundleCard, $challanNo);
            $status = $checkBundleIsScannable['status'];
            $message = $checkBundleIsScannable['message'];
            $printStatus = $checkBundleIsScannable['print_status'];

            if ($status == 0 && !is_null($printStatus)) {
                $input = [
                    'challan_no' => $challanNo,
                    'bundle_card_id' => $bundleCardId,
                    'status' => 1,
                    'print_status' => $printStatus
                ];
                $cuttingInv = CuttingInventory::create($input);
                if ($printStatus != 0) {
                    if ($printStatus == PRNT) {
                        $rcv_date_column = 'print_received_date';
                    } elseif ($printStatus == EMBROIDARY) {
                        $rcv_date_column = 'embroidary_received_date';
                    }
                    DB::table('bundle_cards')
                        ->where('id', $bundleCardId)
                        ->update([$rcv_date_column => operationDate()]);
                }
                DB::commit();
                if ($cuttingInv) {
                    $status = 0;
                }
                if (substr($requestData->bundle_card_id, 0, 1) == 1) {
                    $rejection_status = 1; // For rejection part scan
                    $challan_type = $printStatus;
                }
                $bundle_info = [
                    'id' => $bundleCardId,
                    'quantity' => $bundleCard->quantity ?? 0,
                    'total_rejection' => $bundleCard->total_rejection ?? 0,
                    'print_rejection' => $bundleCard->print_rejection ?? 0,
                    'embroidary_rejection' => $bundleCard->embroidary_rejection ?? 0,
                    'buyer_name' => $bundleCard->buyer->name ?? '',
                    'style_name' => $bundleCard->order->style_name ?? '',
                    'po_no' => $bundleCard->purchaseOrder->po_no ?? '',
                    'color_name' => $bundleCard->color->name ?? '',
                    'lot_no' => $bundleCard->lot->lot_no ?? '',
                    'cutting_no' => $bundleCard->cutting_no ?? '',
                    'size_name' => $bundleCard->size->name ?? '',
                    'bundle_no' => $bundleCard->details->is_manual == 1 ? $bundleCard->size_wise_bundle_no : $bundleCard->{getbundleCardSerial()} ?? $bundleCard->bundle_no ?? '',
                ];
            }
        } catch (Exception $e) {
            DB::rollback();
            $message = $e->getMessage();
        }

        return [
            'status' => $status ?? 1,
            'message' => $message ?? '',
            'rejection_status' => $rejection_status ?? 0,
            'bundle_info' => $bundle_info ?? null,
            'challan_type' => $challan_type ?? ''
        ];
    }

    private function isScannable($bundleCard, $challanNo)
    {
        $status = 0;
        if (!$bundleCard) {
            $status = 1;
            $message = "Invalid barcode";
        } elseif ($bundleCard->input_scan_time) {
            $status = 1;
            $message = 'Already scanned';
        } else {
            $cuttingInventory = CuttingInventory::with([
                'bundlecard:id,color_id',
                'cutting_inventory_challan:id,challan_no,type'
            ])
                ->where('challan_no', $challanNo)
                ->first();
            if (!$cuttingInventory) {
                $status = 1;
                $message = 'Sorry!! This is empty challan';
            } elseif ($cuttingInventory->cutting_inventory_challan->type == 'challan') {
                $status = 1;
                $message = 'You have already created this challan so please reload this page';
            } elseif ($cuttingInventory->bundlecard->color_id != $bundleCard->color_id) {
                $status = 1;
                $message = 'Please scan same color bundle';
            } else {
                $printStatus = $cuttingInventory->print_status; // 0 = Solid, 1 = Print, 2 = Embroidery
                if ($printStatus != 0 && !$bundleCard->print_sent_date && !$bundleCard->embroidary_sent_date) {
                    $status = 1;
                    $message = 'Sorry! This this bundle is not print/embroidery sent yet';
                } elseif ($printStatus == 0 && ($bundleCard->print_sent_date || $bundleCard->embroidary_sent_date)) {
                    $status = 1;
                    $message = 'Sorry! This is solid challan tag';
                }
            }
        }
        return [
            'status' => $status ?? 1,
            'message' => $message ?? null,
            'print_status' => $printStatus ?? null,
        ];
    }
}
