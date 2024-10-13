<?php

namespace App\Exceptions;

use Exception;

class UnknownOSException extends Exception
{
    public function __construct($message = 'Unknown OS', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
