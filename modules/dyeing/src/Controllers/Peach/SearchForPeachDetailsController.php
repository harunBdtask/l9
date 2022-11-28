<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Peach;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Requests\Peach\PeachSearchRequest;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Peach\PeachDetail;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\States\DetailsStates\DetailsState;

class SearchForPeachDetailsController extends Controller
{

    /**
     * @param PeachSearchRequest $request
     * @return JsonResponse
     */
    public function __invoke(PeachSearchRequest $request): JsonResponse
    {
        try {
            $searchState = DetailsState::setState($request->input('type'));
            $peachDetails = $searchState->handle($request);

            foreach ($peachDetails as $key => $detail) {

                $prevQty = PeachDetail::query()
                    ->selectRaw('SUM(no_of_roll) AS total_no_of_roll,SUM(finish_qty) AS total_finish_qty')
                    ->when($detail['textile_order_details_id'],
                        Filter::applyFilter('textile_order_details_id', $detail['textile_order_details_id']))
                    ->when($detail['dyeing_batch_details_id'],
                        Filter::applyFilter('dyeing_batch_details_id', $detail['dyeing_batch_details_id']))
                    ->first();

                $peachDetails[$key] = array_merge($detail, [
                    'prev_no_of_roll' => $prevQty->total_no_of_roll,
                    'no_of_roll' => null,
                    'prev_finish_qty' => $prevQty->total_finish_qty,
                    'finish_qty' => null,
                    'reject_roll' => null,
                    'reject_qty' => null,
                    'unit_cost' => null,
                    'total_cost' => null,
                ]);
            }

            return response()->json([
                'message' => 'Fetch peach details successfully',
                'data' => $peachDetails,
                'status' => Response::HTTP_OK,
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
