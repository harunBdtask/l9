<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking;

use SkylarkSoft\GoRMG\Merchandising\Services\Booking\States\FormatterState;

class ColorWiseFormatter implements ItemFormatter
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

        foreach ($breakdown->groupBy('color_id') as $key => $colorWiseData) {
            $bookedQty = (new FormatterState)->setState($type)
                ->filters([
                    'budget_unique_id' => request('budget_unique_id'),
                    'item_id' => request('item_id'),
                    'color_id' => $key,
                    'size_id' => null,
                    'po_no' => request('po_no')
                ])
                ->bookedQty('qty');

            $stockQty = (new FormatterState)->setState($type)
                ->filters([
                    'item_id' => request('item_id'),
                ])
                ->stockQty('stock');

            $poNo = $colorWiseData->pluck('po_no')->unique()->all();

            $totalAmount = $colorWiseData->sum('total_amount');

            $this->formattedDetails[] = [
                'gmts_item_id' => $colorWiseData->first()['item_id'],
                'gmts_item_name' => $colorWiseData->first()['item'],
                'excess_percent' => $colorWiseData->first()['ex_cons_percent'] ?? 0,
                'type' => $type,
                'po_no' => $poNo,
                'color_id' => $key,
                'color' => $colorWiseData->first()['color'],
                'size' => $colorWiseData->pluck('size')->unique()->implode(', '),
                'size_id' => $colorWiseData->pluck('size_id')->unique(),
                'item_description' => $data->item_description ?? null,
                'brand' => $colorWiseData->first()['brand_value'] ?? '',
                'brand_id' => $colorWiseData->first()['brand_id'] ?? null,
                'item_size' => $colorWiseData->pluck('item_size')->unique()->filter(function ($size) {
                    return strlen($size);
                })->implode(', '),
                'item_color' => null,
                'ref' => null,
                'article_no' => null,
                'care_symbol' => null,
                'care_instruction' => null,
                'production_batch' => null,
                'fiber_composition' => null,
                'remarks' => null,
                'wo_qty' => $colorWiseData->sum('total_qty')
                    ? sprintf(self::FORMAT, ($colorWiseData->sum('total_qty') - $bookedQty))
                    : $data->work_order_qty,
                'budget_qty' => sprintf(self::FORMAT, $colorWiseData->sum('total_qty')),
                'balance' => sprintf(self::FORMAT, $colorWiseData->sum('total_qty') - $bookedQty),
                'wo_total_qty' => sprintf(self::FORMAT, ($colorWiseData->sum('total_qty') - $bookedQty)) ?? $data->work_order_qty,
                'moq_qty' => sprintf(self::FORMAT, $colorWiseData->sum('moq_qty')),
                'avl_stock_qty' => sprintf(self::FORMAT, $stockQty),
                'rate' => sprintf(self::FORMAT, $colorWiseData->avg('rate')),
                'amount' => sprintf(self::FORMAT, $totalAmount),
                'pcs' => sprintf(self::FORMAT, $colorWiseData->sum('pcs')),
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

        return $this->formattedDetails;
    }
}
