<?php
class AgroLogistics_Component_ErrorHandling extends AgroLogistics_Component_ComponentAbstract
{
    
    private function _logError($message, $component = "")
    {
        if(!empty($component))
        {
            $component = "::" . $component;
        }
        
        //process input
        $errorLoggingResponse       = $this->callApi('http://uwi-has.appspot.com/v1/error/', array('message' => $message, 'metadata' => Zend_Json::encode(array('description' => '', 'component' => "AgroLogistics" . $component )) ));
        
        return $errorLoggingResponse;        
    }   
    
    public static function logError($message, $component = "")
    {
        $logger = new AgroLogistics_Component_ErrorHandling();
        
        return $logger->_logError($message, $component);
    }
}