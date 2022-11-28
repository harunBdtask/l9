<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters;

class CompactorFormatter
{

    public function format($compactor)
    {
        $compactor->load('compactorDetails');

        return array_merge($compactor->toArray(), [
            'compactor_details' => $compactor->getRelation('compactorDetails')
            ->map(function ($collection) {
                return array_merge($collection->toArray(), [
                    'color_name' => $collection->color->name,
                ]);
            }),
        ]);
    }

}
