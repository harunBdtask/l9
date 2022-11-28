<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\GatePassChallan;


use SkylarkSoft\GoRMG\Merchandising\Models\GatePassChallan\GatePasChallan;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class GatePassChallanReportService
{

    public static function fetchData($id)
    {
        $data = GatePasChallan::query()->with('merchant:id,screen_name,phone_no,address','party:id,name,contact_person,contact_no,address_1',
            'department:id,product_department','factory')->findOrFail($id);
        $data['goods_details'] = collect($data['goods_details'])->map(function ($item) {
            if (isset($item['po_no'])) {
                $poDetails = PurchaseOrder::query()
                    ->where('po_no', $item['po_no'])
                    ->first();
                $item['po_quantity'] = $poDetails->po_quantity ?? 0;
                $item['avg_rate_pc_set'] = $poDetails->avg_rate_pc_set ?? 0;
            }
            return $item;
        });
        return $data;
    }

}
