<?php

namespace SkylarkSoft\GoRMG\Knitting\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingRollDeliveryChallanDetail;

class KnittingRollDeliveryRule implements Rule
{

    protected $challanNo;
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
        $existingRoll = KnittingRollDeliveryChallanDetail::query()->where([
            'knitting_program_roll_id' => $value
        ])->first();
        $this->challanNo = $existingRoll ? $existingRoll->challan_no : '';

        return !$existingRoll;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This roll is already challaned in '.$this->challanNo.' .';
    }
}
