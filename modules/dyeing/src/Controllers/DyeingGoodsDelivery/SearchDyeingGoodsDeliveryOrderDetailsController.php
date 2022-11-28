<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingGoodsDelivery;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\States\DetailsStates\DetailsState;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingGoodsDelivery\DyeingGoodsDeliveryDetail;

class SearchDyeingGoodsDeliveryOrderDetailsController extends Controller
{
    public function __invoke(Request $request)
    {
        $searchState = DetailsState::setState($request->input('type'));
        $dyeingGoodsDeliveryDetails = $searchState->handle($request);

        foreach ($dyeingGoodsDeliveryDetails as $key => $detail) {

            $prevQty = DyeingGoodsDeliveryDetail::query()
                ->selectRaw('SUM(total_roll) As totalRoll,SUM(delivery_qty) As total_delivery_qty')
                ->when($detail['textile_order_details_id'],
                    Filter::applyFilter('textile_order_details_id', $detail['textile_order_details_id']))
                ->when($detail['dyeing_batch_details_id'],
                    Filter::applyFilter('dyeing_batch_details_id', $detail['dyeing_batch_details_id']))
                ->first();


            $dyeingGoodsDeliveryDetails[$key] = array_merge($detail,[
                'prev_total_roll' => $prevQty->totalRoll ?? 0,
                'total_roll' => null,
                'prev_delivery_qty' => $prevQty->total_delivery_qty ?? 0,
                'delivery_qty' => null,
                'reject_roll' => null,
                'reject_qty' => null,
                'remarks' => null,
                'req_order_qty' => $detail['batch_qty'] ?? $detail['order_qty'],
            ]);

        }

        return $dyeingGoodsDeliveryDetails;
    }
}