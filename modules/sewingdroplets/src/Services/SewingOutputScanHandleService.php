<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput;

class SewingOutputScanHandleService
{
    protected $request;

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setRequest(Request $request): SewingOutputScanHandleService
    {
        $this->request = $request;
        return $this;
    }

    public function handle()
    {
        $requestData = $this->getRequest();
        $bundleCardId = ltrim(substr($requestData->bundle_card_id, 1, 9), 0);
        $output_challan_no = $requestData->output_challan_no;
        try {
            $bundleCard = BundleCard::query()
                ->with([
                    'details:id,is_manual',
                    'buyer:id,name',
                    'order:id,style_name',
                    'purchaseOrder:id,po_no',
                    'color:id,name',
                    'size:id,name',
                    'cutting_inventory:bundle_card_id,challan_no'
                ])
                ->where([
                    'id' => $bundleCardId,
                    'status' => ACTIVE,
                ])
                ->first();

            $scannable = $this->isBundleScannable($bundleCard);
            if (!$scannable['status']) {
                $message = $scannable['message'];
            } else {
                $formattedData = $this->formatData($bundleCardId, $output_challan_no, $bundleCard);
                $details = $formattedData['details'];
                $input = $formattedData['input'];

                $exception = $this->updateData($input);
                if (is_null($exception)) {
                    $status = 0;
                    (new SewingOutputCacheKeyService)->updateCacheData($details);
                    if (substr($requestData->bundle_card_id, 0, 1) == 1) {
                        $rejection_status = 1;
                    }
                } else {
                    $status = 1;
                    $message = "Something went wrong! Please scan again!";
                }
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $message = 'Something went wrong';
        }

        return [
            'status' => $status ?? 1,
            'message' => $message ?? '',
            'rejection_status' => $rejection_status ?? 0,
            'details' => $details ?? null,
            'error' => $error ?? null,
        ];
    }

    private function updateData($data)
    {
        return DB::transaction(function () use ($data) {
            $bundleCardId = $data['bundle_card_id'];
            $exists = Sewingoutput::where('bundle_card_id', $bundleCardId)->first();
            if (!$exists) {
                Sewingoutput::create($data);
            }
            DB::table('bundle_cards')
                ->where('id', $bundleCardId)
                ->update([
                    'sewing_output_date' => \operationDate()
                ]);
        });
    }

    private function formatData($bundleCardId, $output_challan_no, $bundleCard)
    {
        $output_qty = $bundleCard->quantity
            - $bundleCard->print_rejection
            - $bundleCard->embroidary_rejection
            - $bundleCard->sewing_rejection
            - $bundleCard->total_rejection;
        $details = [
            'bundle_card_id' => $bundleCardId,
            'line_no' => $bundleCard->cutting_inventory->cutting_inventory_challan->line->line_no,
            'buyer' => $bundleCard->buyer->name,
            'style_name' => $bundleCard->order->style_name,
            'po_no' => $bundleCard->purchaseOrder->po_no,
            'color' => $bundleCard->color->name,
            'size' => $bundleCard->size->name,
            'bundle_no' => $bundleCard->details->is_manual == 1 ? $bundleCard->size_wise_bundle_no : $bundleCard->{getbundleCardSerial()} ?? $bundleCard->bundle_no,
            'sewing_qty' => $output_qty,
        ];
        $input = [
            'bundle_card_id' => $bundleCardId,
            'output_challan_no' => $output_challan_no,
            'challan_no' => $bundleCard->cutting_inventory->challan_no,
            'line_id' => $bundleCard->cutting_inventory->cutting_inventory_challan->line_id,
            'hour' => date('H'),
            'user_id' => userId(),
            'purchase_order_id' => $bundleCard->purchase_order_id,
            'color_id' => $bundleCard->color_id,
            'details' => $details
        ];

        return [
            'details' => $details,
            'input' => $input,
        ];
    }

    private function isBundleScannable($bundleCard)
    {
        $status = true;
        $message = null;

        if (!$bundleCard) {
            $status = false;
            $message = "Invalid Bundle!";
        } elseif (!$bundleCard->input_date) {
            $status = false;
            $message = "This bundle is not input yet";
        } elseif ($bundleCard->sewing_output_date) {
            $status = false;
            $message = "Already scanned this bundle";
        }

        return [
            'status' => $status,
            'message' => $message,
        ];
    }
}
