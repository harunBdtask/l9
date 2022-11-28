<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Actions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\Inputdroplets\Services\PrintRcvInputCacheKeyService;

class PrintRcvScanAction
{
    protected $request;

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function handle()
    {
        $requestData = $this->getRequest();
        try {
            $challan_no = $requestData->challan_no;
            $bundle_card_id = ltrim(substr($requestData->bundle_card_id, 1, 9), 0);
            $bundleCard = BundleCard::with([
                'details:id,is_manual',
                'print_inventory:id,bundle_card_id,challan_no,status',
                'buyer:id,name',
                'order:id,style_name',
                'purchaseOrder:id,po_no',
                'color:id,name',
                'size:id,name',
                'lot:id,lot_no'
            ])
                ->where('id', $bundle_card_id)
                ->where('status', \ACTIVE)
                ->first();

            $checkBundleIsScannable = $this->isScannable($bundleCard, $challan_no);
            $status = $checkBundleIsScannable['status'];
            $message = $checkBundleIsScannable['message'];
            $rejection_status = $checkBundleIsScannable['rejection_status'];
            $bundle_info = $checkBundleIsScannable['bundle_info'];

            if ($status == 0) {
                $exception = $this->updateData($bundleCard, $challan_no);

                if (!is_null($exception)) {
                    $status = 1; // For transaction error
                    $message = "Something went wrong! Please scan again!";
                } else {
                    if (substr($requestData->bundle_card_id, 0, 1) == 1) {
                        $rejection_status = 1; // For rejection part scan
                    }
                    $bundle_info = [
                        'id' => $bundle_card_id,
                        'quantity' => $bundleCard->quantity,
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
                        'bundle_no' => $bundleCard->details->is_manual == 1 ? $bundleCard->size_wise_bundle_no : ($bundleCard->{getbundleCardSerial()} ?? $bundleCard->bundle_no),
                    ];

                    $this->insertCacheData($bundle_info);
                }
            }
        } catch (Exception $e) {
            $status = 1;
            $message = $e->getMessage();
        }

        return [
            'status' => $status ?? 1,
            'message' => $message ?? '',
            'rejection_status' => $rejection_status ?? 0,
            'bundle_info' => $bundle_info ?? null
        ];
    }

    private function isScannable($bundleCard, $challan_no)
    {
        $status = 0;
        if (!$bundleCard) {
            $status = 1;
            $message = 'Invalid Bundle!';
        } elseif ($bundleCard->input_scan_time || $bundleCard->print_embr_received_scan_time) {
            $status = 1;
            $message = 'This bundle already received/input!';
        } elseif (!$bundleCard->print_sent_date && !$bundleCard->embroidary_sent_date) {
            $status = 1;
            $message = 'This bundle is not sent yet!';
        } else {
            $cuttingInventory = CuttingInventory::with([
                'bundlecard:id,color_id',
                'cutting_inventory_challan:id,challan_no'
            ])
                ->where('challan_no', $challan_no)
                ->first();
            if (isset($cuttingInventory->cutting_inventory_challan)) {
                $status = 1;
                $message = 'You have already created this challan so please reload this page';
            } elseif ($cuttingInventory && ($cuttingInventory->bundlecard->color_id != $bundleCard->color_id)) {
                $status = 1;
                $message = 'Please scan same colors bundle';
            }
        }

        return [
            'status' => $status ?? 1,
            'message' => $message ?? null,
            'rejection_status' => 0,
            'bundle_info' => null
        ];
    }

    private function updateData($bundleCard, $challan_no)
    {
        return DB::transaction(function () use ($bundleCard, $challan_no) {
            $operation_name = $bundleCard->print_inventory->printInventoryChallan->operation_name;
            if ($operation_name == PRNT) {
                // FOR PRINT
                $print_status = 1;
                $rcv_date_column = 'print_received_date';
            } else {
                // FOR EMBROIDERY
                $print_status = 2;
                $rcv_date_column = 'embroidary_received_date';
            }
            $input = [
                'challan_no' => $challan_no,
                'bundle_card_id' => $bundleCard->id,
                'print_status' => $print_status
            ];

            $exist = CuttingInventory::where('bundle_card_id', $bundleCard->id)->first();
            
            if (!$exist) {
                CuttingInventory::create($input);
            }

            DB::table('bundle_cards')
                ->where('id', $bundleCard->id)
                ->update([
                    $rcv_date_column => operationDate(),
                    'print_embr_received_scan_time' => now(),
                    'input_scan_time' => now(),
                ]);
        });
    }

    private function insertCacheData($bundle_info): void
    {
        (new PrintRcvInputCacheKeyService)->setItemStatus(1)->insertCacheData($bundle_info);
    }
}
