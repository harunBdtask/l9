<?php

namespace SkylarkSoft\GoRMG\Inventory\Exceptions;

use Throwable;

class DateNotAvailableException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}