<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\TrimsIssues;


use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssueDetail;

class TrimsIssueQtyForStyleAndPO
{
    public function getQuantity(string $styleName, array $poNo)
    {
        return TrimsIssueDetail::query()
            ->where('style_name', $styleName)
            ->whereJsonContains('po_no', $poNo)
            ->sum('issue_qty');
    }
}
