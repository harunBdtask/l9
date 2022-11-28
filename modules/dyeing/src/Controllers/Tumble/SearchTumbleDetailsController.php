<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Tumble;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Tumble\TumbleDetail;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\States\DetailsStates\DetailsState;


class SearchTumbleDetailsController extends Controller
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
            $prevQty = TumbleDetail::query()
                    ->selectRaw('SUM(no_of_roll) As total_no_roll,SUM(finish_qty) As total_finish_qty')
                    ->when($detail['textile_order_details_id'],
                        Filter::applyFilter('textile_order_details_id',$detail['textile_order_details_id']))
                    ->when($detail['dyeing_batch_details_id'],
                        Filter::applyFilter('dyeing_batch_details_id',$detail['dyeing_batch_details_id']))
                    ->first();


            $tumbleDetails[$key] = array_merge($detail,[
                'prev_no_of_roll' => $prevQty->total_no_roll ?? 0,
                'no_of_roll' => null,
                'prev_finish_qty' => $prevQty->total_finish_qty ?? 0,
                'finish_qty' => null,
                'reject_roll' => null,
                'reject_qty' => null,
                'unit_cost' => null,
                'total_cost' => null,
                'req_order_qty' => $detail['batch_qty'] ?? $detail['order_qty'],
            ]);
        }

        return $tumbleDetails;
     }
}