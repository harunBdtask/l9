<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Rules\ColorWiseProductionRules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualHourlySewingProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualSewingInputProduction;

class SewingOutputQtyRule implements Rule
{
    private $message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = strtoupper($value);
        $production_date = request()->get('production_date') ?? null;
        $factory_id = request()->get('factory_id');
        $subcontract_factory_id = request()->get('subcontract_factory_id') ?? null;
        $buyer_id = request()->get('buyer_id');
        $order_id = request()->get('order_id');
        $garments_item_id = request()->get('garments_item_id');
        $purchase_order_id = request()->get('purchase_order_id');
        $attribute_split = explode('.', $attribute);
        $color_id = request()->get('color_id')[$attribute_split[1]];
        $floor_id = request()->get('floor_id') ?? null;
        $line_id = request()->get('line_id') ?? null;
        $sub_sewing_floor_id = request()->get('sub_sewing_floor_id') ?? null;
        $sub_sewing_line_id = request()->get('sub_sewing_line_id') ?? null;
        if (!$production_date) {
            $this->message = "Production date is required";
            return false;
        }
        if (!$floor_id && !$sub_sewing_floor_id) {
            $this->message = "Floor is required";
            return false;
        }
        if (!$line_id && !$sub_sewing_line_id) {
            $this->message = "Line is required";
            return false;
        }
        $prev_date_production_qty = ManualSewingInputProduction::query()
            ->where('production_date', '<=', $production_date)
            ->where([
                'factory_id' => $factory_id,
                'buyer_id' => $buyer_id,
                'order_id' => $order_id,
                'garments_item_id' => $garments_item_id,
                'purchase_order_id' => $purchase_order_id,
                'color_id' => $color_id
            ])
            ->when(($subcontract_factory_id && $sub_sewing_floor_id && $sub_sewing_line_id), function ($query) use ($subcontract_factory_id, $sub_sewing_floor_id, $sub_sewing_line_id) {
                $query->where([
                    'subcontract_factory_id' => $subcontract_factory_id,
                    'sub_sewing_floor_id' => $sub_sewing_floor_id,
                    'sub_sewing_line_id' => $sub_sewing_line_id
                ]);
            })
            ->when(($floor_id && $line_id && !$sub_sewing_floor_id && !$sub_sewing_line_id), function ($query) use ($floor_id, $line_id) {
                $query->where([
                    'floor_id' => $floor_id,
                    'line_id' => $line_id
                ]);
            })
            ->sum('production_qty');

        $total_input_qty = ManualSewingInputProduction::query()
            ->where([
                'factory_id' => $factory_id,
                'buyer_id' => $buyer_id,
                'order_id' => $order_id,
                'garments_item_id' => $garments_item_id,
                'purchase_order_id' => $purchase_order_id,
                'color_id' => $color_id
            ])
            ->when(($subcontract_factory_id && $sub_sewing_floor_id && $sub_sewing_line_id), function ($query) use ($subcontract_factory_id, $sub_sewing_floor_id, $sub_sewing_line_id) {
                $query->where([
                    'subcontract_factory_id' => $subcontract_factory_id,
                    'sub_sewing_floor_id' => $sub_sewing_floor_id,
                    'sub_sewing_line_id' => $sub_sewing_line_id
                ]);
            })
            ->when(($floor_id && $line_id && !$sub_sewing_floor_id && !$sub_sewing_line_id), function ($query) use ($floor_id, $line_id) {
                $query->where([
                    'floor_id' => $floor_id,
                    'line_id' => $line_id
                ]);
            })
            ->sum('production_qty');

        $total_output_qty = ManualHourlySewingProduction::query()
            ->where([
                'factory_id' => $factory_id,
                'buyer_id' => $buyer_id,
                'order_id' => $order_id,
                'garments_item_id' => $garments_item_id,
                'purchase_order_id' => $purchase_order_id,
                'color_id' => $color_id
            ])
            ->when(($subcontract_factory_id && $sub_sewing_floor_id && $sub_sewing_line_id), function ($query) use ($subcontract_factory_id, $sub_sewing_floor_id, $sub_sewing_line_id) {
                $query->where([
                    'subcontract_factory_id' => $subcontract_factory_id,
                    'sub_sewing_floor_id' => $sub_sewing_floor_id,
                    'sub_sewing_line_id' => $sub_sewing_line_id
                ]);
            })
            ->when(($floor_id && $line_id && !$sub_sewing_floor_id && !$sub_sewing_line_id), function ($query) use ($floor_id, $line_id) {
                $query->where([
                    'floor_id' => $floor_id,
                    'line_id' => $line_id
                ]);
            })->sum('production_qty');
        $remaining_qty = $total_input_qty - $total_output_qty;
        $max_production_qty = 0;
        if ($prev_date_production_qty >= $remaining_qty) {
            $max_production_qty = $remaining_qty;
        } else {
            $max_production_qty = $prev_date_production_qty;
        }
        $production_date_format = date('d M Y', strtotime($production_date));
        $this->message = "The production qty $value cannot be greater than sewing balance qty  $max_production_qty till $production_date_format .";
        return $value > $max_production_qty ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
