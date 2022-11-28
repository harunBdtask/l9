<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\States\DetailsStates;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatchDetail;

class DyeingBatchBasis implements DetailsContract
{
    /**
     * @param Request $request
     * @return Builder[]
     */
    public function handle(Request $request): array
    {
        return DyeingBatchDetail::query()
            ->with(['dyeingBatch', 'color'])
            ->whereHas('dyeingBatch', Filter::applyFilter('factory_id', $request->input('factory_id')))
            ->whereHas('dyeingBatch', Filter::applyFilter('buyer_id', $request->input('buyer_id')))
            ->where('dyeing_batch_id', $request->input('batch_id'))
            ->get()->map(function ($collection) {
                return [
                    'textile_order_id' => $collection->textile_order_id,
                    'textile_order_no' => $collection->textile_order_no,
                    'textile_order_details_id' => $collection->textile_order_detail_id,
                    'dyeing_batch_id' => $collection->dyeing_batch_id,
                    'dyeing_batch_no' => $collection->dyeingBatch->batch_no,
                    'dyeing_batch_details_id' => $collection->id,
                    'fabric_composition_id' => $collection->fabric_composition_id,
                    'fabric_type_id' => $collection->fabric_type_id,
                    'finish_dia' => $collection->finish_dia,
                    'dia_type_id' => $collection->dia_type_id,
                    'dia_type_value' => $collection->dia_type_value,
                    'gsm' => $collection->gsm,
                    'fabric_description' => $collection->fabric_description,
                    'color_id' => $collection->color_id,
                    'color_name' => $collection->color->name,
                    'color_type_id' => $collection->color_type_id,
                    'batch_qty' => $collection->order_qty,
                    'order_qty' => $collection->textileOrderDetail->order_qty,
                ];
            })->toArray();
    }
}
