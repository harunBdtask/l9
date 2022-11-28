<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubDyeingGoodsDelivery;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingGoodsDeliveryDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubDryerSearchRequest;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\States\DetailsStates\DetailsState;
use Symfony\Component\HttpFoundation\Response;

class SubDyeingGoodsDeliverySearchController extends Controller
{
    /**
     * @param SubDryerSearchRequest $request
     * @return JsonResponse
     */
    public function __invoke(SubDryerSearchRequest $request): JsonResponse
    {
        try {
            $searchState = DetailsState::setState($request->input('type'));
            $goodsDeliveryDetails = $searchState->handle($request);

            foreach ($goodsDeliveryDetails as $key => $detail) {
                $prevQty = SubDyeingGoodsDeliveryDetail::query()
                    ->selectRaw('SUM(total_roll) AS totalRoll,SUM(delivery_qty) AS totalDeliveryQty')
                    ->when($detail['sub_textile_order_details_id'], Filter::applyFilter('order_details_id', $detail['sub_textile_order_details_id']))
                    ->when($detail['sub_dyeing_batch_details_id'], Filter::applyFilter('batch_details_id', $detail['sub_dyeing_batch_details_id']))
                    ->first();

                $goodsDeliveryDetails[$key] = $detail + [
                        'batch_id' => $detail['sub_dyeing_batch_id'],
                        'batch_no' => $detail['sub_dyeing_batch_no'],
                        'batch_details_id' => $detail['sub_dyeing_batch_details_id'] ?? null,
                        'order_id' => $detail['sub_textile_order_id'],
                        'order_no' => $detail['sub_textile_order_no'],
                        'order_details_id' => $detail['sub_textile_order_details_id'] ?? null,
                        'prev_total_roll' => $prevQty->totalRoll,
                        'prev_delivery_qty' => $prevQty->totalDeliveryQty,
                        'total_roll' => 0,
                        'delivery_qty' => 0,
                        'rate' => 0,
                        'total_value' => 0,
                        'shade' => null,
                        'reject_roll' => 0,
                        'reject_qty' => 0,
                        'remarks' => null,
                    ];
            }

            return response()->json([
                'data' => $goodsDeliveryDetails,
                'message' => 'Details fetched successfully',
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
