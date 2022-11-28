<?php

namespace SkylarkSoft\GoRMG\Inventory\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveReturnDetail;

class YarnReceiveReturnQtyRule extends YarnStockQtyRule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $this->setValues($value);

        if (! $this->id) {
            $balance = $this->summary->balance;
            $this->message = 'Insufficient balance!';
            return $value <= $balance;
        }

        $detail = YarnReceiveReturnDetail::query()
            ->find($this->id);

        if (! $detail) {
            $this->message = 'Detail Not Found!';
            return false;
        }

        $this->message = 'Cannot Be Edited!';
        return $detail->return_qty == $value;
    }
}
