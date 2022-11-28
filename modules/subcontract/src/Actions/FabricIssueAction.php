<?php

namespace SkylarkSoft\GoRMG\Subcontract\Actions;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreIssue;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\SubTextileOrderDetailsService;

class FabricIssueAction
{
    public function attach(SubGreyStoreIssue $greyStoreIssue)
    {
        $orderId = $greyStoreIssue->sub_textile_order_id;
        $subTextileOrderDetails = SubTextileOrderDetailsService::formatForIssue($orderId, $greyStoreIssue);
        $greyStoreIssue->issueDetails()->createMany($subTextileOrderDetails);
    }
}
