<?php

namespace SkylarkSoft\GoRMG\Inventory\Actions\TrimsStoreActions\TrimsStoreMrr;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrr;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsStore\TrimsReceiveDetailsService;

class TrimsStoreMrrAction extends Controller
{
    /**
     * @param TrimsStoreMrr $mrr
     * @return void
     */
    public function storeDetails(TrimsStoreMrr $mrr)
    {
        $trimsReceiveDetails = TrimsStoreReceiveDetail::query()
            ->with('trimsStoreReceive')
            ->where('trims_store_receive_id', $mrr['trims_store_receive_id'])
            ->get();

        $receiveDetails = TrimsReceiveDetailsService::formatForMrrDetails($trimsReceiveDetails);

        $mrr->details()->createMany($receiveDetails);
    }
}
