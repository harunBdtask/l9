<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\YarnPurchaseOrder;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseRequisitions\YarnPurchaseRequisition;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;

class RequisitionBasisDetails implements Searchable
{
    public function search(Request $request): Collection
    {
        $requisition_no = $request->get('requisition_no');
        $from = $request->get('from') ?? null;
        $to = $request->get('to') ?? null;
        $buyer_id = $request->get('buyer_id');

        $yarnActionStatus = MerchandisingVariableSettings::query()->where(['factory_id'=> factoryId(), 'buyer_id' => $buyer_id])->first();
        $yarnActionStatus = isset($yarnActionStatus) ? $yarnActionStatus['variables_details']['budget_approval_required_for_booking']['yarn_part'] : null;


        $requisitions = YarnPurchaseRequisition::query()
            ->when($requisition_no, function ($query, $requisition_no) {
                return $query->where('id', $requisition_no);
            })->when($from, function ($query, $from) use ($to) {
                return $query->whereBetween('requisition_date', [$from, $to]);
            })->get();
        return RequisitionBasisSearchFormatter::format($requisitions, $yarnActionStatus);
    }
}
