<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsInventory;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventoryDetail;
use SkylarkSoft\GoRMG\Inventory\Services\States\TrimsItemState\ItemFormatterState;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use Symfony\Component\HttpFoundation\Response;

class TrimsBookingDetailsApiController extends Controller
{
    public function __invoke(
        Request            $request,
        TrimsBooking       $trimsBooking,
        ItemFormatterState $state
    ): JsonResponse {
        try {
            $trimsInventoryId = $request->query('trims_inventory_id');
            $trimsBookingDetailsId = [];

            if (isset($trimsInventoryId)) {
                $trimsBookingDetailsId = TrimsInventoryDetail::query()
                    ->when($trimsInventoryId, function (Builder $query) use ($trimsInventoryId) {
                        $query->where('trims_inventory_id', $trimsInventoryId);
                    })
                    ->groupBy('trims_booking_detail_id')
                    ->get()->pluck('trims_booking_detail_id');
            }

            $trimsBooking->load('details');

            $trimsInventoryDetails = $trimsBooking->getRelation('details')
                ->when(count($trimsBookingDetailsId), function ($query) use ($trimsBookingDetailsId) {
                    return $query->whereNotIn('id', $trimsBookingDetailsId);
                })->values()->map(function ($detail) use ($state) {
                    $formatter = $state->setState($detail['sensitivity']);

                    return [
                        'booking_id' => $detail['booking_id'],
                        'budget_unique_id' => $detail['budget_unique_id'],
                        'booking_detail_id' => $detail['id'],
                        'style_name' => $detail['style_name'],
                        'po_no' => $detail['po_no'],
                        'sensitivity' => $detail['sensitivity'],
                        'sensitivity_value' => $detail['sensitivity']
                            ? TrimsBookingDetails::SENSITIVITY[$detail['sensitivity']]
                            : null,
                        'item_id' => $detail['item_id'],
                        'item_name' => $detail['item_name'],
                        'uom_value' => $detail['cons_uom_value'],
                        'item_description' => $detail['item_description'],
                        'work_order_qty' => $detail['work_order_qty'],
                        'work_order_rate' => $detail['work_order_rate'],
                        'work_order_amount' => $detail['work_order_amount'],
                        'details' => $formatter->format($detail),
                    ];
                });

            return response()->json([
                'message' => 'Fetch trims booking details successfully',
                'data' => $trimsInventoryDetails,
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
