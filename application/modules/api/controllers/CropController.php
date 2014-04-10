<?php

class Api_CropController extends AgroLogistics_Controller_ApiAction
{     
    /**
    * Generate Graph
    * @param array $requestData 
    * @return 
    */
    public function getProductsAvailableAction()
    {   
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        
        $requestDataRaw = isset($_REQUEST['requestData']) && is_string($_REQUEST['requestData']) ? $_REQUEST['requestData'] : '{}';
        
        $outputData = array(
                                'code' => null, 
                                'data' => null, 
                                'debug' => null, 
                                'data' => array(), 
                                'message' => null
        );
            
        try
        {
            
            $requestData            = Zend_Json::decode($requestDataRaw, true);
            
            //process input
            $buyerLocation          = isset($requestData['buyerLocation']) ? $requestData['buyerLocation'] : '';
            $cropType               = isset($requestData['cropType']) ? $requestData['cropType'] : null;
            
            $cropAvailability       = new AgroLogistics_Component_Crop();
            
            $cropsAvailableForDestination = $cropAvailability->getCropsAvailableToDestination($buyerLocation, $cropType);
            
            //generate output
            if(!empty($cropsAvailableForDestination))
            {
                $outputData['code'] = '200';
                
                $outputData['data'] = $cropsAvailableForDestination;
                
                echo $this->processOutput($outputData);
            }
            else
            {
                throw new AgroLogistics_Component_Exception_NoDataFoundException();
            }
            
        }
        catch(AgroLogistics_Component_Exception_InvalidInputException $ex)
        {   
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = $ex->getCode();
            $outputData['message']    = 'Error: ' . $ex->getMessage();
            
            echo $this->processOutput($outputData);
        }
        catch(AgroLogistics_Component_Exception_NoDataFoundException $ex)
        {   
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = $ex->getCode();
            $outputData['message']    = 'Error: ' . $ex->getMessage();
            
            echo $this->processOutput($outputData);
        }
        catch(Exception $ex)
        {
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = $ex->getCode();
            $outputData['message']    = 'Error: ' . $ex->getMessage();
            
            echo $this->processOutput($outputData);
        }
    }
}