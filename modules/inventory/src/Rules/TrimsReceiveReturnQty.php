<?php


namespace SkylarkSoft\GoRMG\Inventory\Rules;


use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceiveReturnDetail;

class TrimsReceiveReturnQty extends StockQtyRule
{

    public function passes($attribute, $value): bool
    {
        $this->setValues($attribute, $value);

        if ( !$this->id ) {
            $this->setMessage('Insufficient balance!');
            return $value <= $this->summary->balance;
        }

        $detail = TrimsReceiveReturnDetail::find($this->id);

        if (! $detail) {
            $this->setMessage('Detail is not found for this id - ( ' . $this->id . ' )');
            return false;
        }

        $this->setMessage('Can not be edited!');
        return $detail->return_qty == $value;

    }

    public function message()
    {
        return $this->message;
    }
}
