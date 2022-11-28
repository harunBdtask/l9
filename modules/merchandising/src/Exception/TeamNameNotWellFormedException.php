<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exception;

class TeamNameNotWellFormedException extends \Exception
{
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
