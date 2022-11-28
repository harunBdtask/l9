<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Session;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintReceiveInventory;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintReceiveInventoryChallan;

class PrintFactoryReceiveController extends Controller
{
    public function scanPage()
    {
        $challan_no = PrintReceiveInventory::getChallanNo();
        $bundle_info = $this->challanWiseBundles($challan_no) ?? [];
        $data = compact('challan_no', 'bundle_info');

        return view('printembrdroplets::forms.print_factory_recieve_scan', $data);
    }

    private function challanWiseBundles($challan_no)
    {
        return PrintReceiveInventory::with([
            'bundle_card:id,bundle_no,suffix,cutting_no,quantity,total_rejection,print_factory_receive_rejection,print_rejection,embroidary_rejection,buyer_id,order_id,purchase_order_id,color_id,size_id,lot_id',
            'bundle_card.order:id,order_style_no,booking_no',
            'bundle_card.purchaseOrder:id,po_no,po_quantity',
            'bundle_card.color:id,name',
            'bundle_card.size:id,name',
            'bundle_card.lot:id,lot_no'
        ])
            ->where('challan_no', $challan_no)
            ->orderby('bundle_card_id')
            ->get();
    }

    public function scanPost(Request $request)
    {
        try {

            $challan = PrintReceiveInventoryChallan::where('challan_no', $request->challan_no)->first();

            if (! $challan) {
                $bundleCard = BundleCard::with([
                    'buyer:id,name',
                    'order:id,order_style_no,booking_no',
                    'purchaseOrder:id,po_no',
                    'color:id,name',
                    'size:id,name',
                    'lot:id,lot_no',
                    'print_inventory:id,bundle_card_id,status'
                ])->where([
                    'id'     => substr($request->bundle_card_id, 1, 9),
                    'status' => ACTIVE
                ])->first();


                if ($bundleCard) {
                    if (optional($bundleCard->print_inventory)->status == 1) {
                        if (! $bundleCard->print_receive_inventory) {
                            $data = [
                                'challan_no'     => $request->challan_no,
                                'bundle_card_id' => $bundleCard->id,
                            ];

                            $printRcvInventory = PrintReceiveInventory::create($data);

                            if ($printRcvInventory) {
                                $status = 0;
                            }

                            if (substr($request->bundle_card_id, 0, 1) == 1) {
                                $rejection_status = 1; // For rejection bundle scan
                            }
                        } else {
                            $message = 'Already scanned this bundle in print factory receive!';
                        }
                    } else {
                        $message = 'Not yet send to print/embr.';
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

        return view('printembrdroplets::forms.print_factory_rcv_rejection', [
            'bundle' => $bundle,
            'type'   => request('type')
        ]);
    }

    public function rejectionPost(Request $request)
    {
        $request->validate([
            'bundle_id'     => 'required',
            'rejection_qty' => 'required|numeric|min:1'
        ]);

        try {

            $rejectionQty = $request->input('rejection_qty');
            $bundleInfo = DB::table('bundle_cards')
                ->where('id', $request->bundle_id);

            $bundleCard = $bundleInfo->first();

            $bundleTotalRejection = $rejectionQty + $bundleCard->total_rejection;

            if ($bundleTotalRejection < $bundleCard->quantity) {
                $bundleInfo->update(['print_factory_receive_rejection' => $rejectionQty]);
            }

            Session::flash('success', S_UPDATE_MSG);

        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect('/print-embr-factory-receive-scan');
    }
}