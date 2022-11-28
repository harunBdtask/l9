<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;

class PlyMaxCountRule implements Rule
{
    protected $bundleCardStraightSerialMaxLimit;
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
        $size_suffix_sl_status = request()->get('size_suffix_sl_status');
        $plys = \request()->get('ply');
        $validation = true;
        
        if ($size_suffix_sl_status == BundleCardGenerationDetail::STRAIGHT_SERIAL) {
            $this->bundleCardStraightSerialMaxLimit = getBundleCardStraightSerialMaxLimit();
            $plySum = \is_array($plys) && count($plys) ? \array_sum($plys) : 0;
            $validation = $plySum <= $this->bundleCardStraightSerialMaxLimit;
        }

        return $validation;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Total ply sum is greater than max limit $this->bundleCardStraightSerialMaxLimit.";
    }
}
