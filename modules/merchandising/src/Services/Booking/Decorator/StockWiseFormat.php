<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking\Decorator;

use SkylarkSoft\GoRMG\Merchandising\Services\Booking\States\FormatterState;

class StockWiseFormat extends FormatterDecorator
{

    const FORMAT = "%01.4f";

    public function decorate(): array
    {

        return collect($this->bookingFormatterComponentInterface->decorate())
            ->map(function ($collection) {
                $stockQty = (new FormatterState)->setState('trims_booking')
                    ->filters([
                        'item_id' => request('item_id'),
                        'item_color' => $collection['item_color'],
                    ])
                    ->stockQty('stock');
                return collect($collection)->merge(['avl_stock_qty' => $stockQty]);
            })
            ->toArray();
    }
}
