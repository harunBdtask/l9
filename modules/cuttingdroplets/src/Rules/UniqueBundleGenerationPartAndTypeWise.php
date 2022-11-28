<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use Request;

class UniqueBundleGenerationPartAndTypeWise implements Rule
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
        $partId = request()->get('part_id');

        $genDetailsData = BundleCardGenerationDetail::findOrFail(Request::segment(2));

        $genDetail = BundleCardGenerationDetail::where([
            'type_id' => $value,
            'part_id' => $partId,
            'sid' => $genDetailsData->sid
        ])->first();

       /* if (request()->route('id')) {
            $genDetail = $genDetail->where('id', '!=', request()->route('id'));
        }*/

        //$genDetail = $genDetail

        return $genDetail ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This part and type wise bundle generation already exist';
    }
}
