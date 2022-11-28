<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Stentering;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Stentering\StenteringDetail;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\States\DetailsStates\DetailsState;

class SearchStenteringDetailsController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */

    public function __invoke(Request $request)
    {
        $searchType = $request->input('type');
        $searchState = DetailsState::setState($searchType);
        $stenteringDetails = $searchState->handle($request);


        foreach ($stenteringDetails as $key => $detail) {
            $prevQty = StenteringDetail::query()
                ->selectRaw('SUM(fin_no_of_roll) As total_no_roll,SUM(finish_qty) As total_finish_qty')
                ->when($detail['textile_order_details_id'],
                    Filter::applyFilter('textile_order_details_id', $detail['textile_order_details_id']))
                ->when($detail['dyeing_batch_details_id'],
                    Filter::applyFilter('dyeing_batch_details_id', $detail['dyeing_batch_details_id']))
                ->first();

            $stenteringDetails[$key] = array_merge($detail, [
                'prev_no_of_roll' => $prevQty->total_no_roll ?? 0,
                'fin_no_of_roll' => null,
                'prev_finish_qty' => $prevQty->total_finish_qty ?? 0,
                'finish_qty' => null,
                'reject_roll' => null,
                'reject_qty' => null,
                'unit_cost' => null,
                'total_cost' => null,
                'req_order_qty' => $searchType == 1 ? $detail['batch_qty'] : $detail['order_qty'],
            ]);
        }

        return $stenteringDetails;
    }
}
