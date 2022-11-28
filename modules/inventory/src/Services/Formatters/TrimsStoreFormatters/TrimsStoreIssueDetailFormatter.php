<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\Formatters\TrimsStoreFormatters;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreIssue\TrimsStoreIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrr;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrrDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceiveDetail;

class TrimsStoreIssueDetailFormatter
{
    public function format(TrimsStoreIssueDetail $detail): array
    {
        $mrrQty = $detail->trimsBinCardDetail->mrrDetail->total_delivered_qty;

        return array_merge($detail->toArray(), [
            'item_name' => $detail->itemGroup->item_group ?? '',
            'color' => $detail->color->name ?? '',
            'floor' => $detail->floor->name ?? '',
            'room' => $detail->room->name ?? '',
            'rack' => $detail->rack->name ?? '',
            'shelf' => $detail->shelf->name ?? '',
            'bin' => $detail->bin->name ?? '',
            'uom_value' => $detail->uom->unit_of_measurement ?? '',
            'mrr_qty' => $mrrQty,
            'issue_balance' => $mrrQty - $detail->issue_qty,
            'planned_garments_qty' => $detail->planned_garments_qty,
        ]);
    }
}
