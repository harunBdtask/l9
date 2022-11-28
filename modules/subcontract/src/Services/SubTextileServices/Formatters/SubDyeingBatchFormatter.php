<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\Formatters;

class SubDyeingBatchFormatter
{
    public function format($dyeingBatch)
    {
        $dyeingBatch->load('batchDetails', 'fabricColor');

        return array_merge($dyeingBatch->toArray(), [
            'fabric_color' => $dyeingBatch->getRelation('fabricColor')->id,
        ]);
    }
}
