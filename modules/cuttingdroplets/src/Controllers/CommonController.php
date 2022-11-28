<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;
use Session, DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use Symfony\Component\HttpFoundation\Response;

class CommonController extends Controller
{

    public function lotsByOrderAndColor($orderId, $colorId)
    {
        if (\Request::ajax()) {
            $lots = Lot::where('order_id', $orderId)->where('color_id', $colorId)->get();

            return view('partials.lot_options', ['lots' => $lots]);
        }

        return abort(404);
    }

    public function lastCuttingNo(Request $request)
    {
        if (\Request::ajax()) {
            $orderId = $request->get('order_id');
            $lotIds = $request->get('lot_id');

            $cuttingNos = BundleCard::getLastCuttingNo($orderId, $lotIds);

            return view('cuttingdroplets::forms._cutting_no', [
                'cuttingNos' => $cuttingNos
            ]);
        }

        return abort(404);
    }

    public function orders(Request $request)
    {
        if (\Request::ajax()) {
            $orders = Order::where($request->except('_token'))->get();
            $booking_no_view = view('partials.booking_no_options', ['orders' => $orders])->render();
            $order_view = view('partials.order_options', ['orders' => $orders])->render();

            return response()->json([
                'booking_no_view' => $booking_no_view,
                'order_view' => $order_view
            ]);
        }

        return abort(404);
    }

    public function garmentsItems(Request $request)
    {
        if ($request->ajax()) {
            $order_id = $request->order_id ?? null;
            $order = Order::query()->find($order_id);
            if ($order) {
                $item_details = $order->item_details && is_array($order->item_details) && array_key_exists('details', $order->item_details) ? $order->item_details['details'] : null;
                $garment_item_ids = $item_details && is_array($item_details) ? collect($item_details)->pluck('item_id')->toArray() : [];
                $garment_items = $garment_item_ids && count($garment_item_ids) ? GarmentsItem::query()->whereIn('id', $garment_item_ids)->pluck('name', 'id') : null;
            }
            $item_view = view('partials.garments_items_options', ['garment_items' => $garment_items ?? null])->render();

            return response()->json([
                'item_view' => $item_view,
            ]);
        }

        return abort(404);
    }

    public function sizes(Request $request)
    {
        if (\Request::ajax()) {
            $purchaseOrderIds = $request->get('purchase_order_ids');
            $garmentsItemId = $request->get('garments_item_id') ?? null;
            $sizes = PurchaseOrderDetail::getSizes($purchaseOrderIds, $garmentsItemId);

            return view('partials.size_options', ['sizes' => $sizes]);
        }

        return abort(404);
    }

    public function lots(Request $request)
    {
        if (\Request::ajax() && $request->has('order_ids')) {
            $orderIds = $request->get('order_ids');
            $lots = Order::with('lots.color')->whereIn('id', $orderIds)
                ->get()
                ->map(function ($item) use ($request) {
                    if ($request->has('color_id')) {
                        return $item->lots->where('color_id', $request->get('color_id'));
                    }

                    return $item->lots;
                })
                ->flatten()
                ->unique('id')
                ->values()
                ->all();

            return view('partials.lot_options', ['lots' => $lots]);
        } else if (\Request::ajax() && $request->has('order_id')) {
            $orderId = $request->get('order_id');

            $lots = Lot::with('color:id,name')
                ->where('order_id', $orderId)
                ->get();

            return view('partials.lot_options', ['lots' => $lots]);
        }

        return abort(404);
    }

