<?php

class Api_ShipController extends AgroLogistics_Controller_ApiAction
{
    /**
    * Get Shipping Options To Destination
    * @param array $requestData 
    * @return 
    */
    public function getShippingOptionsToDestinationAction()
    {   
        
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        
        $requestDataRaw = isset($_REQUEST['requestData']) && is_string($_REQUEST['requestData']) ? $_REQUEST['requestData'] : '{}';
        
        $outputData = array(
                                'code' => null, 
                                'data' => null, 
                                'debug' => null, 
                                'data' => null, 
                                'message' => null
                            );
            
        try
        {
            $requestData            = Zend_Json::decode($requestDataRaw, true);            
           
            //process input
            $buyerLocation          = isset($requestData['buyerLocation']) ? $requestData['buyerLocation'] : '';
            
            $shimentAllocator       = new AgroLogistics_Component_Ship();            
            $shippingOptions        = $shimentAllocator->getShippingOptionsToDestination($buyerLocation);
            
            $outputData['data']     = $shippingOptions;
               
        }
        catch(AgroLogistics_Component_Exception_InvalidInputException $ex)
        {   
//            $this->_response->setHttpResponseCode(422);            
            
            $outputData['code']       = $ex->getCode();
            $outputData['message']    = 'Error: ' . $ex->getMessage();
        }
        catch(Exception $ex)
        {
//            $this->_response->setHttpResponseCode(422);            
            
            $outputData['code']       = $ex->getCode();
            $outputData['message']    = 'Error: ' . $ex->getMessage();
            
        }
        
//        $outputData['message'] .= $requestDataRaw;
//        
//        var_dump($outputData);die();
        
        //generat output
        echo $this->processOutput($outputData);
    }  
}