<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\Finishing;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\Packing;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class PackingAndGetUpController extends Controller
{
    public function packingListGenerateForm()
    {
        $challan_no = $this->getChallanNo();

        $buyers = Buyer::query()->pluck('name', 'id');

        return view('finishingdroplets::forms.packing_list_generate', [
            'challan_no' => $challan_no,
            'buyers' => $buyers
        ]);
    }

    public function getChallanNo(): int
    {
        $challan = Packing::query()->where('status', 0)->first();
        if ($challan && $challan->challan_no) {
            $challan_no = $challan->challan_no;
        } else {
            $challan_no = Packing::query()->max('challan_no') + 1;
        }
        return $challan_no;
    }

    public function packingListGenerateView($purchase_order_id, $color_id)
    {
        $sizes = PurchaseOrderDetail::where(['purchase_order_id' => $purchase_order_id, 'color_id' => $color_id])->with('size')->get();
        foreach ($sizes as $key => $size) {
            $sizes[$key]->packing_qty = Packing::where(['purchase_order_id' => $size->purchase_order_id, 'color_id' => $size->color_id, 'size_id' => $size->size_id])->sum('quantity');
        }
        $view = view('finishingdroplets::forms.packing_list_generate_form', compact('sizes'))->render();
        return response()->json(['view' => $view]);
    }

    public function packingListGenerateAction(Request $request)
    {
        $request->validate([
            'buyer_id' => 'required',
            'purchase_order_id' => 'required',
            'color_id' => 'required',
            'size_id' => 'required',
            'color_id' => 'required',
            'quantity' => 'required'
        ]);

        //return $request->all();

        $buyer_id = $request->buyer_id;
        $purchase_order_id = $request->purchase_order_id;
        $order_id = $request->order_id;
        $color_id = $request->color_id;
        $challan_no = $request->challan_no;
        $insert_rows = count($request->size_id);
        $user_id = userId();

        for ($i = 0; $i < $insert_rows; $i++) {
            $packing_data = [
                'buyer_id' => $buyer_id,
                'purchase_order_id' => $purchase_order_id,
                'order_id' => $order_id,
                'color_id' => $color_id,
                'size_id' => $request->size_id[$i],
                'quantity' => $request->quantity[$i],
                'challan_no' => $challan_no,
                'status' => ACTIVE,
                'user_id' => $user_id,
                'factory_id' => currentUser()->factory_id,
                'created_at' => date('Y/m/d h:i:s', time()),
                'updated_at' => date('Y/m/d h:i:s', time()),
            ];
            $insertData[] = $packing_data;
        }
        try {
            Packing::insert($insertData);
            Session::flash('success', S_UPDATE_MSG);
            return redirect('packing-view/' . $challan_no);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }
        return redirect()->back();
    }

    public function packingView($challan_no)
    {
        $packing_details = Packing::where('challan_no', $challan_no)
            ->with('buyer', 'purchaseOrder', 'color', 'size')
            ->get();

        foreach ($packing_details as $key => $pack) {
            $packing_details[$key]->size_order_qty = PurchaseOrderDetail::where(['purchase_order_id' => $pack->purchase_order_id, 'color_id' => $pack->color_id, 'size_id' => $pack->size_id])->first()->quantity;
        }
        return view('finishingdroplets::forms.packing_view')->with('packing_details', $packing_details);
    }

    public function updateGetupProduction()
    {
        $buyers = Buyer::pluck('name', 'id')->all();
        return view('finishingdroplets::forms.update_getup_production', [
            'buyers' => $buyers
        ]);
    }

    public function updateGetupProductionForm($purchase_order_id, $color_id)
    {
        $sizes = PurchaseOrderDetail::where(['purchase_order_id' => $purchase_order_id, 'color_id' => $color_id])
            ->with('size')
            ->get();

        foreach ($sizes as $key => $size) {
            $sizes[$key]->getup_qty = Finishing::where(['purchase_order_id' => $size->purchase_order_id, 'color_id' => $size->color_id, 'size_id' => $size->size_id])->sum('quantity');
        }
        $view = view('finishingdroplets::forms.update_getup_production_form', compact('sizes'))->render();
        return response()->json(['view' => $view]);
    }

    public function updateGetupProductionAction(Request $request)
    {
        $request->validate([
            'buyer_id' => 'required',
            'order_id' => 'required',
            'color_id' => 'required',
            'size_id' => 'required',
            'color_id' => 'required',
            'quantity' => 'required'
        ]);

        $buyer_id = $request->buyer_id;
        $order_id = $request->style_id;
        $purchase_order_id = $request->purchase_order_id;
        $color_id = $request->color_id;
        $challan_no = $request->challan_no;
        $insert_rows = count($request->size_id);

        for ($i = 0; $i < $insert_rows; $i++) {
            $packing_data = [
                'buyer_id' => $buyer_id,
                'purchase_order_id' => $purchase_order_id,
                'order_id' => $order_id,
                'color_id' => $color_id,
                'size_id' => $request->size_id[$i],
                'quantity' => $request->quantity[$i],
                'challan_no' => $challan_no,
                'status' => ACTIVE,
                'user_id' => userId(),
                'factory_id' => factoryId(),
                'created_at' => date('Y/m/d h:i:s', time()),
                'updated_at' => date('Y/m/d h:i:s', time()),
            ];
            $insertData[] = $packing_data;
        }
        try {
            Finishing::insert($insertData);
            Session::flash('success', S_UPDATE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }
        return redirect()->back();
    }

}
