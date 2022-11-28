<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters;

class TextileOrderFormatter
{

    public function format($textileOrder)
    {
        $textileOrder->load('textileOrderDetails');

        return array_merge($textileOrder->toArray(), [
            'textile_order_details' => $textileOrder->getRelation('textileOrderDetails')
                ->map(function ($collection) {
                    return array_merge($collection->toArray(), [
                        'body_part_value' => $collection->bodyPart->name,
                        'item_color' => $collection->color->name,
                        'color_type' => $collection->colorType->color_types,
                        'uom' => $collection->unitOfMeasurement->unit_of_measurement,
                        'customer_buyer_name' => $collection->customerBuyer->name,
                        'customer_style_name' => $collection->customerStyle->style_name,
                        'fabric_type' => $collection->fabricType->name,
                    ]);
                }),
        ]);
    }
}
