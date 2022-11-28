<?php

namespace SkylarkSoft\GoRMG\Subcontract\Actions;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreIssue;

class SyncFabricIssueDetails
{
    public function handle(SubGreyStoreIssue $greyStoreIssue)
    {
        if (count($greyStoreIssue->issueDetails)) {
            foreach ($greyStoreIssue->issueDetails as $issueDetail) {
                $issueDetail->update([
                    'sub_grey_store_id' => $greyStoreIssue->sub_grey_store_id,
                    'sub_dyeing_unit_id' => $greyStoreIssue->sub_dyeing_unit_id,
                    'challan_no' => $greyStoreIssue->challan_no,
                    'challan_date' => $greyStoreIssue->challan_date,
                ]);
            }
        }
    }
}
