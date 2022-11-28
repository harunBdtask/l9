<?php

namespace SkylarkSoft\GoRMG\DyesStore\Exceptions;

class VoucherIdNullException extends \Exception
{
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
