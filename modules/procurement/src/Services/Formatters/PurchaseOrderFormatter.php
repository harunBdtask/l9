<?php

namespace SkylarkSoft\GoRMG\Procurement\Services\Formatters;

class PurchaseOrderFormatter
{
    public function format($purchaseOrder)
    {
        $purchaseOrder->load('poDetails');

        return array_merge($purchaseOrder->toArray(), [
            // 'created_by_name' => $procurementRequisition->createdBy->screen_name,
            'purchase_order_details' => $purchaseOrder
                ->getRelation('poDetails')
                ->map(function ($collection) {
                    return array_merge($collection->toArray(), [
                        'item_name' => $collection->item->item_group,
                        'quotation_description' => $collection->quotation->item_description,
                    ]);
                }),
        ]);
    }
}
