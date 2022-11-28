<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Actions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\Inputdroplets\Services\PrintRcvInputCacheKeyService;

class SolidInputScanAction
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
        $bundleCardId = ltrim(substr($requestData->bundle_card_id, 1, 9), 0);
        $challanNo = $requestData->challan_no;
        try {
            $bundleCard = BundleCard::with([
                'details:id,is_manual',
                'cutting_inventory:id,bundle_card_id,challan_no',
                'print_inventory:id,bundle_card_id,challan_no',
                'buyer:id,name',
                'order:id,style_name',
                'purchaseOrder:id,po_no',
                'color:id,name',
                'size:id,name',
                'lot:id,lot_no'
            ])
                ->where([
                    'id' => $bundleCardId,
                    'status' => ACTIVE
                ])->first();

            $checkBundleIsScannable = $this->isScannable($bundleCard, $challanNo);
            $status = $checkBundleIsScannable['status'];
            $message = $checkBundleIsScannable['message'];
            $rejection_status = $checkBundleIsScannable['rejection_status'];
            $bundleInfo = $checkBundleIsScannable['bundle_info'];

            if ($status == 0) {
                $exception = $this->updateData($challanNo, $bundleCardId);
                if (!is_null($exception)) {
                    $status = 1; // For transaction error
                    $message = "Something went wrong! Please scan again!";
                } else {
                    $status = 0;
                    if (substr($requestData->bundle_card_id, 0, 1) == 1) {
                        $rejection_status = 1; // For rejection part scan
                    }
                    $bundleInfo = [
                        'id' => $bundleCardId,
                        'quantity' => $bundleCard->quantity ?? '',
                        'total_rejection' => $bundleCard->total_rejection ?? 0,
                        'print_rejection' => $bundleCard->print_rejection ?? 0,
                        'embroidary_rejection' => $bundleCard->embroidary_rejection ?? 0,
                        'buyer_name' => $bundleCard->buyer->name ?? '',
                        'style_name' => $bundleCard->order->style_name ?? '',
                        'po_no' => $bundleCard->purchaseOrder->po_no ?? '',
                        'color_name' => $bundleCard->color->name ?? '',
                        'lot_no' => $bundleCard->lot->lot_no ?? '',
                        'cutting_no' => $bundleCard->cutting_no ?? '',
                        'size_name' => ($bundleCard->size->name ?? '') . ($bundleCard->suffix ? '(' . $bundleCard->suffix . ')' : ""),
                        'bundle_no' => $bundleCard->details->is_manual == 1 ? $bundleCard->size_wise_bundle_no : ($bundleCard->{getbundleCardSerial()} ?? $bundleCard->bundle_no ?? ''),
                    ];
                    $this->insertCacheData($bundleInfo);
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
            'bundle_info' => $bundleInfo ?? null
        ];
    }

    private function isScannable($bundleCard, $challanNo)
    {
        $status = 0;
        if (!$bundleCard) {
            $status = 1;
            $message = 'Invalid barcode'; // For invald bundle
        } elseif ($bundleCard->input_scan_time) {
            $status = 1;
            $message = 'Already scanned';
        } elseif ($bundleCard->print_embr_send_scan_time) {
            $status = 1;
            $message = 'Please scan this bundle form print received section';
        } else {
            $cuttingInventory = CuttingInventory::with([
                'bundlecard:id,color_id',
                'cutting_inventory_challan:id,challan_no'
            ])
                ->where('challan_no', $challanNo)
                ->first();
            if (isset($cuttingInventory->cutting_inventory_challan)) {
                $status = 1;
                $message = 'You have already created this challan so please reload this page';
            } elseif ($cuttingInventory && ($cuttingInventory->bundlecard->color_id != $bundleCard->color_id)) {
                $status = 1;
                $message = 'Please scan same color bundle';
            }
        }

        return [
            'status' => $status ?? 1,
            'message' => $message ?? null,
            'rejection_status' => 0,
            'bundle_info' => null
        ];
    }

    private function updateData($challanNo, $bundleCardId)
    {
        return DB::transaction(function () use ($challanNo, $bundleCardId) {
            $input = [
                'challan_no' => $challanNo,
                'bundle_card_id' => $bundleCardId
            ];

            $exist = CuttingInventory::where('bundle_card_id', $bundleCardId)->first();
            
            if (!$exist) {
                CuttingInventory::create($input);
            }
            
            DB::table('bundle_cards')
                ->where('id', $bundleCardId)
                ->update([
                    'input_scan_time' => now(),
                ]);
        });
    }

    private function insertCacheData($bundle_info): void
    {
        (new PrintRcvInputCacheKeyService)->setItemStatus(0)->insertCacheData($bundle_info);
    }
}
