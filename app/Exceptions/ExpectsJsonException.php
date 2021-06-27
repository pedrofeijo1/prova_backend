<?php

namespace App\Exceptions;

use Exception;

class ExpectsJsonException extends Exception
{
    protected $message = "Unexpected header [Accept: application/json] is present on response.";
}
