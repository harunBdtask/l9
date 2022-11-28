<?php

namespace SkylarkSoft\GoRMG\Knitting\Rules;

use Illuminate\Contracts\Validation\Rule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramColorsQty;
use SkylarkSoft\GoRMG\Knitting\Models\YarnAllocationDetail;

class KnitProgramFabricColorQtyRule implements Rule
{
    private $msg = 'field is required';

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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function passes($attribute, $value): bool
    {
        $value = strtoupper($value);
        $attribute_split = explode('.', $attribute);
        $color = request()->get('item_color')[$attribute_split[1]];
        $max_program_qty = request()->get('max_program_qty')[$attribute_split[1]];

        $isNotMax = $value <= $max_program_qty;

        if ($isNotMax == true) {
            /**
             * Validation Check For : After yarn allocation if program color qty
             * updated less than total yarn allocation qty.
             */
            $programId = request()->route('knit_program_id');
            $colorQtyId = request()->get('id')[$attribute_split[1]];

            $yarnAllocation = YarnAllocationDetail::query()
                ->where('knitting_program_id', $programId)
                ->where('knitting_program_color_id', $colorQtyId)
                ->get();

            if (count($yarnAllocation) > 0) {
                $allocatedQty = $yarnAllocation->sum('allocated_qty');
                $this->msg = "$color : Program Qty must be equal or greater then Allocated Qty";
                return $value >= $allocatedQty;
            }
        }

        $this->msg = "$color : Program Qty must be less then Balance Qty";
        return $isNotMax;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->msg;
    }
}
