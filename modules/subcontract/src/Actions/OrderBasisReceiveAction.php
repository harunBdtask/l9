<?php

namespace SkylarkSoft\GoRMG\Subcontract\Actions;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceive;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\SubTextileOrderDetailsService;

class OrderBasisReceiveAction
{
    public function attach(SubGreyStoreReceive $greyStoreReceive)
    {
        if ($greyStoreReceive->receive_basis == SubGreyStoreReceive::ORDER_BASIS_RECEIVE) {
            $orderId = $greyStoreReceive->sub_textile_order_id;
            $subTextileOrderDetails = SubTextileOrderDetailsService::formatForReceive($orderId, $greyStoreReceive);
            $greyStoreReceive->receiveDetails()->createMany($subTextileOrderDetails);
        }
    }
}
