<?php

namespace SkylarkSoft\GoRMG\Inventory\Exceptions;

use Throwable;

class InvalidBookingNoException extends \Exception
{
    public function __construct($message = 'Booking No is invalid!', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}