<?php

namespace App\Exceptions;

use Exception;

class NoAcceptLanguageException extends Exception
{
    public function __construct($message = 'No Accept Language', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
