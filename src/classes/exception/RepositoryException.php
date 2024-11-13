<?php

namespace iutnc\nrv\exception;

class RepositoryException extends \Exception
{
    public function __construct($message = ""){
        parent::__construct($message);
    }
}