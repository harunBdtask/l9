<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\SampleRequisition;

use Illuminate\Support\Arr;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class SampleRequisitionService
{
    public function garmentItemsByStyleName($styleName)
    {
        $order = Order::getByStyleName($styleName);

        if (!$order) {
            return GarmentsItem::all(['id', 'name as text']);
        }

        $items = Arr::get($order->toArray(), 'item_details.details', []);
        $itemsId = Arr::pluck($items, 'item_id');

        return GarmentsItem::whereIn('id', $itemsId)->get(['id', 'name as text']);
    }
}