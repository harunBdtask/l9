<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\States\DetailsStates;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatchDetail;

class SubDyeingBatchBasis implements DetailsContract
{
    /**
     * @param Request $request
     * @return Builder[]
     */
    public function handle(Request $request): array
    {
        $batchIds = is_array($request->input('batch_id'))
            ? $request->input('batch_id')
            : [$request->input('batch_id')];

        return SubDyeingBatchDetail::query()
            ->with(['subDyeingBatch', 'color'])
            ->where('factory_id', $request->input('factory_id'))
            ->where('supplier_id', $request->input('supplier_id'))
            ->whereIn('sub_dyeing_batch_id', $batchIds)
            ->get()->map(function ($collection) {
                return [
                    'sub_dyeing_batch_id' => $collection->sub_dyeing_batch_id,
                    'sub_dyeing_batch_no' => $collection->subDyeingBatch->batch_no,
                    'sub_textile_order_id' => $collection->sub_textile_order_id,
                    'sub_textile_order_no' => $collection->subTextileOrder->order_no,
                    'sub_textile_order_details_id' => $collection->sub_textile_order_detail_id,
                    'sub_dyeing_batch_details_id' => $collection->id,
                    'fabric_composition_id' => $collection->fabric_composition_id,
                    'fabric_type_id' => $collection->fabric_type_id,
                    'finish_dia' => $collection->finish_dia,
                    'dia_type_id' => $collection->dia_type_id,
                    'dia_type_value' => $collection->dia_type_value['name'],
                    'gsm' => $collection->gsm,
                    'fabric_description' => $collection->material_description,
                    'color_id' => $collection->subDyeingBatch->fabric_color,
                    'color_name' => $collection->subDyeingBatch->fabricColor->name,
                    'color_type_id' => $collection->color_type_id,
                    'batch_qty' => $collection->batch_weight,
                    'order_qty' => $collection->subTextileOrderDetail->order_qty,
                ];
            })->toArray();
    }
}
