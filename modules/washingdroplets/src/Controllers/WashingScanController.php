<?php

namespace SkylarkSoft\GoRMG\Washingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Washingdroplets\Models\DateWiseWashingProductionReport;
use SkylarkSoft\GoRMG\Washingdroplets\Models\Washing;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput;
use SkylarkSoft\GoRMG\Washingdroplets\Models\WashingReceive;
use SkylarkSoft\GoRMG\SystemSettings\Models\PrintFactory;
use SkylarkSoft\GoRMG\Washingdroplets\Models\WashingInventoryChallan;
use SkylarkSoft\GoRMG\Washingdroplets\Requests\WashingRequest;
use Session, DB;
use Carbon\Carbon;

class WashingScanController extends Controller
{
    
    public function washingScan()
    {
        $washing_challan_no = $this->getChallanNo();
        $washing_bundles = $this->getCurrentChallanData($washing_challan_no);

        return view('washingdroplets::forms.washing_scan', [
           'washing_challan_no' => $washing_challan_no,
           'washing_bundles' => $washing_bundles
        ]);
    }

    public function getChallanNo()
    {
        $washing = Washing::where([
            'status' => 0, 
            'user_id' => userId()
        ])->first();
       
        return $washing->washing_challan_no ?? userId().time();
    }

    public function getCurrentChallanData($washing_challan_no)
    {
        $relationalData = [
            'bundlecard:id,bundle_no,buyer_id,order_id,purchase_order_id,color_id,size_id,quantity,total_rejection,print_rejection,embroidary_rejection,sewing_rejection,washing_rejection',
            'buyer:id,name',
            'order:id,style_name',
            'purchaseOrder:id,po_no',
            'color:id,name',
            'size:id,name'
        ];

        return Washing::with($relationalData)->where([
                'washing_challan_no' => $washing_challan_no, 
                'status' => 0
            ])
            ->orderBy('created_at', 'asc')
            ->get();            
    }

    public function washingScanPost(Request $request)
    {        
        try {
            DB::beginTransaction();
            $sewingoutput = Sewingoutput::with('purchaseOrder:id,buyer_id,order_id', 'washing:id,bundle_card_id')
                ->where('bundle_card_id', substr($request->barcode, 1, 9))
                ->first();

            if ($sewingoutput) {
                if (!$sewingoutput->washing) {
                    $input = [
                        'bundle_card_id' => $sewingoutput->bundle_card_id,
                        'washing_challan_no' => $request->washing_challan_no,
                        'buyer_id' => $sewingoutput->purchaseOrder->buyer_id,
                        'order_id' => $sewingoutput->purchaseOrder->order_id,
                        'purchase_order_id' => $sewingoutput->purchase_order_id,
                        'color_id' => $sewingoutput->color_id,
                        'size_id' => $sewingoutput->bundlecard->size_id,
                        'user_id' => userId()
                    ];
                    Washing::create($input);
                    DB::table('bundle_cards')
                        ->where('id', $sewingoutput->bundle_card_id)
                        ->update([
                            'washing_date' => date('Y-m-d')
                        ]);
                    DB::commit();
                } else {
                   Session::flash('error', 'Sorry!! Already scan this barcode');
                }
            } else {
                Session::flash('error', 'Sorry!! This bundle is not sewing yet or invalid');
            }
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', $e->getMessage());
        }        

        return redirect('washing-scan');
    }    

    public function getWashingReceivedChallanNo()
    {
        $washingReceived = Washing::where([
            'received_challan_status' => 0,
            'received_status' => 1,
            'user_id' => userId()
        ])->first();

        return $washingReceived->washing_received_challan_no ?? userId().time();
    }

    public function receivedBundleFromWashing()
    {
        $washing_received_challan_no = $this->getWashingReceivedChallanNo();

        return view('washingdroplets::forms.received_bundle_from_washing', [
           'washing_received_challan_no' => $washing_received_challan_no,
           'washing_bundles' => $this->getWashingReceivedData($washing_received_challan_no)
        ]);
    }

    public function getWashingReceivedData($washing_received_challan_no)
    {
        return Washing::where([
            'washing_received_challan_no' => $washing_received_challan_no,
            'received_status' => 1,
            'received_challan_status' => 0
        ])
        ->with('bundlecard','purchaseOrder','color')
        ->get();
    }

