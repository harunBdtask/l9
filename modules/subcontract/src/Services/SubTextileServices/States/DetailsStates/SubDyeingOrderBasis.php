<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\States\DetailsStates;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrderDetail;

class SubDyeingOrderBasis implements DetailsContract
{
    /**
     * @param Request $request
     * @return Builder[]
     */
    public function handle(Request $request): array
    {
        $orderIds = is_array($request->input('order_id'))
            ? $request->input('order_id')
            : [$request->input('order_id')];

        return SubTextileOrderDetail::query()
            ->with(['subTextileOrder', 'color'])
            ->where('factory_id', $request->input('factory_id'))
            ->where('supplier_id', $request->input('supplier_id'))
            ->whereIn('sub_textile_order_id', $orderIds)
            ->get()->map(function ($collection) {
                return [
                    'sub_dyeing_batch_id' => null,
                    'sub_dyeing_batch_no' => null,
                    'sub_textile_order_id' => $collection->sub_textile_order_id,
                    'sub_textile_order_no' => $collection->subTextileOrder->order_no,
                    'sub_textile_order_details_id' => $collection->id,
                    'sub_dyeing_batch_details_id' => null,
                    'fabric_composition_id' => $collection->fabric_composition_id,
                    'fabric_type_id' => $collection->fabric_type_id,
                    'finish_dia' => $collection->finish_dia,
                    'dia_type_id' => $collection->dia_type_id,
                    'dia_type_value' => $collection->dia_type,
                    'gsm' => $collection->gsm,
                    'fabric_description' => $collection->fabric_description,
                    'color_id' => $collection->color_id,
                    'color_name' => $collection->color->name,
                    'color_type_id' => $collection->color_type_id,
                    'batch_qty' => null,
                    'order_qty' => $collection->order_qty,
                ];
            })->toArray();
    }
}
