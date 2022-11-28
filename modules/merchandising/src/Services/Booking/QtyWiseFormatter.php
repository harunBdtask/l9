<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking;

use SkylarkSoft\GoRMG\Merchandising\Services\Booking\States\FormatterState;

class QtyWiseFormatter implements ItemFormatter
{
    const FORMAT = "%01.4f";

    private $formattedData;

    public function __construct()
    {
        $this->formattedData = [];
    }

    public function format($data, $type = null): array
    {
        $breakdown = collect($data->breakdown);

        $filters = [
            'budget_unique_id' => request('budget_unique_id'),
            'item_id' => request('item_id'),
            'po_no' => request('po_no'),
        ];
        $bookedQty = (new FormatterState)->setState($type)
            ->filters($filters)
            ->bookedQty('qty');

        $stockQty = (new FormatterState)->setState($type)
            ->filters([
                'item_id' => request('item_id'),
            ])
            ->stockQty('stock');

        $poNo = $breakdown->pluck('po_no')->unique()->all();
        $totalAmount = $breakdown->sum('total_amount');

        $this->formattedData[] = [
            'gmts_item_id' => $breakdown->first()['item_id'],
            'gmts_item_name' => $breakdown->first()['item'],
            'excess_percent' => $breakdown->first()['ex_cons_percent'] ?? 0,
            'type' => $type,
            'po_no' => $poNo,
            'color' => null,
            'color_id' => null,
            'size' => null,
            'size_id' => null,
            'item_description' => $data->item_description ?? null,
            'brand' => $breakdown->first()['brand_value'] ?? '',
            'brand_id' => $breakdown->first()['brand_id'] ?? null,
            'item_size' => $breakdown->pluck('item_size')->unique()->filter(function ($size) {
                return strlen($size);
            })->implode(', '),
            'item_color' => null,
            'ref' => null,
            'article_no' => null,
            'care_symbol' => null,
            'care_instruction' => null,
            'production_batch' => null,
            'remarks' => null,
            'fiber_composition' => null,
            'wo_qty' => $data->work_order_qty ?? sprintf(self::FORMAT, ($breakdown->sum('total_qty') - $bookedQty)),
            'budget_qty' => sprintf(self::FORMAT, $breakdown->sum('total_qty')),
            'balance' => sprintf(self::FORMAT, $breakdown->sum('total_qty') - $bookedQty),
            'wo_total_qty' => $data->work_order_qty ?? sprintf(self::FORMAT, ($breakdown->sum('total_qty') - $bookedQty)),
            'moq_qty' => sprintf(self::FORMAT, $breakdown->sum('moq_qty')),
            'avl_stock_qty' => sprintf(self::FORMAT, $stockQty),
            'rate' => sprintf(self::FORMAT, $breakdown->avg('rate')),
            'amount' => sprintf(self::FORMAT, $totalAmount),
            'pcs' => sprintf(self::FORMAT, $breakdown->sum('pcs')),
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

        return $this->formattedData;
    }
}
