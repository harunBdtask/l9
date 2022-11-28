<?php

namespace SkylarkSoft\GoRMG\DyesStore\Events;

class DyesTransactionCompleted
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

    public function getVoucher()
    {
        return $this->voucher;
    }
}
