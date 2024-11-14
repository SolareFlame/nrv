<?php

namespace iutnc\nrv\exception;

use Exception;

class RepositoryException extends Exception
{
    public function __construct($message = "")
    {
        parent::__construct($message);
    }
}