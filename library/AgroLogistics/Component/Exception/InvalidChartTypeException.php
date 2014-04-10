<?php
class AgroLogistics_Component_Exception_InvalidChartTypeException extends Exception
{   
    protected $message = "The chart type requested is not implemented here.";
    protected $code    = "451";
}