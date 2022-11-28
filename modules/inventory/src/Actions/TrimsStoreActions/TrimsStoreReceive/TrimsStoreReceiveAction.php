<?php

namespace SkylarkSoft\GoRMG\Inventory\Actions\TrimsStoreActions\TrimsStoreReceive;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventoryDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceive;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsStore\TrimsInventoryDetailsService;

class TrimsStoreReceiveAction extends Controller
{
    /**
     * @param TrimsStoreReceive $receive
     * @return void
     */
    public function storeDetails(TrimsStoreReceive $receive)
    {
        $trimsInventoryDetails = TrimsInventoryDetail::query()
            ->with('trimsBookingDetail')
            ->where('trims_inventory_id', $receive['trims_inventory_id'])
            ->get();

        $receiveDetails = TrimsInventoryDetailsService::formatForReceiveDetails($trimsInventoryDetails);

        $receive->details()->createMany($receiveDetails);
    }
}
