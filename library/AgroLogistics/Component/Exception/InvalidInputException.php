<?php
class AgroLogistics_Component_Exception_InvalidInputException extends Exception
{    
    protected $message = "The input data is either incomplete or missing.";
    protected $code    = "450";
    
    public function __construct($message = null)
    {
        parent::__construct($message ? $message : $this->message);
        
        AgroLogistics_Component_ErrorHandling::logError($this->getMessage());
    }
}