    public function closeWashingReceivedChallan($washing_received_challan_no)
    {
        DB::table('washings')->where('washing_received_challan_no', $washing_received_challan_no)
            ->update(['received_challan_status' => 1]);

        return redirect('received-bundle-from-wash');
    }

    public function receivedBundleFromWashingPost(Request $request)
    {
        try {
            DB::beginTransaction();
            $washingBundle = Washing::where('bundle_card_id', substr($request->barcode, 1, 9))
                ->where('status', 1)
                ->where('received_status', 0)
                ->first();

            if ($washingBundle) {
                $washing = Washing::findorFail($washingBundle->id)
                    ->update([
                        'received_status' => 1,
                        'washing_received_challan_no' => $request->washing_received_challan_no,
                        'user_id' => userId()
                    ]);
                DB::commit();
                if($washing && substr($request->barcode, 0, 1) == 1) {
                    return view('washingdroplets::forms.washing_rejection')
                        ->with('washingBundle', $washingBundle);
                }
            } else {
                Session::flash('error', 'Sorry!! Pleae scan valid barcode');
            }            
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', $e->getMessage());
        }
        return redirect('received-bundle-from-wash');
    }

    public function washingRejectionPost(Request $request)
    {
        $request->validate([
            'washing_rejection' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();
            $washing = Washing::where('bundle_card_id', $request->id)->first();
            $bundlecard = $washing->bundlecard;
            $allRejection = $bundlecard->print_rejection 
                + $bundlecard->embroidary_rejection 
                + $bundlecard->sewing_rejection 
                + $bundlecard->total_rejection
                + $request->washing_rejection;

            if ($bundlecard && $bundlecard->quantity >= $allRejection) {
                DB::table('bundle_cards')->where('id', $request->id)
                    ->update(['washing_rejection' => $request->washing_rejection]);
            
                $washRejectionQty = $request->washing_rejection;
                $this->updateTotalProductionReportForWashingRejection($bundlecard, $washRejectionQty);
                $this->updateDateAndColorWiseProductionForWashingRejection($bundlecard, $washRejectionQty);

                DB::commit();
                Session::flash('success', S_UPDATE_MSG);
                return redirect('received-bundle-from-wash');
            } else {
                Session::flash('error', 'Sorry!! Rejection must be less than bundlecard quantity');
                return view('washingdroplets::forms.washing_rejection')
                    ->with('washingBundle', $washing);
            }
            
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', $e->getMessage());
        }

        return redirect()->back();
    }

    private function updateTotalProductionReportForWashingRejection($bundlecard, $washRejectionQty) 
    {
        $washingReport = TotalProductionReport::where([
            'purchase_order_id' => $bundlecard->purchase_order_id,
            'color_id' => $bundlecard->color_id
        ])->first();

        $washingReport->todays_washing_received -= $washRejectionQty;        
        $washingReport->todays_washing_rejection += $washRejectionQty;
        $washingReport->total_washing_received -= $washRejectionQty;
        $washingReport->total_washing_rejection += $washRejectionQty;
        $washingReport->save();

        return true;
    }

    private function updateDateAndColorWiseProductionForWashingRejection($bundlecard, $washRejectionQty)
    {
        $washingRcvDate = date('Y-m-d');
        $purchaseOrderId = $bundlecard->purchase_order_id;
        $colorId = $bundlecard->color_id;

        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $washingRcvDate,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
        ])->first();        

        $dateAndColorWiseProduction->washing_received_qty -= $washRejectionQty;
        $dateAndColorWiseProduction->washing_rejection_qty += $washRejectionQty;
        $dateAndColorWiseProduction->save();

        return true;
    }    

    public function receivedFromWash()
    {
        $buyers = Buyer::pluck('name', 'id')->all();
        
        return view('washingdroplets::forms.received_from_washing', [
           'buyers' => $buyers
        ]);
    }

    public function receivedFromWashPost(WashingRequest $request)
    {
        WashingReceive::create($request->all());
        Session::flash('success', 'Successfully Received');
        return redirect()->back();
    }

}
