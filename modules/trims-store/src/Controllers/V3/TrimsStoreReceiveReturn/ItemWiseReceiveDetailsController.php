<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreReceiveReturn;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceive\TrimsStoreReceiveDetail;
use SkylarkSoft\GoRMG\TrimsStore\Services\V3\StockSummaryService;
use Symfony\Component\HttpFoundation\Response;

class ItemWiseReceiveDetailsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $factoryId = $request->query('factory_id');
            $buyerId = $request->query('buyer_id');
            $styleId = $request->query('style_id');
            $garmentItemId = $request->query('garments_item_id');
            $itemId = $request->query('item_id');
            $supplierId = $request->query('supplier_id');

            $itemWiseReceiveDetails = TrimsStoreReceiveDetail::query()
                ->with([
                    'trimsStoreReceive', 'currency', 'uom', 'color',
                    'floor', 'room', 'rack', 'shelf', 'bin',
                ])
                ->where('factory_id', $factoryId)
                ->where('buyer_id', $buyerId)
                ->where('style_id', $styleId)
                ->where('garments_item_id', $garmentItemId)
                ->where('item_id', $itemId)
                ->get()->map(function ($detail) use ($factoryId, $supplierId) {
                    $stockSummaryService = StockSummaryService::setCriteria($detail);

                    return [
                        'trims_store_receive_return_id' => $detail['id'],
                        'factory_id' => $factoryId,
                        'sensitivity_id' => $detail['sensitivity_id'],
                        'buyer_id' => $detail['buyer_id'],
                        'brand_name' => $detail['brand_name'],
                        'style_id' => $detail['style_id'],
                        'item_id' => $detail['item_id'],
                        'garments_item_id' => $detail['garments_item_id'],
                        'garments_item_name' => $detail['garments_item_name'],
                        'item_description' => $detail['item_description'],
                        'item_code' => $detail['item_code'],
                        'color_id' => $detail['color_id'],
                        'color' => $detail['color']['name'],
                        'size_id' => $detail['size_id'],
                        'order_qty' => $detail['order_qty'],
                        'wo_qty' => $detail['wo_qty'],
                        'receive_return_qty' => null,
                        'uom_id' => $detail['uom_id'],
                        'uom_name' => $detail['uom']['unit_of_measurement'],
                        'currency_id' => $detail['currency_id'],
                        'currency_name' => $detail['currency']['currency_name'],
                        'rate' => $detail['rate'],
                        'exchange_rate' => $detail['exchange_rate'],
                        'amount' => null,
                        'floor_id' => $detail['floor_id'],
                        'floor_name' => $detail['floor']['name'],
                        'room_id' => $detail['room_id'],
                        'room_name' => $detail['room']['name'],
                        'rack_id' => $detail['rack_id'],
                        'rack_name' => $detail['rack']['name'],
                        'shelf_id' => $detail['shelf_id'],
                        'shelf_name' => $detail['shelf']['name'],
                        'bin_id' => $detail['bin_id'],
                        'bin_name' => $detail['bin']['name'],
                        'remarks' => null,
                        'receive_no' => $detail['trimsStoreReceive']['unique_id'],
                        'challan_no' => $detail['trimsStoreReceive']['challan_no'],
                        'receive_date' => $detail['trimsStoreReceive']['receive_date'],
                        'receive_qty' => $detail['receive_qty'],
                        'po_numbers' => $detail['po_numbers'],
                        'supplier_id' => $supplierId ?? $detail['supplier_id'],
                        'total_rcv_qty' => $stockSummaryService->computeReceiveQty(),
                        'prev_rcv_return_qty' => $stockSummaryService->getStockSummary()['receive_return_qty'] ?? 0,
                        'transaction_date' => $detail['transaction_date'],
                        'booking_id' => $detail['booking_id'],
                        'booking_no' => $detail['booking_no'],
                        'pi_numbers' => $detail['trimsStoreReceive']['pi_numbers'],
                    ];
                });

            return response()->json([
                'message' => 'Fetch trims store receive details successfully',
                'data' => $itemWiseReceiveDetails,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
