<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;

class UniqueCuttingNo implements Rule
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
        $value = strtoupper($value);
        $buyerId = request()->get('buyer_id');
        $styleId = request()->get('style_id');
        $colorId = request()->get('color_id');

        $genDetail = BundleCardGenerationDetail::where('buyer_id', $buyerId)
            ->where('style_id', $styleId)
            ->where('cutting_no', $value)
            ->where('factory_id', \Auth::user()->factory_id);

        if (request()->route('id')) {
            $genDetail = $genDetail->where('id', '!=', request()->route('id'));
        }

        $genDetail = $genDetail->first();

        return $genDetail ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Cutting no should be unique';
    }
}
