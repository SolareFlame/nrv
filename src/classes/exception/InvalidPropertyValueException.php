<?php

namespace iutnc\nrv\exception;

use Exception;

class InvalidPropertyValueException extends Exception
{
    public function __construct($message = "")
    {
        parent::__construct($message);
    }
}
