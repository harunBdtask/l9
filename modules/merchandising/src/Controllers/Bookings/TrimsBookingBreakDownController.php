<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Actions\TrimsBookingVirtualStockAction;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\Sensitivity;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingItemDetails;
use SkylarkSoft\GoRMG\Merchandising\Requests\Bookings\TrimsBookingRequest;
use Symfony\Component\HttpFoundation\Response;

class TrimsBookingBreakDownController extends Controller
{
    public function store(TrimsBookingRequest $request): JsonResponse
    {
        try {
            $details = collect($request->input('details'));

            $workOrderQty = $details->sum('wo_total_qty');
            $workOrderRate = $details->avg('rate');
            $workOrderAmount = $details->sum('amount');

            DB::beginTransaction();
            $bookingDetail = TrimsBookingDetails::query()->findOrFail($request->get('id'));

            TrimsBookingItemDetails::where([
                'booking_id' => $bookingDetail->booking_id,
                'budget_unique_id' => $bookingDetail->budget_unique_id,
                'item_id' => $bookingDetail->item_id,
            ])->delete();


            $bookingDetail->update([
                'sensitivity' => $request->get('sensitivity'),
                'details' => $request->get('details'),
                'work_order_qty' => $workOrderQty,
                'work_order_rate' => $workOrderRate,
                'work_order_amount' => $workOrderAmount,
            ]);
            $this->saveBalance($request);

            DB::commit();

            $response = [
                'message' => 'Success',
                'status' => Response::HTTP_OK,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function saveBalance($request): bool
    {
        $sensitivity = $request->get('sensitivity');
        $details = $request->input('details');
        $virtualStockAction = new TrimsBookingVirtualStockAction();

        $headers = [
            'booking_id' => request('bookingId'),
            'item_id' => request('itemId'),
        ];
        if (in_array($sensitivity, [Sensitivity::AS_GARMENTS_PER_COLOR, Sensitivity::CONTRAST_COLOR])) {

            foreach ($details as $detail) {
                $itemDetail = $this->getItemDetail(['color_id' => $detail['color_id']]);
                $itemDetail->qty = $detail['wo_qty'];
                $itemDetail->save();

                $stockDetail = collect($detail)->only([
                    'item_color',
                    'item_description',
                    'item_size',
                    'stock',
                    'wo_qty',
                    'moq_qty',
                    'avl_stock_qty'
                ])->merge($headers)->toArray();

                $virtualStockAction->handle($stockDetail);
            }

            return true;
        }

        if (in_array($sensitivity, [Sensitivity::SIZE_SENSITIVE, Sensitivity::COLOR_N_SIZE_SENSITIVE])) {
            $newItemDetails = [];
            foreach ($details as $detail) {
                $itemDetail = $this->getItemDetail(['color_id' => $detail['color_id'], 'size_id' => $detail['size_id']]);
                $itemDetail->qty = $detail['wo_qty'];
                $newItemDetails[] = [
                    'booking_id' => $itemDetail['booking_id'],
                    'budget_unique_id' => $itemDetail['budget_unique_id'],
                    'item_id' => $itemDetail['item_id'],
                    'color_id' => $itemDetail['color_id'],
                    'size_id' => $itemDetail['size_id'],
                    'qty' => $detail['wo_qty'],
                    'po_no' => $itemDetail['po_no'],
                    'factory_id' => factoryId(),
                ];

                $stockDetail = collect($detail)->only([
                    'item_color',
                    'item_description',
                    'item_size',
                    'stock',
                    'wo_qty',
                    'moq_qty',
                    'avl_stock_qty'
                ])->merge($headers)->toArray();

                $virtualStockAction->handle($stockDetail);

            }
            TrimsBookingItemDetails::query()->insert($newItemDetails);
            return true;
        }

        foreach ($details as $detail) {
            $itemDetail = $this->getItemDetail();
            $itemDetail->qty = $detail['wo_qty'];
            $itemDetail->save();

            $stockDetail = collect($detail)->only([
                'item_color',
                'item_description',
                'item_size',
                'stock',
                'wo_qty',
                'moq_qty',
                'avl_stock_qty'
            ])->merge($headers)->toArray();

            $virtualStockAction->handle($stockDetail);
        }

        return true;
    }

    private function getItemDetail($criteria = [])
    {
        return TrimsBookingItemDetails::firstOrNew(array_merge([
            'booking_id' => request('bookingId'),
            'budget_unique_id' => request('budgetUniqueId'),
            'item_id' => request('itemId'),
            'po_no' => request('poNo'),
        ], $criteria));
    }
}
