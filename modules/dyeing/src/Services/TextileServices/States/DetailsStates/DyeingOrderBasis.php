<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\States\DetailsStates;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrderDetail;

class DyeingOrderBasis implements DetailsContract
{
    /**
     * @param Request $request
     * @return Builder[]
     */
    public function handle(Request $request): array
    {
        return TextileOrderDetail::query()
            ->with(['textileOrder', 'color'])
            ->whereHas('textileOrder', Filter::applyFilter('factory_id', $request->input('factory_id')))
            ->whereHas('textileOrder', Filter::applyFilter('buyer_id', $request->input('buyer_id')))
            ->where('textile_order_id', $request->input('order_id'))
            ->get()->map(function ($collection) {
                return [
                    'textile_order_id' => $collection->textile_order_id,
                    'textile_order_no' => $collection->textileOrder->unique_id,
                    'textile_order_details_id' => $collection->id,
                    'dyeing_batch_id' => null,
                    'dyeing_batch_no' => null,
                    'dyeing_batch_details_id' => null,
                    'fabric_composition_id' => $collection->fabric_composition_id,
                    'fabric_type_id' => $collection->fabric_type_id,
                    'finish_dia' => $collection->finish_dia,
                    'dia_type_id' => $collection->dia_type_id,
                    'dia_type_value' => $collection->dia_type_value,
                    'gsm' => $collection->gsm,
                    'fabric_description' => $collection->fabric_composition_value,
                    'color_id' => $collection->item_color_id,
                    'color_name' => $collection->color->name,
                    'color_type_id' => $collection->color_type_id,
                    'batch_qty' => null,
                    'order_qty' => $collection->order_qty,
                ];
            })->toArray();
    }
}
