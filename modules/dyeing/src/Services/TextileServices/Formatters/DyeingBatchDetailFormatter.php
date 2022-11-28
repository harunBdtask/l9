<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatchDetail;

class DyeingBatchDetailFormatter
{

    public function format($dyeingBatchDetail)
    {
        $previousBatchDetails = DyeingBatchDetail::query()
            ->where('textile_order_detail_id', $dyeingBatchDetail->textile_order_detail_id)
            ->get();

        $prevBatchRoll = $previousBatchDetails->sum('batch_roll');

        $prevBatchWeight = $previousBatchDetails->sum('batch_weight');

        return array_merge($dyeingBatchDetail->toArray(), [
            'fabric_type_value' => $dyeingBatchDetail->fabricType->name,
            'body_part_value' => $dyeingBatchDetail->bodyPart->name,
            'color_type' => $dyeingBatchDetail->colorType->color_types,
            'uom' => $dyeingBatchDetail->unitOfMeasurement->unit_of_measurement,
            'color' => $dyeingBatchDetail->color->name,
            'prev_batch_roll' => $prevBatchRoll - $dyeingBatchDetail->batch_roll,
            'prev_batch_weight' => $prevBatchWeight - $dyeingBatchDetail->batch_weight,
        ]);
    }
}
