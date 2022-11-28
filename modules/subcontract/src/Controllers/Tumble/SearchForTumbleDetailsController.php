<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Tumble;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingTumble\SubDyeingTumbleDetail;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\States\DetailsStates\DetailsState;

class SearchForTumbleDetailsController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        $searchState = DetailsState::setState($request->input('type'));
        $tumbleDetails = $searchState->handle($request);

        foreach ($tumbleDetails as $key => $detail) {
            $prevQty = SubDyeingTumbleDetail::query()
                ->selectRaw('SUM(no_of_roll) AS total_no_of_roll,SUM(finish_qty) AS total_finish_qty')
                ->when(
                    $detail['sub_textile_order_details_id'],
                    Filter::applyFilter('sub_textile_order_details_id', $detail['sub_textile_order_details_id'])
                )
                ->when(
                    $detail['sub_dyeing_batch_details_id'],
                    Filter::applyFilter('sub_dyeing_batch_details_id', $detail['sub_dyeing_batch_details_id'])
                )
                ->first();

            $tumbleDetails[$key] = array_merge($detail, [
                'prev_no_of_roll' => $prevQty->total_no_of_roll,
                'no_of_roll' => null,
                'prev_finish_qty' => $prevQty->total_finish_qty,
                'finish_qty' => null,
                'reject_roll' => null,
                'reject_qty' => null,
                'unit_cost' => 0,
                'total_cost' => null,
            ]);
        }

        return $tumbleDetails;
    }
}
