<?php

namespace SkylarkSoft\GoRMG\Inventory\Rules;

use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;

class YarnReceiveQtyRule extends YarnStockQtyRule
{
    protected $permitableQty = 0;

    public function __construct()
    {
        $this->setValues(request('receive_qty'));
    }
    public function passes($attribute, $value): bool
    {
        if ($value <= 0) {
            $this->message = "negative or zero not Allowed";
            return false;
        }
        if ($this->id) {
            $balance = $this->summary->balance;
            $oldValue = $this->receiveDetailOriginal->receive_qty;
            $this->permitableQty = $oldValue - $balance;
            if ($value < $oldValue && $value < $this->permitableQty) {
                $this->message = "Must Be Greater Than Or Equal $this->permitableQty";
                return false;
            }
        }

        return true;
    }
}
