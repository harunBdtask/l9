<?php

namespace SkylarkSoft\GoRMG\Inventory\Actions\TrimsStoreActions\TrimsStoreBinCard;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreBinCard\TrimsStoreBinCard;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrrDetail;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsStore\TrimsStoreMrrDetailsService;

class TrimsStoreBinCardAction extends Controller
{
    public function storeDetails(TrimsStoreBinCard $binCard)
    {
        $trimsBinCardDetails = TrimsStoreMrrDetail::query()
            ->with('trimsBookingDetail', 'trimsStoreReceiveDetail')
            ->where('trims_store_mrr_id', $binCard['trims_store_mrr_id'])
            ->get();

        $binCardDetails = TrimsStoreMrrDetailsService::formatForBinCardDetails($trimsBinCardDetails);

        $binCard->details()->createMany($binCardDetails);
    }
}
