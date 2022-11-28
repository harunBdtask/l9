<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Slitting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Slitting\SlittingDetail;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\States\DetailsStates\DetailsState;

class SearchSlittingDetailsController extends Controller
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        $searchState = DetailsState::setState($request->input('type'));
        $slittingDetails = $searchState->handle($request);

        foreach ($slittingDetails as $key => $detail) {

            $prevQty = SlittingDetail::query()
                ->selectRaw('SUM(fin_no_of_roll) As total_fin_no_of_roll,SUM(finish_qty) As total_finish_qty')
                ->when($detail['textile_order_details_id'],
                    Filter::applyFilter('textile_order_details_id', $detail['textile_order_details_id']))
                ->when($detail['dyeing_batch_details_id'],
                    Filter::applyFilter('dyeing_batch_details_id', $detail['dyeing_batch_details_id']))
                ->first();

            $slittingDetails[$key] = array_merge($detail, [
                'req_order_qty' => $detail['batch_qty'] ?? $detail['order_qty'],
                'total_fin_no_of_roll' => $prevQty->total_fin_no_of_roll ?? 0,
                'fin_no_of_roll' => null,
                'prev_finish_qty' => $prevQty->total_finish_qty ?? 0,
                'finish_qty' => null,
                'reject_roll_qty' => null,
                'reject_qty' => null,
                'total_finish_qty' => null,
                'unit_cost' => null,
                'total_cost' => null,
            ]);

        }

        return $slittingDetails;
    }

}
