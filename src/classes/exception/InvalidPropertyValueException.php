<?php
namespace iutnc\nrv\exception;
class InvalidPropertyValueException extends \Exception{
    public function __construct($message = ""){
        parent::__construct($message);
    }
}
?>