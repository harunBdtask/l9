<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services;

use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;

class OrderService
{
    public function region(): array
    {
        return PriceQuotation::REGIONS;
    }

    public function shipMode(): array
    {
        return ['Sea', 'Air', 'Road', 'Train', 'Sea/Air', 'Road/Air'];
    }

    public function packing(): array
    {
        return ['Solid color solid size', 'solid color asort size', 'Asort color solid size', 'Asort color asort size'];
    }

    public function delayFor(): array
    {
        return ['Knitting', 'Dyeing', 'Gmts Production'];
    }

    public function cutOff(): array
    {
        return ['1st cut off', '2nd cut off', '3rd cut off'];
    }

    public function cutOffForSelect(): array
    {
        $data = array();
        foreach ($this->cutOff() as $value) {
            $data[$value] = $value;
        }

        return $data;
    }

    public static function loadFactoryBuyerWiseJob($factoryId, $buyerId)
    {
        return Order::with('purchaseOrders')->where('factory_id', $factoryId)->where('buyer_id', $buyerId)->get();
    }

    public static function generateUniqueId(): string
    {
        $prefix = Order::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'OE-' . date('y') . '-' . $generate;
    }
}
