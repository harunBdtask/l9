<?php

namespace SkylarkSoft\GoRMG\Subcontract\Actions;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceive;

class SyncFabricReceiveDetails
{
    public function handle(SubGreyStoreReceive $greyStoreReceive)
    {
        if (count($greyStoreReceive->receiveDetails)) {
            foreach ($greyStoreReceive->receiveDetails as $receiveDetail) {
                $receiveDetail->update([
                    'sub_grey_store_id' => $greyStoreReceive->sub_grey_store_id,
                    'challan_no' => $greyStoreReceive->challan_no,
                    'challan_date' => $greyStoreReceive->challan_date,
                ]);
            }
        }
    }
}
