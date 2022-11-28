<?php

namespace SkylarkSoft\GoRMG\Inventory\Actions\TrimsStoreActions\TrimsStoreDeliveryChallan;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreIssue\TrimsStoreIssue;

class TrimsStoreDeliveryChallanDetailsAction extends Controller
{
    /**
     * @param $challan
     * @return void
     */
    public function storeDetails($challan)
    {
        $issueDetails = TrimsStoreIssue::query()
            ->with('details')
            ->where('booking_id', $challan->booking_id)
            ->get()
            ->pluck('details')
            ->flatten();

        $issueDetails = $issueDetails->map(function ($item) use ($challan) {
            unset($item['created_at'], $item['updated_at'], $item['created_by'], $item['updated_by']);
            $item['issue_date'] = null;
            $item['issue_qty'] = null;
            $item['trims_store_delivery_challan_id'] = $challan->id;
            $item['trims_store_issue_detail_id'] = $item->id;
            $item['planned_garments_qty'] = $item->planned_garments_qty;
            return $item;
        })->toArray();

        $challan->details()->createMany($issueDetails);
    }
}
