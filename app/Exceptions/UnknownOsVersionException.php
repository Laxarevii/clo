<?php

namespace App\Exceptions;

use Exception;

class UnknownOsVersionException extends Exception
{
    protected $message = 'Unknown OS version';
}
