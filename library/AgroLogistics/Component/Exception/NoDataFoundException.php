<?php
class AgroLogistics_Component_Exception_NoDataFoundException extends Exception
{
    protected $message = "No data was found for the input criteria specified.";
    protected $code    = "453";
    
    public function __construct($message, $code, $previous)
    {
        parent::__construct($message, $code, $previous);
        
        AgroLogistics_Component_ErrorHandling::logError($this->getMessage());
    }    
}