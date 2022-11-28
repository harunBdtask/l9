<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\SubDyeingHtSet;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingHtSetDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubDyeingHtSetDetailSearchRequest;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\States\DetailsStates\DetailsState;
use Symfony\Component\HttpFoundation\Response;

class SubDyeingHtSetDetailSearchController extends Controller
{
    /**
     * @param SubDyeingHtSetDetailSearchRequest $request
     * @return JsonResponse
     */
    public function __invoke(SubDyeingHtSetDetailSearchRequest $request): JsonResponse
    {
        try {
            $searchState = DetailsState::setState($request->input('type'));
            $htSetDetails = $searchState->handle($request);

            foreach ($htSetDetails as $key => $detail) {
                $prevQty = SubDyeingHtSetDetail::query()
                    ->selectRaw('SUM(fin_no_of_roll) AS totalFinRoll,SUM(finish_qty) AS totalFinQty')
                    ->when($detail['sub_textile_order_details_id'], Filter::applyFilter('order_details_id', $detail['sub_textile_order_details_id']))
                    ->when($detail['sub_dyeing_batch_details_id'], Filter::applyFilter('batch_details_id', $detail['sub_dyeing_batch_details_id']))
                    ->first();

                $htSetDetails[$key] = array_merge($detail, [
                    'batch_id' => $detail['sub_dyeing_batch_id'],
                    'batch_no' => $detail['sub_dyeing_batch_no'],
                    'batch_details_id' => $detail['sub_dyeing_batch_details_id'] ?? null,
                    'order_id' => $detail['sub_textile_order_id'],
                    'order_no' => $detail['sub_textile_order_no'],
                    'order_details_id' => $detail['sub_textile_order_details_id'] ?? null,
                    'prev_fin_no_of_roll' => $prevQty->totalFinRoll,
                    'prev_finish_qty' => $prevQty->totalFinQty,
                    'production_date' => null,
                    'fin_no_of_roll' => null,
                    'finish_qty' => null,
                    'reject_roll_qty' => null,
                    'reject_qty' => null,
                    'total_finish_qty' => null,
                    'unit_cost' => 0,
                    'total_cost' => null,
                ]);
            }

            return response()->json([
                'data' => $htSetDetails,
                'message' => 'Details fetched successfully',
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
