<?php

namespace iutnc\nrv\exception;

use Exception;

class InvalidPropertyNameException extends Exception
{
    public function __construct($message = "")
    {
        parent::__construct($message);

    }
}
