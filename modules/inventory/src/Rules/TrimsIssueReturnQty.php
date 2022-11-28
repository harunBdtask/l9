<?php


namespace SkylarkSoft\GoRMG\Inventory\Rules;


use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsStockSummeryService;

class TrimsIssueReturnQty extends StockQtyRule
{

    public function passes($attribute, $value): bool
    {
        $this->setValues($attribute, $value);

        /* Max issue return qty is equals to issue qty*/
        if ( $this->summary->issue_qty - $this->summary->issue_return_qty >= $value) {
            return true;
        }

        $this->setMessage('Return Qty Can not be greater than Issue qty');
        return false;
    }

    public function message()
    {
        return $this->message;
    }
}
