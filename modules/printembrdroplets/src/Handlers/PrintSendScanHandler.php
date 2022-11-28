<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Handlers;

use Exception;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Services\PrintSendCacheKeyService;

class PrintSendScanHandler
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        try {
            $challan_no = $this->request->challan_no;
            $bundle_card_id = $this->request->bundle_card_id;
            $isChallanExist = $this->checkIfChallanExist($challan_no);
            $requestValidated = true;
            $bundleCard = null;

            if ($isChallanExist) {
                $message = 'You have already created this challan so please reload this page';
                $requestValidated = false;
            } else {
                $bundleCardRelatedValidation = $this->isBundleCardRelatedValidationPersists($bundle_card_id);
                $bundleCard = $bundleCardRelatedValidation['bundleCard'];
                $message = $bundleCardRelatedValidation['message'];
                $requestValidated = $bundleCardRelatedValidation['requestValidated'];
            }

            if ($bundleCard && $requestValidated) {
                $updatedResponse = $this->updateData($challan_no, $bundle_card_id, $bundleCard);
                $status = $updatedResponse['status'];
                $rejection_status = $updatedResponse['rejection_status'];
                $message = $updatedResponse['message'];
                $bundle_info = $this->formatBundleInfo($bundleCard);
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

    private function checkIfChallanExist($challan_no)
    {
        return DB::table('print_inventory_challans')->where('challan_no', $challan_no)->first();
    }

    private function isBundleCardRelatedValidationPersists($bundle_card_id)
    {
        $bundleCard = $this->checkIfBundleCardExist($bundle_card_id);
        $message = '';
        $requestValidated = true;

        if (!$bundleCard) {
            $message = 'Invalid bundle!! Please scan valid bundle';
            $requestValidated = false;
        }
        if ($bundleCard && $bundleCard->input_scan_time) {
            $message = 'Already scanned in input section';
            $requestValidated = false;
        }
        if ($bundleCard && $bundleCard->print_embr_send_scan_time) {
            $message = 'Already scanned this bundle';
            $requestValidated = false;
        }
        return [
            'bundleCard' => $bundleCard,
            'message' => $message,
            'requestValidated' => $requestValidated,
        ];
    }

    private function checkIfBundleCardExist($bundle_card_id)
    {
        return BundleCard::with([
            'buyer:id,name',
            'order:id,style_name',
            'purchaseOrder:id,po_no',
            'color:id,name',
            'size:id,name',
            'lot:id,lot_no',
            'details:id,is_manual',
        ])->where([
            'id' => substr($bundle_card_id, 1, 9),
            'status' => ACTIVE
        ])->first();
    }

    private function updateData($challan_no, $bundle_card_id, $bundleCard)
    {
        $exception = DB::transaction(function () use ($challan_no, $bundle_card_id, $bundleCard) {
            $bundleCardId = ltrim(substr($bundle_card_id, 1, 9), 0);
            $input = [
                'challan_no' => $challan_no,
                'bundle_card_id' => $bundleCardId,
            ];
            $exist = PrintInventory::where('bundle_card_id', $bundleCardId)->first();
            if (!$exist) {
                PrintInventory::create($input);
            }
            DB::table('bundle_cards')->where('id', $bundleCardId)
                ->update([
                    'print_embr_send_scan_time' => now()
                ]);
            (new PrintSendCacheKeyService())->updateChallanBundlesCache($bundleCardId, $bundleCard);
        });
        if (!is_null($exception)) {
            $status = 1; // For transaction error
            $message = "Something went wrong! Please scan again!";
        } else {
            $status = 0;
            if (substr($bundle_card_id, 0, 1) == 1) {
                $rejection_status = 1; // For rejection bundle scan
            }
        }

        return [
            'status' => $status ?? 1,
            'rejection_status' => $rejection_status ?? 0,
            'message' => $message ?? '',
        ];
    }

    private function formatBundleInfo($bundleCard)
    {
        return [
            'id' => $bundleCard->id,
            'buyer_name' => $bundleCard->buyer->name ?? '',
            'style_name' => $bundleCard->order->style_name ?? '',
            'po_no' => $bundleCard->purchaseOrder->po_no ?? '',
            'color_name' => $bundleCard->color->name ?? '',
            'lot_no' => $bundleCard->lot->lot_no ?? '',
            'size_name' => $bundleCard->size->name ?? '',
            'cutting_no' => $bundleCard->cutting_no ?? '',
            'bundle_no' => $bundleCard->details->is_manual == 1 ? $bundleCard->size_wise_bundle_no : ($bundleCard->{getbundleCardSerial()} ?? $bundleCard->size_wise_bundle_no ?? $bundleCard->bundle_no ?? ''),
            'quantity' => $bundleCard->quantity ??  '',
        ];
    }
}
