<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\Formatters\TrimsStoreFormatters;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreBinCard\TrimsStoreBinCardDetail;

class TrimsStoreBinCardDetailsFormatter
{
    public function format(TrimsStoreBinCardDetail $detail): array
    {
        $todayDate = Carbon::now();
        $binCardDate = $detail['bin_card_date'] ? Carbon::make($detail['bin_card_date']) : null;
        $mrrQty = $detail->mrrDetail->total_delivered_qty;
        $issueQty = $detail->getRelation('issueDetails')->sum('issue_qty');

        return array_merge($detail->toArray(), [
            'item_name' => $detail->itemGroup->item_group,
            'color' => $detail->color->name,
            'uom_value' => $detail->uom->unit_of_measurement,
            'mrr_qty' => $mrrQty,
            'issue_qty' => $issueQty,
            'issue_balance' => $mrrQty - $issueQty,
            'ageing' => $detail['bin_card_date'] ? $binCardDate->diffInDays($todayDate) : 0,
        ]);
    }
}
