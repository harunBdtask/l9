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

class PrintEmbrProductionController extends Controller
{
    
    public function printEmbrProductionScan()
    {
        $production_challan_no = $this->getProductionChallanNo();
        $bundle_info = $this->challanWiseBundles($production_challan_no);       
        $data = compact('production_challan_no', 'bundle_info');

        return view('printembrdroplets::forms.print_embr_production_scan', $data);
    }

    public function getProductionChallanNo()
    {
        $challan = PrintReceiveInventory::where([
            'status' => ACTIVE,
            'created_by' => userId(),
            'production_status' => ACTIVE
        ])->first();

        return $challan->production_challan_no ?? userId().time();
    }

    private function challanWiseBundles($production_challan_no)
    {
        return PrintReceiveInventory::with([
            'bundle_card:id,bundle_no,suffix,cutting_no,quantity,total_rejection,print_factory_receive_rejection,print_rejection,embroidary_rejection,buyer_id,order_id,purchase_order_id,color_id,size_id,lot_id,production_rejection_qty,qc_rejection_qty',
            'bundle_card.order:id,order_style_no,booking_no',
            'bundle_card.purchaseOrder:id,po_no,po_quantity',
            'bundle_card.color:id,name',
            'bundle_card.size:id,name'          
        ])
        ->where('production_challan_no', $production_challan_no)
        ->orderby('updated_at', 'desc')
        ->get();
    }

    public function printEmbrProductionScanPost(Request $request)
    {
       /* try {*/

            $bundleCard = BundleCard::with([
                'buyer:id,name',
                'order:id,order_style_no,booking_no',
                'purchaseOrder:id,po_no',
                'color:id,name',
                'size:id,name',
                'print_production:id,bundle_card_id,production_status'
            ])->where([
                'id'     => substr($request->bundle_card_id, 1, 9),
                'status' => ACTIVE
            ])->first();

            if ($bundleCard) {
                if ($bundleCard->print_production) {
                    if ($bundleCard->print_production->production_status == 0) {
                        $printRcvInventory = PrintReceiveInventory::where([
                            'bundle_card_id' => $bundleCard->id
                        ])->first();



                        if ($printRcvInventory) {
                            $printRcvInventory->production_challan_no = $request->production_challan_no;
                            $printRcvInventory->production_status = 1;
                            $printRcvInventory->save();
                            $status = 0;

                            $bundleQty = $bundleCard->quantity -
                                ($bundleCard->total_rejection + $bundleCard->print_factory_receive_rejection);
                            DB::table('print_factory_reports')->where('bundle_card_id', $bundleCard->id)
                                ->update(['production_qty' => $bundleQty]);

                        }
                        if (substr($request->bundle_card_id, 0, 1) == 1) {
                            $rejection_status = 1; // For rejection bundle scan
                        }
                    } else {
                        $message = 'Sorry!! Already in print production';
                    }
                } else {
                    $message = 'Not yet in print input';
                }
            } else {
                $message = 'Invalid bundle!! Please scan valid bundle';
            }
        /*} catch (Exception $e) {
            $message = $e->getMessage();
        }*/

        return response()->json([
            'status'           => $status ?? 1,
            'message'          => $message ?? '',
            'rejection_status' => $rejection_status ?? 0,
            'bundle_info'      => $bundleCard ?? null
        ]);
    }

    public function closePrintProductionChallan($production_challan_no)
    {
        try {
            $challan = PrintReceiveInventory::where('production_challan_no', $production_challan_no)->update([
                'production_status' => 2 // challan close status
            ]);
            Session::flash('success', 'Challan close successfully');
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());   
        }
        return redirect()->back();
    }
    
}