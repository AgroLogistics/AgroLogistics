<?php
class AgroLogistics_Component_Exception_InvalidInputException extends Exception
{    
    protected $message = "The input data is either incomplete or missing.";
    protected $code    = "450";
}