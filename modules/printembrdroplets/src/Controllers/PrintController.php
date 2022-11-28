<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Handlers\CuttingRejectionBeforePrintSendRejectionHandler;
use SkylarkSoft\GoRMG\Printembrdroplets\Handlers\PrintSendScanHandler;
use SkylarkSoft\GoRMG\Printembrdroplets\Services\PrintSendCacheKeyService;

class PrintController extends Controller
{
    public function printInventoryScan($challanNo = null)
    {
        $challanNo = $this->getChallanNo();
        $bundle_info = $this->challanWiseBundles($challanNo);

        return view('printembrdroplets::forms.print_inventory_scan', [
            'challan_no' => $challanNo,
            'bundle_info' => $bundle_info
        ]);
    }

    private function challanWiseBundles($challan_no)
    {
        $cacheKey = (new PrintSendCacheKeyService)->getChallanBundlesCacheKey();

        return Cache::remember($cacheKey, 86400, function () use ($challan_no) {
            return PrintInventory::getChallaWiseBundleQuery($challan_no)
                ->map(function ($item) {
                    return [
                        'bundle_card_id' => $item->bundle_card->id,
                        'buyer_name' => $item->bundle_card->buyer->name ?? '',
                        'style_name' => $item->bundle_card->order->style_name ?? '',
                        'po_no' => $item->bundle_card->purchaseOrder->po_no ?? '',
                        'color_name' => $item->bundle_card->color->name ?? '',
                        'lot_no' => $item->bundle_card->lot->lot_no ?? '',
                        'size_name' => ($item->bundle_card->size->name ?? '') . ($item->bundle_card->suffix ? '(' . $item->bundle_card->suffix . ')' : ''),
                        'cutting_no' => $item->bundle_card->cutting_no ?? '',
                        'bundle_no' => $item->bundle_card->details->is_manual == 1 ? $item->bundle_card->size_wise_bundle_no : ($item->bundle_card->{getbundleCardSerial()} ?? $item->bundle_card->bundle_no ?? ''),
                        'quantity' => $item->bundle_card->quantity,
                        'total_rejection' => $item->bundle_card->total_rejection,
                    ];
                });
        });
    }

    public function printScanPost(Request $request)
    {
        $response = (new PrintSendScanHandler($request))->handle();

        return response()->json($response);
    }

    private function getChallanNo()
    {
        $cacheKey = (new PrintSendCacheKeyService)->getChallanNoCacheKey();
        
        return Cache::remember($cacheKey, 86400, function () {
            $challan = PrintInventory::where([
                'status' => 0,
                'created_by' => userId()
            ])->first();

            return $challan->challan_no ?? userId() . time();
        });
    }

    public function printRejection()
    {
        $cuttingInventory = CuttingInventory::where('bundle_card_id', request('bundeId'))->first();
        $printOrEmbroidery = ($cuttingInventory->print_status == 1) ? 'Print' : 'Embroidery';

        return view('printembrdroplets::forms.print_received_rejection', [
            'cuttingInventory' => $cuttingInventory,
            'printOrEmbroidery' => $printOrEmbroidery,
            'type' => request('type') ?? null
        ]);
    }

    public function printRejectionPost(Request $request)
    {
        $request->validate([
            'print_rejection' => 'required|numeric|min:0'
        ]);

        $redirection_url = (new CuttingRejectionBeforePrintSendRejectionHandler($request))->handle();

        return $redirection_url ? redirect($redirection_url) : redirect()->back();
    }
}
