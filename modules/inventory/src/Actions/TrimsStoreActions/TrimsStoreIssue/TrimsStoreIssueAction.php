<?php

namespace SkylarkSoft\GoRMG\Inventory\Actions\TrimsStoreActions\TrimsStoreIssue;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreBinCard\TrimsStoreBinCardDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreIssue\TrimsStoreIssue;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsStore\TrimsIssueDetailsService;

class TrimsStoreIssueAction extends Controller
{
    /**
     * @param TrimsStoreIssue $issue
     * @return void
     */
    public function storeDetails(TrimsStoreIssue $issue)
    {
        $trimsBinCardDetails = TrimsStoreBinCardDetail::query()
            ->with('mrrDetail')
            ->where('trims_store_bin_card_id', $issue['trims_store_bin_card_id'])
            ->get();

        $receiveDetails = TrimsIssueDetailsService::formatForBinCardDetails($trimsBinCardDetails);

        $issue->details()->createMany($receiveDetails);
    }
}
