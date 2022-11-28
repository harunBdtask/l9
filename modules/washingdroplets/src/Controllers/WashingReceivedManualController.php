<?php

namespace SkylarkSoft\GoRMG\Washingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Washingdroplets\Models\WashingReceivedManual;
use Session, Validator, DB;
use Carbon\Carbon;

class WashingReceivedManualController extends Controller
{
    
    public function index()
    {
        $washingChallans = WashingReceivedManual::with('color:id,name')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('washingdroplets::pages.washing_received_challan_list', [
           'washingChallans' => $washingChallans
        ]);
    }

    public function searchWashingReceivedChallan(Request $request)
    {
        $washingChallans = WashingReceivedManual::with('color:id,name')
            ->where('challan_no', 'like', '%' . $request->q . '%')
            ->orderBy('id', 'desc')
            ->paginate(20);           

        return view('washingdroplets::pages.washing_received_challan_list', [
           'washingChallans' => $washingChallans,
           'q' => $request->q
        ]);
    }

    public function receivedFromWash()
    {
        // only get washing sent buyer list
        $buyerWiseWashingSent = TotalProductionReport::with('buyer:id,name')
            ->where('total_washing_sent', '>', 0)
            ->select('buyer_id', 'total_washing_sent')
            ->get();

        $buyers = [];
        foreach ($buyerWiseWashingSent->groupBy('buyer_id') as $buyerId => $buyerWiseData) {       
            $buyers[$buyerId] = $buyerWiseData->first()->buyer->name ?? 'N/A';
        }
        
        return view('washingdroplets::forms.received_from_washing', [
           'buyers' => $buyers
        ]);
    }

    /*public function getTotalProductionReportData($whereId, $type) 
    {
        $totalProductionReport = TotalProductionReport::with($type)            
            ->where('total_washing_sent', '>', 0)
            ->select('order_id', 'color_id', 'style_id', 'total_washing_sent', 'total_washing_received');
            
            if ($type == 'buyer') {
                $totalProductionReport->where('buyer_id', $whereId);
            } else if($type == 'style') {             
                $totalProductionReport->where('buyer_id', $whereId);
            }
            
            $totalProductionReport = $totalProductionReport->get();
        
        return $totalProductionReport;
    }*/

    public function getWashingSentOrders($buyerId)
    {
        $orderWiseWashingSent = TotalProductionReport::with('order:id,style_name')
            ->where('buyer_id', $buyerId)
            ->where('total_washing_sent', '>', 0)
            ->select('order_id')
            ->get()
            ->groupBy('order_id');

        $orders = [];     
        foreach ($orderWiseWashingSent as $orderId => $orderWiseData) {       
            $orders[$orderId] = $orderWiseData->first()->order->style_name ?? 'N/A';
        }
        
        return $orders;
    }

    public function getColorWiseWashingReceived($orderId)
    {        
        $colorWiseWashingSent = TotalProductionReport::with('purchaseOrder:id,po_no,po_quantity', 'color:id,name')
            ->where('order_id', $orderId)
            ->where('total_washing_sent', '>', 0)
            ->select('purchase_order_id', 'color_id', 'total_washing_sent', 'total_washing_received')
            ->get();

        $uniqueColors = [];
        foreach ($colorWiseWashingSent->groupBy('color_id') as $colorId => $colorWiseData) {       
            $uniqueColors[$colorId] = $colorWiseData->first()->colors->name ?? 'N/A';
        }

        return response()->json([
            'uniqueColors'  => $uniqueColors,
            'colorWiseWashingSent' => $colorWiseWashingSent
        ]);          

    }

