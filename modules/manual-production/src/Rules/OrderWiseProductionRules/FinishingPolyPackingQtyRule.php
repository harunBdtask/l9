<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Rules\OrderWiseProductionRules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualHourlySewingProduction;

class FinishingPolyPackingQtyRule implements Rule
{
    private $production_qty, $remaining_qty, $production_date;
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
        $production_date = request()->get('production_date');
        $factory_id = request()->get('factory_id');
        $buyer_id = request()->get('buyer_id');
        $order_id = request()->get('order_id');
        $garments_item_id = request()->get('garments_item_id');
        $purchase_order_id = request()->get('purchase_order_id');

        if (!$production_date) {
            return true;
        }
        
        $prev_date_production_qty = ManualHourlySewingProduction::query()
            ->where('production_date', '<=', $production_date)
            ->where([
                'factory_id' => $factory_id,
                'buyer_id' => $buyer_id,
                'order_id' => $order_id,
                'garments_item_id' => $garments_item_id,
                'purchase_order_id' => $purchase_order_id,
            ])->sum('production_qty');

        $remaining_qty = request()->get('remaining_production_qty');
        $max_production_qty = 0;
        if ($prev_date_production_qty >= $remaining_qty) {
            $max_production_qty = $remaining_qty;
        } else {
            $max_production_qty = $prev_date_production_qty;
        }
        $this->production_qty = $value;
        $this->remaining_qty = $max_production_qty;
        $this->production_date = date('d M Y', strtotime($production_date));
        return $value > $max_production_qty ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The production qty $this->production_qty cannot be greater than poly balance qty  $this->remaining_qty till $this->production_date .";
    }
}
