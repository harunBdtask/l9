<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\WorkOrders;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\Sensitivity;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentBookingItemDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentWorkOrderDetails;
use SkylarkSoft\GoRMG\Merchandising\Requests\WorkOrders\EmbellishmentWorkOrderDetailsRequest;

class EmbellishmentWorkOrderBreakDownController extends Controller
{
    public function store(EmbellishmentWorkOrderDetailsRequest $request): JsonResponse
    {
        try {
            $details = collect($request->input('details'));

            $workOrderQty = $details->sum('wo_qty');
            $workOrderRate = $details->avg('rate');
            $workOrderAmount = $details->sum('amount');
            DB::beginTransaction();
            $bookingDetail = EmbellishmentWorkOrderDetails::findOrFail($request->get('id'));

            EmbellishmentBookingItemDetails::query()
                ->where('embellishment_work_order_id', $bookingDetail->embellishment_work_order_id)
                ->where('item_id', $bookingDetail->embellishment_id)
                ->delete();

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

    private function saveBalance($request): void
    {
        $sensitivity = $request->get('sensitivity');
        $details = $request->input('details');

        if (in_array($sensitivity, [Sensitivity::AS_GARMENTS_PER_COLOR, Sensitivity::CONTRAST_COLOR])) {
            foreach ($details as $detail) {
                $itemDetail = $this->getItemDetail(['color_id' => $detail['color_id']]);
                $itemDetail->qty = $detail['wo_qty'];
                $itemDetail->save();
            }

            return;
        }

        if (in_array($sensitivity, [Sensitivity::SIZE_SENSITIVE, Sensitivity::COLOR_N_SIZE_SENSITIVE])) {
            foreach ($details as $detail) {
                $itemDetail = $this->getItemDetail(['color_id' => $detail['color_id'], 'size_id' => $detail['size_id']]);
                $itemDetail->qty = $detail['wo_qty'];
                $itemDetail->save();
            }

            return;
        }

        foreach ($details as $detail) {
            $itemDetail = $this->getItemDetail();
            $itemDetail->qty = $detail['wo_qty'];
            $itemDetail->save();
        }
    }

    private function getItemDetail($criteria = [])
    {
        return EmbellishmentBookingItemDetails::firstOrNew(array_merge([
            'embellishment_work_order_id' => request('workOrderId'),
            'budget_unique_id' => request('budgetUniqueId'),
            'item_id' => request('itemId'),
        ], $criteria));
    }
}