    public function receivedFromWashPost(Request $request)
    {
        $status = 0;
        $rules = [
            'buyer_id' => 'required',
            'order_id' => 'required',
            'challan_no' => 'required'        
        ];
        $messages = [
            'buyer_id.required' => 'The buyer field is required.',
            'order_id.required' => 'The style field is required.',
            'challan_no.required' => 'The challan no field is required.'            
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
        {
            return response()->json([
                'status' => 422,          
                'errors'=> $validator->errors()->all()
            ]);
        }

        if (array_sum($request->color_wise_qty) == 0) {
            $status = 1;
            $message = 'Please Fill up at least one color';  
        }

        /*$colorWiseQty = array_sum($request->color_wise_qty);
        if ($colorWiseQty == array_sum($request->received_qty) + array_sum($request->rejection_qty)) {
            $message = 'Color wise & po wise received data mismatch';
        }*/

        $countPoWiseColor = count($request->received_qty);
        foreach ($request->color_wise_qty as $key => $value) {       
            $colorWiseTotal = 0;
            for ($i = 0; $i < $countPoWiseColor; $i++) {
                if (isset($request->received_qty[$i]) && $request->color_id[$i] == $key) {                   
                    $colorWiseTotal += $request->received_qty[$i] + $request->rejection_qty[$i];
                }
            }

            if ($value != $colorWiseTotal) {
                $status = 1;
                $message = 'Sorry!! Received qty & assigned qty mismatch. Please check this';
                break;
            }
        }        
        
        try {
            if ($status == 0) {
                DB::beginTransaction();
                $countPoWiseColor = count($request->received_qty);      
                $input = [];
                $dateTime = Carbon::now();
                for ($i = 0; $i < $countPoWiseColor; $i++) {
                    if (isset($request->received_qty[$i])) {
                        $input = [
                            'challan_no' => $request->challan_no,
                            'buyer_id' => $request->buyer_id,
                            'order_id' => $request->order_id,
                            'purchase_order_id' => $request->purchase_order_id[$i],
                            'color_id' => $request->color_id[$i],
                            'received_qty' => $request->received_qty[$i],
                            'rejection_qty' => $request->rejection_qty[$i] ?? 0,
                            'reasons' => $request->reasons[$i],
                            'user_id' => userId()
                        ];
                        WashingReceivedManual::create($input);
                    }           
                }                
                DB::commit();
                $message = 'Successfully updated';
            }
        } catch (Exception $e) {
            DB::rollback();
            $status = 3;
            $message = $e->getMessage();
        }    

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function washingReceivedChallanEdit($challan_no)
    {
        $buyers = Buyer::pluck('name', 'id')->all();
        $washingReceivedChallan = WashingReceivedManual::with('purchaseOrder:id,po_no,po_quantity', 'color:id,name')
            ->where('challan_no', $challan_no)
            ->get();

        $orders = Order::where('buyer_id', $washingReceivedChallan->first()->buyer_id)
            ->pluck('style_name', 'id')
            ->all();

         return view('washingdroplets::forms.received_washing_challan_edit', [
           'buyers' => $buyers,
           'orders' => $orders,
           'washingReceivedChallan' => $washingReceivedChallan
        ]);
    }

    public function washingReceivedChallanEditPost(Request $request)
    {
        $status = 0;
        $rules = [
            'buyer_id' => 'required',
            'order_id' => 'required',
            'challan_no' => 'required',       
        ];

        $messages = [
            'buyer_id.required' => 'The buyer field is required.',
            'order_id.required' => 'The style field is required.',
            'challan_no.required' => 'The challan no field is required.'            
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
        {
            return response()->json([
                'status' => 422,          
                'errors'=> $validator->errors()->all()
            ]);
        }

        if (array_sum($request->color_wise_qty) == 0) {
            $status = 1;
            $message = 'Please Fill up at least one color';  
        }

        /*$colorWiseQty = array_sum($request->color_wise_qty);
        if ($colorWiseQty == array_sum($request->received_qty) + array_sum($request->rejection_qty)) {
            $message = 'Color wise & po wise received data mismatch';
        }*/
       
        $countPoWiseColor = count($request->received_qty);
        foreach ($request->color_wise_qty as $key => $value) { 
            $colorWiseTotal = 0;
            for ($i = 0; $i < $countPoWiseColor; $i++) {
                if (isset($request->received_qty[$i]) && $request->color_id[$i] == $key) {                 
                    $colorWiseTotal += $request->received_qty[$i] + $request->rejection_qty[$i];
                }
            }

            if ($value != $colorWiseTotal) {
                $status = 1;
                $message = 'Sorry!! Received qty & assigned qty mismatch. Please check this';
                break;
            }
        }

        if ($status == 0) {        
            try {
                DB::beginTransaction();
                $countPoWiseColor = count($request->received_qty);
                $dateTime = Carbon::now();
                for ($i = 0; $i < $countPoWiseColor; $i++) {
                    if (isset($request->received_qty[$i])) {                        
                        $washingReceivedManual = WashingReceivedManual::findOrFail($request->id[$i]);
                        $washingReceivedManual->challan_no =  $request->challan_no;
                        $washingReceivedManual->received_qty =  $request->received_qty[$i] ?? 0;
                        $washingReceivedManual->rejection_qty =  $request->rejection_qty[$i] ?? 0;
                        $washingReceivedManual->reasons =  $request->reasons[$i];
                        $washingReceivedManual->save();
                    }
                }
                
                DB::commit();
                $message = 'Successfully updated';
            } catch (Exception $e) {
                DB::rollback();
                $status = 3;
                $message = $e->getMessage();
            }
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function destroy($challan_no)
    {        
        try {
            DB::beginTransaction();
            $washingReceivedManuals = WashingReceivedManual::where('challan_no', $challan_no)
                ->select('id')
                ->get();

            if ($washingReceivedManuals) {
                foreach ($washingReceivedManuals as $washingReceived) {
                    $row = WashingReceivedManual::findOrFail($washingReceived->id);
                    $row->delete();
                }
            }

            DB::commit();
            Session::flash('success', S_DEL_MSG);
            
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }

        return redirect()->back();
    }

   /* public function searchWashingChallan(Request $request)
    {
        $washingChallans = WashingInventoryChallan::where('washing_challan_no', 'like', '%' . $request->q . '%')
            ->orderBy('id', 'desc')
            ->paginate();

        return view('washingdroplets::pages.washing_challan_list', [
           'washingChallans' => $washingChallans,
           'q' => $request->q
        ]);
    }

    public function edit($id)
    {
        $washing_challan = WashingInventoryChallan::find($id);
        $washing_factories = PrintFactory::where('factory_type', 'wash')
            ->pluck('factory_name', 'id')
            ->all();

        return view('washingdroplets::forms.washing_challan_edit',[
            'washing_challan' => $washing_challan,
            'washing_factories' => $washing_factories
        ]);
    }

    public function update($id, WashingChallanUpdateRequest $request)
    {
        try{
            DB::beginTransaction();
            $washing_challan = WashingInventoryChallan::findOrFail($id);
            $washing_challan->print_wash_factory_id = $request->print_wash_factory_id;
            $washing_challan->bag = $request->bag;
            $washing_challan->save();
            DB::commit();
            Session::flash('success', 'Data Updated successfully!!');
            return redirect('/washing-challan-list');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!ERROR CODE Wash Challan.U-102');
            return redirect()->back();
        }
    }*/

}
