<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Events;

class TransactionCompleted
{
    public $voucher;

    public function __construct($voucher)
    {
        $this->voucher = $voucher;
    }

    public function getItems()
    {
        return $this->voucher->details;
    }

    public function getVoucherId()
    {
        return $this->voucher->id;
    }

    public function getType()
    {
        return $this->voucher->type;
    }
}
