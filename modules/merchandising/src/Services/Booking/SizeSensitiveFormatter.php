<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking;

use SkylarkSoft\GoRMG\Merchandising\Services\Booking\States\FormatterState;

class SizeSensitiveFormatter implements ItemFormatter
{
    const FORMAT = "%01.4f";

    public $formattedDetails;

    public function __construct()
    {
        $this->formattedDetails = [];
    }

    public function format($data, $type = null): array
    {
        $breakdown = collect($data->breakdown);

        foreach ($breakdown->groupBy('color_id') as $colorWiseData) {

            foreach ($colorWiseData->groupBy('size_id') as $colorSizeWiseData) {

                $item = $colorSizeWiseData->first();

                $bookedQty = (new FormatterState)->setState($type)
                    ->filters([
                        'budget_unique_id' => request('budget_unique_id'),
                        'item_id' => request('item_id'),
                        'color_id' => $item['color_id'],
                        'size_id' => $item['size_id'],
                        'po_no' => request('po_no')
                    ])
                    ->bookedQty('qty');

                $stockQty = (new FormatterState)->setState($type)
                    ->filters([
                        'item_id' => request('item_id'),
                    ])
                    ->stockQty('stock');

                $poNo = $item['po_no'];

                $totalAmount = $colorWiseData->where('size_id', $item['size_id'])->sum('total_amount');

                $totalQty = $colorWiseData->where('size_id', $item['size_id'])->sum('total_qty');

                $this->formattedDetails[] = [
                    'gmts_item_id' => $item['item_id'],
                    'gmts_item_name' => $item['item'],
                    'excess_percent' => $item['ext_cons_percent'] ?? 0,
                    'type' => $type,
                    'po_no' => $item['po_no'] ?? null,
                    'color' => $item['color'] ?? null,
                    'color_id' => $item['color_id'] ?? null,
                    'size' => $item['size'] ?? null,
                    'size_id' => $item['size_id'] ?? null,
                    'item_description' => $item->item_description ?? null,
                    'brand' => $item['brand_value'] ?? '',
                    'brand_id' => $item['brand_id'] ?? null,
                    'item_size' => $item['item_size'] ?? null,
                    'item_color' => null,
                    'ref' => null,
                    'article_no' => null,
                    'care_symbol' => null,
                    'care_instruction' => null,
                    'production_batch' => null,
                    'fiber_composition' => null,
                    'remarks' => null,
                    'booked_qty' => $bookedQty,
                    'wo_qty' => sprintf(self::FORMAT, $totalQty - $bookedQty ?? 0),
                    'budget_qty' => sprintf(self::FORMAT, $totalQty ?? 0),
                    'balance' => sprintf(self::FORMAT, $totalQty ?? 0 - $bookedQty),
                    'wo_total_qty' => sprintf(self::FORMAT, $totalQty - $bookedQty ?? 0),
                    'moq_qty' => sprintf(self::FORMAT, $item['moq_qty'] ?? 0),
                    'avl_stock_qty' => sprintf(self::FORMAT, $stockQty),
                    'rate' => sprintf(self::FORMAT, $item['rate'] ?? 0),
                    'amount' => sprintf(self::FORMAT, $totalAmount),
                    'pcs' => sprintf(self::FORMAT, $item['pcs'] ?? 0),
                    'team_id' => null,
                    'division' => null,
                    'style_ref' => null,
                    'po_ref' => null,
                    'qty_per_carton' => null,
                    'measurement' => null,
                    'fabric_ref' => null,
                    'thread_count' => null,
                    'cons_per_mtr' => null,
                    'league' => null,
                    'age_or_size' => null,
                    'poly_bag_art_work' => null,
                    'fold_over' => null,
                    'poly_thickness' => null,
                    'swatch' => null,
                    'sizer' => null,
                    'combo_color' => null,
                    'item_code' => null,
                    'binding_color' => null,
                    'zip_puller_ref' => null,
                    'zipper_puller_teeth_color' => null,
                    'zipper_tape_color' => null,
                    'contrast_cord_color' => null,
                    'zipper_size' => null,
                    'fusing_status' => null,
                ];
            }
        }

        return $this->formattedDetails;
    }
}
