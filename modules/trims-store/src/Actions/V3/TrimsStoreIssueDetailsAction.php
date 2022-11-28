<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Actions\V3;

use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreIssue\TrimsStoreIssue;

class TrimsStoreIssueDetailsAction
{
    public function storeDetails(TrimsStoreIssue $issue)
    {
        $issue->load('trimsStoreReceive.details');

        $details = $issue->getRelation('trimsStoreReceive')
            ->getRelation('details')
            ->map(function ($detail) use ($issue) {
                return [
                    'trims_store_issue_id' => $issue->id,
                    'transaction_date' => $issue->issue_date,
                    'factory_id' => $detail->factory_id,
                    'buyer_id' => $detail->buyer_id,
                    'style_id' => $detail->style_id,
                    'po_numbers' => $detail->po_numbers,
                    'booking_id' => $detail->booking_id,
                    'booking_no' => $detail->booking_no,
                    'garments_item_id' => $detail->garments_item_id,
                    'item_code' => $detail->item_code,
                    'item_id' => $detail->item_id,
                    'sensitivity_id' => $detail->sensitivity_id,
                    'supplier_id' => $detail->supplier_id,
                    'brand_name' => $detail->brand_name,
                    'item_description' => $detail->item_description,
                    'color_id' => $detail->color_id,
                    'size_id' => $detail->size_id,
                    'order_qty' => $detail->order_qty,
                    'wo_qty' => $detail->wo_qty,
                    'uom_id' => $detail->uom_id,
                    'currency_id' => $detail->currency_id,
                    'floor_id' => $detail->floor_id,
                    'room_id' => $detail->room_id,
                    'rack_id' => $detail->rack_id,
                    'shelf_id' => $detail->shelf_id,
                    'bin_id' => $detail->bin_id,
                ];
            });

        $issue->details()->createMany($details);
    }
}
