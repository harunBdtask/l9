<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters;

use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Slitting\SlittingDetail;

class SlittingFormatter
{

    public function format($slitting)
    {
        $slitting->load('slittingDetails');

        return array_merge($slitting->toArray(), [
            'slitting_details' => $slitting->getRelation('slittingDetails')
                ->map(function ($collection) {

                    $prevQty = SlittingDetail::query()
                        ->selectRaw('SUM(fin_no_of_roll) As total_fin_no_of_roll,SUM(finish_qty) As total_finish_qty')
                        ->when($collection['textile_order_details_id'],
                            Filter::applyFilter('textile_order_details_id', $collection['textile_order_details_id']))
                        ->when($collection['dyeing_batch_details_id'],
                            Filter::applyFilter('dyeing_batch_details_id', $collection['dyeing_batch_details_id']))
                        ->first();

                    return array_merge($collection->toArray(), [
                        'total_fin_no_of_roll' => $prevQty->total_fin_no_of_roll ?? 0,
                        'prev_finish_qty' => $prevQty->total_finish_qty ?? 0,
                        'color_name' => $collection->color->name,
                    ]);
                }),
        ]);
    }

}
