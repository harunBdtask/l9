<?php

namespace SkylarkSoft\GoRMG\Sample\Services\SampleRequisition;

use Illuminate\Support\Arr;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class SampleOrderRequisitionService
{
    public function garmentItemsByStyleName($styleName)
    {
        $order = Order::getByStyleName($styleName);

        if (! $order) {
            return GarmentsItem::all(['id', 'name as text']);
        }

        $items = Arr::get($order->toArray(), 'item_details.details', []);
        $itemsId = Arr::pluck($items, 'item_id');

        return GarmentsItem::whereIn('id', $itemsId)->get(['id', 'name as text']);
    }

    public static function fabricUoms()
    {
        return [
            [
                "id" => 1,
                "name" => "Kg",
            ],
            [
                "id" => 2,
                "name" => "Yards",
            ],
            [
                "id" => 3,
                "name" => "Meter",
            ],
            [
                "id" => 4,
                "name" => "Pcs",
            ],
        ];
    }

    public static function fabricSources()
    {
        return [
            [
                "id" => 1,
                "name" => "Production",
            ],
            [
                "id" => 2,
                "name" => "Purchase",
            ],
            [
                "id" => 3,
                "name" => "Buyer Supplier",
            ],
            [
                "id" => 4,
                "name" => "Stock",
            ],
        ];
    }
}