    public function getStyleByBuyer(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404);
        }
        try {
            $buyer_id = $request->buyer_id ?? null;
            $styles = $buyer_id ? Order::query()
            ->selectRaw("id, style_name, reference_no")
            ->where('buyer_id', $buyer_id)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'style_name' => $order->style_name,
                    'reference_no' => $order->reference_no,
                ];
            }) : [];
            
            return response()->json([
                'data' => $styles,
                'status' => 200,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'status' => 500,
                'errors' => $e->getMessage(),
            ]);
        }
    }

    public function purchaseOrdersByOrder($orderId)
    {
        if (request()->ajax()) {
            $purchaseOrders = PurchaseOrder::where('order_id', $orderId)->get();
            return view('partials.purchase_order_options', ['purchaseOrders' => $purchaseOrders]);
        }
        return abort(404);
    }

    public function utilityColors(Request $request)
    {
        if ($request->ajax()) {
            $order_id = $request->order_id ?? null;
            $colors = PurchaseOrder::with(['colors' => function ($query) {
                $query->withoutGlobalScope('factoryId');
            }])
                ->where('order_id', $order_id)
                ->get()
                ->map(function ($item) use ($request) {
                    return $item->colors;
                })
                ->flatten()
                ->unique('id')
                ->values()
                ->all();
            return view('partials.color_options', ['colors' => $colors]);
        }
        return abort(404);
    }

    public function getColors($purchaseOrderIds, Request $request)
    {
        if ($request->ajax()) {
            if (!is_array($purchaseOrderIds)) {
                $purchaseOrderIds = (array)$purchaseOrderIds;
            }

            return PurchaseOrderDetail::with('color:id,name')
                ->join('colors', 'purchase_order_details.color_id', 'colors.id')
                ->whereIn('purchase_order_details.purchase_order_id', $purchaseOrderIds)
                ->pluck('colors.name', 'colors.id')->all();
        }
        return abort(404);
    }

    public function purchaseOrder(Request $request)
    {
        if (request()->ajax()) {
            $purchaseOrders = PurchaseOrder::query()
                ->with('purchaseOrderDetails')
                ->where('order_id', $request->order_id)
                ->get()
                ->filter(function ($item, $key) use ($request) {
                    if ($request->has('color_id')) {
                        return $item->purchaseOrderDetails->contains('color_id', $request->color_id);
                    }
                    return true;
                })
                ->all();
            return view('partials.purchase_order_options', ['purchaseOrders' => $purchaseOrders]);
        }
        return abort(404);
    }

    public function itemWisePurchaseOrders(Request $request)
    {
        if (request()->ajax()) {
            $order_id = $request->order_id ?? null;
            $garments_item_id = $request->garments_item_id ?? null;
            if ($order_id && $garments_item_id) {
                $purchaseOrders = PurchaseOrder::query()
                    ->whereRaw("id IN (SELECT purchase_order_id FROM po_item_color_size_details WHERE order_id = $order_id AND garments_item_id = $garments_item_id)")
                    ->get();
            }
            return view('partials.purchase_order_options', ['purchaseOrders' => $purchaseOrders ?? null]);
        }
        return abort(404);
    }

    public function colorsByOrder(Request $request)
    {
        if (request()->ajax()) {
            $purchaseOrderId = $request->purchase_order_ids;
            if (is_array($purchaseOrderId)) {
                $sqlQuery = PurchaseOrderDetail::where('quantity', '>', 0)
                    ->with('color:id,name')
                    ->whereIn('purchase_order_id', $purchaseOrderId);
            } else {
                $sqlQuery = PurchaseOrderDetail::where('quantity', '>', 0)
                    ->with('color:id,name')
                    ->where('purchase_order_id', $purchaseOrderId);
            }
            $colors = $sqlQuery->get()->map(function ($item) {
                return $item->color;
            })->unique('id')->values()->all();
            return view('partials.color_options', ['colors' => $colors]);
        }
        return abort(404);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getStylesForSelectSearch(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404);
        }
        $search_query = $request->search ?? null;
        $buyer_id = $request->buyer_id ?? null;
        $booking_nos = Order::query()
            ->selectRaw("id, style_name")
            ->when($buyer_id, function ($query) use ($buyer_id) {
                $query->where('buyer_id', $buyer_id);
            })
            ->when($search_query, function ($query) use ($search_query) {
                $query->where('style_name', 'like', $search_query . '%');
            })
            ->limit(50)
            ->get()
            ->map(function ($item) {
                $items['id'] = $item->id;
                $items['text'] = $item->style_name;
                return $items;
            });
        return response()->json([
            'results' => $booking_nos,
        ]);
    }

    public function getOrdersWithBookingNo($buyer_id)
    {
        $orders = Order::query()
            ->where('buyer_id', $buyer_id)
            ->get();
        return response()->json([
            'data' => $orders,
        ]);
    }

    public function getStylesByBuyer($buyer_id)
    {
        $booking_nos = Order::query()
            ->where('buyer_id', $buyer_id)
            ->pluck('style_name', 'id');

        return response()->json([
            'data' => $booking_nos,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getItemsForSelectSearch(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404);
        }

        $order_id = $request->get('order_id') ?? null;
        $order = Order::query()->find($order_id);

        if ($order) {
            $item_details = $order->item_details && is_array($order->item_details) && array_key_exists('details', $order->item_details) ? $order->item_details['details'] : null;
            $garment_item_ids = $item_details && is_array($item_details) ? collect($item_details)->pluck('item_id')->toArray() : [];
            $garment_items = $garment_item_ids && count($garment_item_ids) ? GarmentsItem::query()->whereIn('id', $garment_item_ids)->select('name as text', 'id')->get() : null;
        }

        return response()->json([
            'results' => $garment_items ?? [],
        ]);
    }

    public function getItemsByOrder($order_id)
    {

        $order = Order::query()->find($order_id);

        if ($order) {
            $item_details = $order->item_details && is_array($order->item_details) && array_key_exists('details', $order->item_details) ? $order->item_details['details'] : null;
            $garment_item_ids = $item_details && is_array($item_details) ? collect($item_details)->pluck('item_id')->toArray() : [];
            $garment_items = $garment_item_ids && count($garment_item_ids) ? GarmentsItem::query()->whereIn('id', $garment_item_ids)->pluck('name', 'id') : null;
        }

        return response()->json([
            'data' => $garment_items ?? [],
        ]);
    }

    public function getOrderItemSmv(Request $request)
    {
        try {
            $order_id = $request->order_id ?? null;
            $garments_item_id = $request->item_id ?? null;
            if ($order_id && $garments_item_id) {
                $data = Order::getOrderItemWiseFactorySMV($order_id, $garments_item_id) ?? Order::getOrderItemWiseSMV($order_id, $garments_item_id);
            }
            $status = Response::HTTP_OK;
            $message = \SUCCESS_MSG;
        } catch (Exception $e) {
            $error = $e->getMessage();
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = \SOMETHING_WENT_WRONG;
        }

        return response()->json([
            'data' => $data ?? null,
            'status' => $status,
            'message' => $message,
            'error' => $error ?? null,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getBuyersForSelectSearch(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404);
        }
        $search_query = $request->search;
        $buyers = Buyer::query()
            ->when($search_query, function ($q) use ($search_query) {
                return $q->where('name', 'LIKE', '%' . $search_query . '%');
            })
            ->limit(20)
            ->get([
                'id',
                'name as text'
            ])
            ->all();

        return response()->json([
            'results' => $buyers,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPosForSelectSearch(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404);
        }

        $order_id = $request->order_id ?? null;
        $garments_item_id = $request->item_id ?? null;
        $search = $request->search ?? null;

        $pos = PurchaseOrder::query()
            ->when($search, function ($query) use ($search, $order_id) {
                $query->where('po_no', 'like', $search . '%')
                    ->when($order_id, function ($query) use ($order_id) {
                        $query->where('order_id', $order_id);
                    });
            })
            ->when(!$search && $order_id && $garments_item_id, function ($query) use ($order_id, $garments_item_id) {
                $query->whereRaw("id IN (SELECT purchase_order_id FROM po_item_color_size_details WHERE order_id = $order_id AND garments_item_id = $garments_item_id)");
            })
            ->when(!$search && $order_id && !$garments_item_id, function ($query) use ($order_id) {
                $query->where('order_id', $order_id);
            })
            ->limit(20)
            ->get([
                'id',
                'po_no as text'
            ])
            ->all();

        return response()->json([
            'results' => $pos,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getColorsForPosSelectSearch(Request $request): JsonResponse
    {
        if (!$request->ajax()) {
            return abort(404);
        }
        $order_id = $request->order_id ?? null;
        $garments_item_id = $request->garments_item_id ?? null;
        $purchase_order_id = $request->purchase_order_id ?? null;
        $colors = [];
        if ($purchase_order_id) {
            $color_array = PurchaseOrderDetail::getColors($purchase_order_id, false, $order_id, $garments_item_id,);
            foreach ($color_array as $key => $val) {
                $colors[] = [
                    'id' => $key,
                    'text' => $val,
                ];
            }
        }

        return response()->json([
            'results' => $colors,
        ]);
    }
}
