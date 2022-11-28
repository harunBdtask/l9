<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\YarnPurchaseOrder;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;

class StyleBasisDetails implements Searchable
{
    /**
     * @param Request $request
     * @return Collection
     */
    public function search(Request $request): Collection
    {
        $buyerId = $request->get('buyer_id');
        $uniqId = $request->get('uniq_id');
        $styleName = $request->get('style_name');
//        $po_no = $request->get('po_no');
        $from = $request->get('from');
        $to = $request->get('to');

        $yarnActionStatus = MerchandisingVariableSettings::query()
            ->where([
                'factory_id' => factoryId(),
                'buyer_id' => $buyerId
            ])
            ->first();
        $yarnActionStatus = isset($yarnActionStatus)
            ? $yarnActionStatus['variables_details']['budget_approval_required_for_booking']['yarn_part']
            : null;

        $budgets = Budget::query()
            ->when($buyerId, function ($budget, $buyerId) {
                $budget->where('buyer_id', $buyerId);
            })->when($uniqId, function ($budget, $uniqId) {
                $budget->where('job_no', 'LIKE', "%{$uniqId}%");
            })->when($styleName, function ($budget, $styleName) {
                $budget->where('style_name', 'LIKE', "%{$styleName}%");
            })->when($from, function ($budget, $from) use ($to) {
                $budget->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
            })->get();
        return StyleBasisSearchFormatter::format($budgets, $yarnActionStatus);
    }

}
