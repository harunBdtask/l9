<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Services;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintReceiveInventoryChallan;

class PrintReceiveChallanService
{
    public function challanList($type)
    {
        return PrintReceiveInventoryChallan::with([
            'inventories:id,bundle_card_id,challan_no', 
            'print_table:id,name', 
            'createdBy:id,first_name,last_name,email,screen_name'
        ])
        ->where('type', $type)
        ->when(request('q'), function ($query) {
            $query->where('challan_no', 'LIKE', '%' . request('q') . '%');
        })
        ->latest()
        ->paginate();
    }

    public function rcvChallanForPrintView($challan_no)
    {
       return PrintReceiveInventoryChallan::with(
           'inventories:id,challan_no,bundle_card_id'
       )->where('challan_no', $challan_no)->first();
    }

    public function bundlesForPrintChallan(array $ids)
    {
        return BundleCard::whereIn('id', $ids)->with([
            'buyer:id,name',
            'order:id,order_style_no,booking_no,total_quantity',
            'purchaseOrder:id,po_no,po_quantity',
            'size:id,name'
        ])->get();
    }
}