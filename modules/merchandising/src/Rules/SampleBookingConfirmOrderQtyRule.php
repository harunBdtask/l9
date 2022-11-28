<?php

namespace SkylarkSoft\GoRMG\Merchandising\Rules;


use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisitionFabricDetail;

class SampleBookingConfirmOrderQtyRule implements Rule
{

    public $message;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {

        $index = explode('.', $attribute)[0];

        $fabricDetailId = request("$index.requisition_detail_id");
        $gmtsColorId = request("$index.gmts_color_id");

        try {
            $detail = SampleRequisitionFabricDetail::findOrFail($fabricDetailId);

            if (isset($detail->details)) {
                $reqQty = collect($detail->details)
                    ->where('color_id', $gmtsColorId)
                    ->sum('grey_qty');

                if ($reqQty >= $value) {
                    return true;
                }
            }

            return false;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return $this->message;
    }
}