<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\Poly;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class IronPolyPackingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request): View
    {

        $polies = $this->getIronPolyPackingData($request);

        return view('finishingdroplets::pages.iron_poly_packings', compact('polies'));
    }

    public function getIronPolyPackingData($request): LengthAwarePaginator
    {
        $query = Poly::query();
        $query->when(request('buyer_id') != null, function ($buyer_query) {
            return $buyer_query->where('buyer_id', request('buyer_id'));
        });
        $query->when(request('order_id') != null, function ($order_query) {
            return $order_query->where('order_id', request('order_id'));
        });
        return $query->with([
            'buyer:id,name',
            'order:id,style_name',
            'purchaseOrder:id,po_no',
            'color:id,name'
        ])->latest()->paginate();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('finishingdroplets::forms.iron_poly_packing');
    }

    public function getPreviousData($purchaseOrderId, $colorId)
    {
        return TotalProductionReport::where([
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId
        ])->select(
            'total_sewing_output',
            'total_iron',
            'total_poly',
            'total_packing'
        )->first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        try {

            $count = 0;
            $status = 403;
            $validate = false;
            $buyer_id = $request->buyer_id;
            $order_id = $request->order_id;
            $production_date = $request->production_date;
            $finishing_floor_id = $request->finishing_floor_id;
            $finishing_table_id = $request->finishing_table_id;
            if (!$finishing_floor_id) {
                return response()->json([
                    'status' => $status,
                    'message' => 'Please Select Finishing Floor!'
                ]);
            }
            DB::beginTransaction();
            foreach ($request->color_id as $key => $val) {
                if (isset($request->poly_qty[$key])
                    || isset($request->poly_rejection_qty[$key])
                    || isset($request->iron_qty[$key])
                    || isset($request->iron_rejection_qty[$key])
                    || isset($request->iron_qty[$key])
                    || isset($request->packing_rejection_qty[$key])
                    || isset($request->packing_qty[$key])
                    || isset($request->packing_rejection_qty[$key])) {

                    $previous_data = $this->getPreviousData($request->purchase_order_id[$key], $request->color_id[$key]);
                    if (!$previous_data) {
                        $message = 'Sorry!! Please sewing production first!';
                    } elseif ($previous_data->total_sewing_output < ($previous_data->total_iron + $request->iron_qty[$key])) {
                        $message = 'Sorry!! Iron qty must be less than or equal sewing Qty';
                    } elseif ($previous_data->total_sewing_output < ($previous_data->total_poly + $request->poly_qty[$key])) {
                        $message = 'Sorry!! Poly qty must be less than or equal sewing Qty';
                    } elseif ($previous_data->total_sewing_output < ($previous_data->total_packing + $request->packing_qty[$key])) {
                        $message = 'Sorry!! Packing qty must be less than or equal sewing Qty';
                    } else {
                        $validate = true;
                    }
                    if (!$validate) {
                        DB::rollBack();
                        return response()->json([
                            'status' => $status,
                            'message' => $message
                        ]);
                    }

                    $poly = new Poly();
                    $poly->buyer_id = $buyer_id;
                    $poly->order_id = $order_id;
                    $poly->production_date = $production_date;
                    $poly->finishing_floor_id = $finishing_floor_id ?? null;
                    $poly->finishing_table_id = $finishing_table_id ?? null;
                    $poly->purchase_order_id = $request->purchase_order_id[$key];
                    $poly->color_id = $request->color_id[$key];

                    $poly->poly_qty = $request->poly_qty[$key] ?? 0;
                    $poly->poly_rejection_qty = $request->poly_rejection_qty[$key] ?? 0;

                    $poly->iron_qty = $request->iron_qty[$key] ?? 0;
                    $poly->iron_rejection_qty = $request->iron_rejection_qty[$key] ?? 0;

                    $poly->packing_qty = $request->packing_qty[$key] ?? 0;
                    $poly->packing_rejection_qty = $request->packing_rejection_qty[$key] ?? 0;

                    $poly->reason = $request->reason[$key];
                    $poly->remarks = $request->remarks[$key];
                    $poly->save();
                    $count = 1;
                }
            }
            if ($count == 0) {
                $message = 'Please At least one row fill up correctly!';
            } else {
                DB::commit();
                $status = 200;
                $message = S_SAVE_MSG;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $status = 500;
            $message = $e->getMessage();
        }
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $poly = Poly::findOrFail($id);
        $buyers = Buyer::pluck('name', 'id')->all();
        $orders = Order::where('buyer_id', $poly->buyer_id)->pluck('style_name', 'id')->all();
        $purchaseOrders = PurchaseOrder::where('order_id', $poly->order_id)->pluck('po_no', 'id')->all();
        $colors = PurchaseOrderDetail::with('color:id,name')
            ->where('quantity', '>', 0)
            ->where('purchase_order_id', $poly->purchase_order_id)
            ->get()->map(function ($item) {
                return $item->color;
            })->unique('id')->pluck('name', 'id')->all();

        return view('finishingdroplets::forms.edit_iron_poly_packing', [
            'poly' => $poly,
            'buyers' => $buyers,
            'orders' => $orders,
            'purchaseOrders' => $purchaseOrders,
            'colors' => $colors
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'finishing_floor_id' => 'required',
            'poly_qty' => 'min:0|numeric',
            'poly_rejection_qty' => 'min:0|numeric',
            'iron_qty' => 'min:0|numeric',
            'iron_rejection_qty' => 'min:0|numeric',
            'packing_qty' => 'min:0|numeric',
            'packing_rejection_qty' => 'min:0|numeric',
            'reason' => 'nullable|max:191',
            'remarks' => 'nullable|max:191'
        ], [
            'required' => 'This field is required',
            'poly_rejection_qty.numeric' => 'This field value must be numeric',
            'iron_qty.numeric' => 'This field value must be numeric',
            'iron_rejection_qty.required' => 'This field is required',
            'packing_qty.numeric' => 'This field value must be numeric',
            'packing_rejection_qty.numeric' => 'This field value must be numeric',
        ]);

        try {
            DB::beginTransaction();
            $poly = Poly::findOrFail($id);
            $poly->finishing_floor_id = $request->finishing_floor_id;
            $poly->poly_qty = $request->poly_qty;
            $poly->poly_rejection_qty = $request->poly_rejection_qty;
            $poly->iron_qty = $request->iron_qty;
            $poly->iron_rejection_qty = $request->iron_rejection_qty;
            $poly->packing_qty = $request->packing_qty;
            $poly->packing_rejection_qty = $request->packing_rejection_qty;
            $poly->reason = $request->reason;
            $poly->remarks = $request->remarks;
            $poly->save();
            DB::commit();
            Session::flash('success', S_SAVE_MSG);
            return redirect('/iron-poly-packings');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            Poly::destroy($id);
            $status = SUCCESS;
            DB::commit();
        } catch (\Exception $e) {
            $status = FAIL;
            DB::rollBack();
        }
        return $status;
    }

    public function getOrdersForPoly($orderId)
    {
        if (!\Request::ajax()) {
            return abort(404);
        }

        $purchaseOrders = TotalProductionReport::with([
            'color:id,name',
            'purchaseOrder:id,po_no'
        ])
            ->where('total_sewing_output', '>', 0)
            ->where('order_id', $orderId)
            ->select(
                'order_id',
                'purchase_order_id',
                'color_id',
                'total_sewing_output',
                'total_iron',
                'total_poly',
                'total_packing'
            )->get()->groupBy('purchase_order_id');

        $purchase_orders = [];
        foreach ($purchaseOrders as $purchaseOrderId => $pOrders) {
            $singlePo = $pOrders->first();
            $purchase_orders[$purchaseOrderId]['po_no'] = $singlePo->purchaseOrder->po_no ?? 'Po';
            $purchase_orders[$purchaseOrderId]['rowspan'] = count($pOrders) + 1;
            $purchase_orders[$purchaseOrderId]['purchase_order_id'] = $purchaseOrderId;

            $color_wise_info = [];
            foreach ($pOrders as $key => $color_wise) {
                $color_order_qty = $this->getColorWisePoQuantity($purchaseOrderId, $color_wise->color_id);
                /*if (!$color_order_qty) {
                    continue;
                }*/
                $color_wise_info[$key]['color_id'] = $color_wise->color_id;
                $color_wise_info[$key]['color_name'] = $color_wise->color->name;
                $color_wise_info[$key]['color_order_qty'] = $color_order_qty;
                $color_wise_info[$key]['total_sewing_output'] = $color_wise->total_sewing_output;
                $color_wise_info[$key]['total_iron'] = $color_wise->total_iron;
                $color_wise_info[$key]['total_poly'] = $color_wise->total_poly;
                $color_wise_info[$key]['total_packing'] = $color_wise->total_packing;
            }
            $purchase_orders[$purchaseOrderId]['color_wise_info'] = $color_wise_info;
        }

        return $purchase_orders;
    }

    public function getColorWisePoQuantity($purchaseOrderId, $colorId)
    {
        return PurchaseOrderdetail::getColorWisePoQuantity($purchaseOrderId, $colorId);
    }

    /*public function getOrdersForPoly($orderId)
    {
        if(!\Request::ajax()) {
            return abort(404);
        }

        $purchaseOrders = PurchaseOrder::with('purchaseOrderdetails:id,purchase_order_id,color_id,quantity')
            ->where('order_id', $orderId)
            ->select('id','po_no')
            ->get();

        foreach ($purchaseOrders as $key => $pOrder) {
            $colors = [];
            foreach ($pOrder->purchaseOrderdetails->groupBy('color_id') as $colorId => $colorGroupBy) {
                $colors[$key][$colorId]['color_id'] = $colorId;
                $colors[$key][$colorId]['colorName'] = $colorGroupBy->first()->color->name ?? '';
                $colors[$key][$colorId]['color_order_qty'] = $colorGroupBy->sum('quantity');

                $poliesData = Poly::where([
                    'purchase_order_id' => $pOrder->id,
                    'color_id' => $colorId
                ])->get();

                $colors[$key][$colorId]['sewing_receivd_qty'] = TotalProductionReport::where([
                    'purchase_order_id' => $pOrder->id,
                    'color_id' => $colorId
                ])->sum('total_sewing_output');

                $colors[$key][$colorId]['prev_poly_qty'] = $poliesData->sum('poly_qty');
                $colors[$key][$colorId]['prev_iron_qty'] = $poliesData->sum('iron_qty');
                $colors[$key][$colorId]['prev_packing_qty'] = $poliesData->sum('packing_qty');
            }
            $purchaseOrders[$key]->colors = $colors;
            $purchaseOrders[$key]->rowspan = $pOrder->purchaseOrderdetails->groupBy('color_id')->count() + 1 ?? '';
        }

        return $purchaseOrders;
    }*/
}
