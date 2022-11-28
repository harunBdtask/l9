<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Services;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintDeliveryInventory;

class PrintScanService
{
    public function getBundleCard($request = null)
    {
        $request = $request ?? request();

        return BundleCard::with([
            'buyer:id,name',
            'order:id,order_style_no,booking_no',
            'purchaseOrder:id,po_no',
            'color:id,name',
            'size:id,name',
            'lot:id,lot_no',
            'print_inventory:id,bundle_card_id'
        ])->where([
            'id'     => substr($request->bundle_card_id, 1, 9),
            'status' => ACTIVE
        ])->first();
    }

    public function printDeliveryChallanWiseBundles($challan_no)
    {
        if (! $challan_no) {
            return [];
        }


        return PrintDeliveryInventory::with([
                'bundle_card:id,bundle_no,suffix,cutting_no,quantity,total_rejection,print_factory_receive_rejection,print_factory_delivery_rejection,print_rejection,embroidary_rejection,buyer_id,order_id,purchase_order_id,color_id,size_id,lot_id',
                'bundle_card.order:id,order_style_no,booking_no',
                'bundle_card.purchaseOrder:id,po_no,po_quantity',
                'bundle_card.color:id,name',
                'bundle_card.size:id,name',
                'bundle_card.lot:id,lot_no'
            ])
                ->where('challan_no', $challan_no)
                ->orderby('bundle_card_id')
                ->get() ?? [];
    }
}