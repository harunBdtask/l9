<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Textiledroplets\Models\FinishFabStore;
use DB;

class CheckAvailableStoreAmount implements Rule
{
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
        $requisition_amounts = request()->get('requisition_amount');     

        foreach ($requisition_amounts as $key => $requisition_amount) {
            $store_available_amount = FinishFabStore::where([
                'garments_part_id' => request('garments_part_id')[$key],
                'composition_fabric_id' => request('composition_fabric_id')[$key],
                'color_id' => request('color_id')[$key],
                'fabric_type' => request('fabric_type')[$key],
                'unit_of_measurement_id' => request('unit_of_measurement_id')[$key]
            ])->value(DB::raw("SUM(today_receive_qty - today_delivery_qty)")) ?? 0;

            if ($requisition_amount > $store_available_amount) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Must be less than or equal available amount';
    }
}
