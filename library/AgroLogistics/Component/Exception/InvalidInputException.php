<?php
class AgroLogistics_Component_Exception_InvalidInputException extends Exception
{    
    protected $message = "The input data is either incomplete or missing.";
    protected $code    = "450";
    
    public function __construct($message)
    {
        parent::__construct($message);
        
        AgroLogistics_Component_ErrorHandling::logError($this->getMessage());
    }
}