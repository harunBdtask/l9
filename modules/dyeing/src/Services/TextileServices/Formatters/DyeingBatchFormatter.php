<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatchDetail;

class DyeingBatchFormatter
{

    public function format($dyeingBatch)
    {
        $dyeingBatch->load('dyeingBatchDetails');

        return array_merge($dyeingBatch->toArray(), [
            'color' => $dyeingBatch->color->name,
            'dyeing_batch_details' => $dyeingBatch->getRelation('dyeingBatchDetails')
                ->map(function ($collection) {
                    return (new DyeingBatchDetailFormatter())->format($collection);
                }),
        ]);
    }
}
