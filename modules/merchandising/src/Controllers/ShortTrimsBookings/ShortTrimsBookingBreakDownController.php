<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\ShortTrimsBookings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\Sensitivity;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBookingItemDetails;

class ShortTrimsBookingBreakDownController extends Controller
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $details = collect($request->input('details'));

            $workOrderQty = $details->sum('wo_total_qty');
            $workOrderRate = $details->avg('rate');
            $workOrderAmount = $details->sum('amount');

            DB::beginTransaction();
            $bookingDetail = ShortTrimsBookingDetails::findOrFail($request->get('id'));

            ShortTrimsBookingItemDetails::where([
                'short_booking_id' => $bookingDetail->short_booking_id,
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

        if (in_array($sensitivity, [Sensitivity::AS_GARMENTS_PER_COLOR, Sensitivity::CONTRAST_COLOR])) {
            foreach ($details as $detail) {
                $itemDetail = $this->getItemDetail(['color_id' => $detail['color_id']]);
                $itemDetail->qty = $detail['wo_qty'];
                $itemDetail->save();
            }

            return true;
        }

        if (in_array($sensitivity, [Sensitivity::SIZE_SENSITIVE, Sensitivity::COLOR_N_SIZE_SENSITIVE])) {

            $newShortItemDetails = [];

            foreach ($details as $detail) {
                $itemDetail = $this->getItemDetail(['color_id' => $detail['color_id'], 'size_id' => $detail['size_id']]);
                $itemDetail->qty = $detail['wo_qty'];
                $newShortItemDetails[] = [
                    'short_booking_id' => $itemDetail['short_booking_id'],
                    'budget_unique_id' => $itemDetail['budget_unique_id'],
                    'item_id' => $itemDetail['item_id'],
                    'color_id' => $itemDetail['color_id'],
                    'size_id' => $itemDetail['size_id'],
                    'qty' => $detail['wo_qty'],
                    'factory_id' => factoryId(),
                ];
            }
            ShortTrimsBookingItemDetails::query()->insert($newShortItemDetails);
            return true;
        }

        foreach ($details as $detail) {
            $itemDetail = $this->getItemDetail();
            $itemDetail->qty = $detail['wo_qty'];
            $itemDetail->save();
        }

        return true;
    }

    private function getItemDetail($criteria = [])
    {
        return ShortTrimsBookingItemDetails::firstOrNew(array_merge([
            'short_booking_id' => request('shortBookingId'),
            'budget_unique_id' => request('budgetUniqueId'),
            'item_id' => request('itemId'),
        ], $criteria));
    }
}
