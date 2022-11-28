<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\RackCartonPosition;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseCarton;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseCartonDetail;

class WarehouseCartonController extends Controller
{
    public function index()
    {
        $warehouse_cartons = WarehouseCarton::orderBy('id', 'DESC')->paginate();

        return view('warehouse-management::pages.warehouse_cartons', ['warehouse_cartons' => $warehouse_cartons]);
    }

    public function create()
    {
        return view('warehouse-management::forms.warehouse_carton', [
            'warehouse_carton' => null,
            'buyers' => [],
            'orders' => [],
            'purchase_orders' => [],
            'colors' => [],
            'sizes' => [],
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'buyer_id' => 'required',
            'order_id' => 'required',
            'purchase_order_id' => 'required',
            'color_id.*' => 'required',
            'size_id.*' => 'required',
            'quantity.*' => 'required',
        ], [
            'required' => 'This field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ]);
        }

        try {
            DB::beginTransaction();
            if (DB::table('warehouse_cartons')->count()) {
                $warehouse_carton_last_id = DB::table('warehouse_cartons')->orderBy('id', 'desc')->first()->id;
            } else {
                $warehouse_carton_last_id = 0;
            }
            $barcode_no = str_pad(($warehouse_carton_last_id + 1), 10, "0", STR_PAD_LEFT);

            $color_ids = $request->get('color_id');
            $is_quantity_given = 0;
            $total_garments_qty = 0;
            if (count($color_ids)) {
                foreach ($color_ids as $key => $color_id) {
                    if ($request->quantity[$key]) {
                        $is_quantity_given = 1;
                        $total_garments_qty += $request->quantity[$key];
                    }
                }
            }

            if (! $is_quantity_given) {
                $html = view('partials.flash_message', [
                    'message_class' => "danger",
                    'message' => "At least one color quantity must be greater than zero!!",
                ])->render();

                return response()->json([
                    'status' => 'danger',
                    'errors' => null,
                    'message' => $html,
                ]);
            }

            $warehouse_carton = new WarehouseCarton();
            $warehouse_carton->barcode_no = $barcode_no;
            $warehouse_carton->buyer_id = $request->buyer_id;
            $warehouse_carton->order_id = $request->order_id;
            $warehouse_carton->purchase_order_id = $request->purchase_order_id;
            $warehouse_carton->garments_qty = $total_garments_qty;
            $warehouse_carton->save();

            $color_ids = $request->get('color_id');
            if (count($color_ids)) {
                foreach ($color_ids as $key => $color_id) {
                    if ($request->quantity[$key] > 0) {
                        $warehouse_carton_detail = new WarehouseCartonDetail();
                        $warehouse_carton_detail->warehouse_carton_id = $warehouse_carton->id;
                        $warehouse_carton_detail->color_id = $color_id;
                        $warehouse_carton_detail->size_id = $request->size_id[$key];
                        $warehouse_carton_detail->quantity = $request->quantity[$key];
                        $warehouse_carton_detail->save();
                    }
                }
            }

            DB::commit();
            $html = view('partials.flash_message', [
                'message_class' => "success",
                'message' => "Data updated successfully!!",
            ])->render();

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'message' => $html,
                'warehouse_carton_id' => $warehouse_carton->id,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            $html = view('partials.flash_message', [
                'message_class' => "danger",
                'message' => "Something went wrong!!",
            ])->render();

            return response()->json([
                'status' => 'danger',
                'errors' => null,
                'message' => $html,
            ]);
        }
    }

    public function show($id)
    {
        $warehouse_carton = WarehouseCarton::with('warehouseCartonDetails')->findOrFail($id);

        $colors = $warehouse_carton->warehouseCartonDetails->unique('color_id')->pluck('color.name')->implode(', ');
        $sizes = $warehouse_carton->warehouseCartonDetails->unique('size_id')->pluck('size.name')->implode(', ');

        return view('warehouse-management::pages.warehouse_carton_barcode', [
            'warehouse_carton' => $warehouse_carton,
            'colors' => $colors,
            'sizes' => $sizes,
        ]);
    }

    public function edit($id)
    {
        $warehouse_carton = WarehouseCarton::with('warehouseCartonDetails')->findOrFail($id);
        $buyers = Buyer::where('id', $warehouse_carton->buyer_id)->pluck('name', 'id');
        $orders = Order::where('id', $warehouse_carton->order_id)->pluck('style_name', 'id');
        $purchase_orders = PurchaseOrder::where('id', $warehouse_carton->purchase_order_id)->pluck('po_no', 'id');

        $colors = $this->getColorsByPurchaseOrder($warehouse_carton->purchase_order_id);
        $sizes = $this->getSizesByPurchaseOrder($warehouse_carton->purchase_order_id);

        return view('warehouse-management::forms.warehouse_carton', [
            'warehouse_carton' => $warehouse_carton,
            'buyers' => $buyers,
            'orders' => $orders,
            'purchase_orders' => $purchase_orders,
            'colors' => $colors,
            'sizes' => $sizes,
            'warehouse_carton_details' => $warehouse_carton->warehouseCartonDetails(),
        ]);
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'buyer_id' => 'required',
            'order_id' => 'required',
            'purchase_order_id' => 'required',
            'color_id.*' => 'required',
            'size_id.*' => 'required',
            'quantity.*' => 'required',
        ], [
            'buyer_id.required' => 'This field is required.',
            'order_id.required' => 'This field is required.',
            'purchase_order_id.required' => 'This field is required.',
            'color_id.*.required' => 'This field is required.',
            'size_id.*.required' => 'This field is required.',
            'quantity.*.required' => 'This field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ]);
        }

        try {
            DB::beginTransaction();
            $warehouse_carton = WarehouseCarton::findOrFail($id);

            if ($warehouse_carton->shipment_status) {
                $html = view('partials.flash_message', [
                    'message_class' => "danger",
                    'message' => "Cannot update this carton information because already shipped out!!",
                ])->render();

                return response()->json([
                    'status' => 'danger',
                    'errors' => null,
                    'message' => $html,
                ]);
            }

            if ($warehouse_carton->rack_allocation_status) {
                $html = view('partials.flash_message', [
                    'message_class' => "danger",
                    'message' => "Cannot update this carton information because already allocated to rack!!",
                ])->render();

                return response()->json([
                    'status' => 'danger',
                    'errors' => null,
                    'message' => $html,
                ]);
            }

            $color_ids = $request->get('color_id');
            $is_quantity_given = 0;
            $total_garments_qty = 0;
            if (count($color_ids)) {
                foreach ($color_ids as $key => $color_id) {
                    if ($request->quantity[$key]) {
                        $is_quantity_given = 1;
                        $total_garments_qty += $request->quantity[$key];
                    }
                }
            }

            if (! $is_quantity_given) {
                $html = view('partials.flash_message', [
                    'message_class' => "danger",
                    'message' => "At least one color quantity must be greater than zero!!",
                ])->render();

                return response()->json([
                    'status' => 'danger',
                    'errors' => null,
                    'message' => $html,
                ]);
            }

            $warehouse_carton->buyer_id = $request->buyer_id;
            $warehouse_carton->order_id = $request->order_id;
            $warehouse_carton->purchase_order_id = $request->purchase_order_id;
            $warehouse_carton->garments_qty = $total_garments_qty;
            $warehouse_carton->save();

            $color_ids = $request->get('color_id');
            if (count($color_ids)) {
                WarehouseCartonDetail::where('warehouse_carton_id', $id)->forceDelete();
                foreach ($color_ids as $key => $color_id) {
                    if ($request->quantity[$key] > 0) {
                        $warehouse_carton_detail = new WarehouseCartonDetail();
                        $warehouse_carton_detail->warehouse_carton_id = $warehouse_carton->id;
                        $warehouse_carton_detail->color_id = $color_id;
                        $warehouse_carton_detail->size_id = $request->size_id[$key];
                        $warehouse_carton_detail->quantity = $request->quantity[$key];
                        $warehouse_carton_detail->save();
                    }
                }
            }

            DB::commit();
            $html = view('partials.flash_message', [
                'message_class' => "success",
                'message' => "Data updated successfully!!",
            ])->render();

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'message' => $html,
                'warehouse_carton_id' => $warehouse_carton->id,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            $html = view('partials.flash_message', [
                'message_class' => "danger",
                'message' => "Something went wrong!!",
            ])->render();

            return response()->json([
                'status' => 'danger',
                'errors' => null,
                'message' => $html,
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            //If in rack_carton_positions table is free(null) of warehouse_carton_id then delete can occur
            $rack_carton_position_query = RackCartonPosition::where('warehouse_carton_id', $id);
            $is_carton_exists = 0;
            if ($rack_carton_position_query->count()) {
                foreach ($rack_carton_position_query->get() as $rack_carton_position) {
                    if ($rack_carton_position->warehouse_carton_id) {
                        $is_carton_exists = 1;

                        break;
                    }
                }
            }
            if ($is_carton_exists) {
                Session::flash('alert-danger', 'Cannot delete this carton because this carton exist in a rack!!');

                return redirect()->back();
            }
            $warehouse_carton = WarehouseCarton::findOrFail($id);
            $warehouse_carton->delete();
            DB::commit();
            Session::flash('alert-success', 'Data deleted successfully!!');

            return redirect('/warehouse-cartons');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('alert-danger', 'Something went wrong!! Try again!!');

            return redirect()->back();
        }
    }

    public function search(Request $request)
    {
        if ($request->q == '') {
            return redirect('/warehouse-cartons');
        }
        $warehouse_cartons = WarehouseCarton::withoutGlobalScope('factoryId')->with('buyer', 'order', 'purchaseOrder', 'warehouseCartonDetails')
            ->leftJoin('buyers', 'buyers.id', 'warehouse_cartons.buyer_id')
            ->leftJoin('orders', 'orders.id', 'warehouse_cartons.order_id')
            ->leftJoin('purchase_orders', 'purchase_orders.id', 'warehouse_cartons.purchase_order_id')
            ->where('warehouse_cartons.factory_id', factoryId())
            ->where(function ($query) use ($request) {
                $query->orWhere('buyers.name', 'like', '%' . $request->q . '%')
                    ->orWhere('orders.order_style_no', 'like', '%' . $request->q . '%')
                    ->orWhere('purchase_orders.po_no', 'like', '%' . $request->q . '%');
            })
            ->select('warehouse_cartons.*')
            ->orderBy('warehouse_cartons.id', 'DESC')->paginate();

        return view('warehouse-management::pages.warehouse_cartons', ['warehouse_cartons' => $warehouse_cartons, 'q' => $request->q]);
    }

    public function getSizesByPurchaseOrder($purchase_order_id)
    {
        return PurchaseOrderDetail::withoutGlobalScope('factoryId')
            ->join('sizes', 'purchase_order_details.size_id', 'sizes.id')
            ->where('purchase_order_details.purchase_order_id', $purchase_order_id)
            ->pluck('sizes.name', 'sizes.id')
            ->all();
    }

    public function getColorsByPurchaseOrder($purchase_order_id)
    {
        return PurchaseOrderDetail::withoutGlobalScope('factoryId')
            ->join('colors', 'purchase_order_details.color_id', 'colors.id')
            ->where('purchase_order_details.purchase_order_id', $purchase_order_id)
            ->pluck('colors.name', 'colors.id')
            ->all();
    }

    public function getPurchaseOrderDetailsForWarehouse($purchase_order_id)
    {
        $data['colors'] = $this->getColorsByPurchaseOrder($purchase_order_id);

        $data['sizes'] = $this->getSizesByPurchaseOrder($purchase_order_id);

        $data['po_size_breakdown_html'] = view('warehouse-management::forms.po_size_breakdown_form', [
            'colors' => $data['colors'],
            'sizes' => $data['sizes'],
            'warehouse_carton_details' => null,
        ])->render();

        return response()->json($data);
    }
}
