<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Services;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintEmbroideryQcInventoryChallan;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class PrintEmbrQcDeliveryChallanService
{
    public function bundlesForPrintChallan(array $ids)
    {
        return BundleCard::whereIn('id', $ids)->with([
            'buyer:id,name',
            'order:id,order_style_no,booking_no,total_quantity',
            'purchaseOrder:id,po_no,po_quantity',
            'size:id,name'
        ])->get();
    }

    public function getUserFactory($factoryId)
    {
        return Factory::whereId($factoryId)->first();
    }

    public function deliveryChallanForPrintView($challan_no)
    {
        return PrintEmbroideryQcInventoryChallan::with(
            'inventories:id,challan_no,bundle_card_id'
        )->where('challan_no', $challan_no)->first();
    }

    public function tagChallanList($type)
    {
        return PrintEmbroideryQcInventoryChallan::with('delivery_factory', 'createdBy')
            ->where('type', $type)
            ->when(request('q'), function ($query) {
                $query->where('challan_no', 'LIKE', '%' . request('q') . '%');
            })
            ->latest()
            ->paginate();
    }

    public function destry($id)
    {
        return PrintEmbroideryQcInventoryChallan::where('challan_no', $challan_no)->delete();
    }
}