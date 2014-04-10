<?php

class Api_IndexController extends AgroLogistics_Controller_ApiAction
{
    public function indexAction()
    {
        
    }    
    
    /**
    * Generate Graph
    * @param array $requestData 
    * @return image/png
    */
    public function generateGraphAction()
    {   
        
        require_once(APPLICATION_PATH . '/../library/' . "jpgraph-3.5.0b1/src/jpgraph.php");
        require_once(APPLICATION_PATH . '/../library/' . "jpgraph-3.5.0b1/src/jpgraph_line.php");
        require_once(APPLICATION_PATH . '/../library/' . "jpgraph-3.5.0b1/src/jpgraph_pie.php");
        require_once(APPLICATION_PATH . '/../library/' . "jpgraph-3.5.0b1/src/jpgraph_pie3d.php");
        require_once(APPLICATION_PATH . '/../library/' . "jpgraph-3.5.0b1/src/jpgraph_bar.php");
        require_once(APPLICATION_PATH . '/../library/' . "jpgraph-3.5.0b1/src/jpgraph_scatter.php");
        
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
            $title                  = isset($requestData['title']) ? $requestData['title'] : '';
            $chartingData           = isset($requestData['data']) && is_array($requestData['data']) ? $requestData['data'] : array();
            $xAxisTitle             = isset($requestData['xAxisTitle']) ? $requestData['xAxisTitle'] : '';
            $yAxisTitle             = isset($requestData['yAxisTitle']) ? $requestData['yAxisTitle'] : '';
            $width                  = isset($requestData['width']) ? $requestData['width'] : 600;
            $height                 = isset($requestData['height']) ? $requestData['height'] : 200;
            $chartType              = isset($requestData['chartType']) ? $requestData['chartType'] : 'line';

            //verify input
            if(empty($chartingData))
            {
                throw new AgroLogistics_Component_Exception_InvalidInputException("'data' to produce the chart cannot be empty.");
            }
            
            //generate output
            AgroLogistics_Component_GraphGenerator::generateGraph(  
                                                                    $title,          
                                                                    $chartingData,   
                                                                    $xAxisTitle,     
                                                                    $yAxisTitle,     
                                                                    $width,          
                                                                    $height,         
                                                                    $chartType  
                                                                 );
        }
        catch(AgroLogistics_Component_Exception_InvalidInputException $ex)
        {   
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = $ex->getCode();
            $outputData['message']    = 'Error: ' . $ex->getMessage();
        }
        catch(AgroLogistics_Component_Exception_InvalidChartTypeException $ex)
        {   
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = $ex->getCode();
            $outputData['message']    = 'Error: ' . $ex->getMessage();
        }
        catch(Exception $ex)
        {
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = $ex->getCode();
            $outputData['message']    = 'Error: ' . $ex->getMessage();
            
        }
        
        echo $this->processOutput($outputData);
    }    
    
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
            
            $shimentAllocator       = new AgroLogistics_Component_ShipmentAllocator();            
            $shippingOptions        = $shimentAllocator->getShippingOptionsToDestination($buyerLocation);
            
            $outputData['data']     = $shippingOptions;
               
        }
        catch(AgroLogistics_Component_Exception_InvalidInputException $ex)
        {   
            $this->_response->setHttpResponseCode(422);            
            
            $outputData['code']       = $ex->getCode();
            $outputData['message']    = 'Error: ' . $ex->getMessage();
        }
        catch(Exception $ex)
        {
            $this->_response->setHttpResponseCode(422);            
            
            $outputData['code']       = $ex->getCode();
            $outputData['message']    = 'Error: ' . $ex->getMessage();
            
        }
        
        //generat output
        echo $this->processOutput($outputData);
    }    
    
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
            
            $cropAvailability       = new AgroLogistics_Component_CropAvailability();
            
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