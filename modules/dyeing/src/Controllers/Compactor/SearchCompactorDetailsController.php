<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Compactor;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Requests\Compactor\CompactorSearchRequest;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Compactor\CompactorDetail;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\States\DetailsStates\DetailsState;

class SearchCompactorDetailsController extends Controller
{

    /**
     * @param CompactorSearchRequest $request
     * @return JsonResponse
     */
    public function __invoke(CompactorSearchRequest $request): JsonResponse
    {
        try {
            $searchState = DetailsState::setState($request->input('type'));
            $compactorDetails = $searchState->handle($request);

            foreach ($compactorDetails as $key => $detail) {
                $prevQty = CompactorDetail::query()
                    ->selectRaw('SUM(fin_no_of_roll) AS total_fin_roll,SUM(finish_qty) AS total_fin_qty')
                    ->when($detail['textile_order_details_id'],
                        Filter::applyFilter('textile_order_details_id', $detail['textile_order_details_id']))
                    ->when($detail['dyeing_batch_details_id'],
                        Filter::applyFilter('dyeing_batch_details_id', $detail['dyeing_batch_details_id']))
                    ->first();

                $compactorDetails[$key] = $detail + [
                        'prev_fin_no_of_roll' => $prevQty->total_fin_roll,
                        'prev_finish_qty' => $prevQty->total_fin_qty,
                        'fin_no_of_roll' => null,
                        'finish_qty' => null,
                        'req_order_qty' => $detail['batch_qty'] ?? $detail['order_qty'],
                        'reject_roll_qty' => null,
                        'reject_qty' => null,
                        'total_finish_qty' => null,
                        'unit_cost' => null,
                        'total_cost' => null,
                    ];
            }

            return response()->json([
                'data' => $compactorDetails,
                'message' => 'Compactor details fetched successfully',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
