<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Session;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintDeliveryInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintDeliveryInventoryChallan;
use SkylarkSoft\GoRMG\Printembrdroplets\Services\PrintScanService;

class PrintFactoryDeliveryController extends Controller
{
    public function scanPage(PrintScanService $service)
    {
        $challan_no = PrintDeliveryInventory::getChallanNo();
        $bundle_info = $service->printDeliveryChallanWiseBundles($challan_no);
        $data = compact('challan_no', 'bundle_info');

        return view('printembrdroplets::forms.print_factory_delivery_scan', $data);
    }

    public function scanPost(Request $request, PrintScanService $service)
    {
        try {
            $challan = PrintDeliveryInventoryChallan::where('challan_no', $request->challan_no)->first();

            if (!$challan) {
                $bundleCard = $service->getBundleCard($request);

                if ($bundleCard) {               
                    if (optional($bundleCard->print_receive_inventory)->status) {
                        if (! $bundleCard->print_delivery_inventory) {
                            $data = [
                                'challan_no'     => $request->challan_no,
                                'bundle_card_id' => $bundleCard->id,
                            ];

                            $inv = PrintDeliveryInventory::create($data);

                            if ($inv) {
                                $status = 0;
                            }

                            if (substr($request->bundle_card_id, 0, 1) == 1) {
                                $rejection_status = 1; // For rejection bundle scan
                            }
                        } else {
                            $message = 'Already scanned this bundle in print factroy delivery';
                        }
                    } else {
                        $message = 'Not yet receive in print factory receive.';
                    }
                } else {
                    $message = 'Invalid bundle!! Please scan valid bundle';
                }
            } else {
                $message = 'You have already created this challan so please reload this page';
            }

        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return response()->json([
            'status'           => $status ?? 1,
            'message'          => $message ?? '',
            'rejection_status' => $rejection_status ?? 0,
            'bundle_info'      => $bundleCard ?? null
        ]);
    }

    public function rejectionForm()
    {
        $bundle = BundleCard::findOrFail(request('bundeId'));

        return view('printembrdroplets::forms.print_factory_delivery_rejection', [
            'bundle' => $bundle,
            'type'   => request('type')
        ]);
    }

    public function rejectionPost(Request $request)
    {
        $this->validateRejectionPost($request);

        try {
            $rejectionQty = $request->rejection_qty;
            $bundleInfo = DB::table('bundle_cards')->where('id', $request->bundle_id);
            $bundleCard = $bundleInfo->first();
            $bundleTotalRejection = $rejectionQty + $bundleCard->total_rejection + $bundleCard->print_factory_receive_rejection;

            if ($bundleTotalRejection < $bundleCard->quantity) {
                $bundleInfo->update(['print_factory_delivery_rejection' => $rejectionQty]);
            }

            Session::flash('success', S_UPDATE_MSG);

        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect('/print-factory-delivery-scan');
    }

    private function validateRejectionPost(Request $request)
    {
        $request->validate([
            'bundle_id'     => 'required',
            'rejection_qty' => 'required|numeric|min:1'
        ]);
    }
}