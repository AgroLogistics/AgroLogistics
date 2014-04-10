<?php
class AgroLogistics_Component_Exception_InvalidChartTypeException extends Exception
{   
    protected $message = "The chart type requested is not implemented here.";
    protected $code    = "451";
    
    public function __construct($message)
    {
        parent::__construct($message ? $message : $this->message);
        
        AgroLogistics_Component_ErrorHandling::logError($this->getMessage(), 'GraphGenerator');
    }
}