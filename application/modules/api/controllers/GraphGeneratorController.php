<?php

class Api_GraphGeneratorController extends AgroLogistics_Controller_ApiAction
{
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
        
        $requestDataRaw = $this->getRequest()->getParam('requestData');
        
        $outputData = array(
                                'code' => null, 
                                'data' => null, 
                                'debug' => null, 
                                'data' => null, 
                                'message' => null
                            );
            
        try
        {
//            var_dump($requestDataRaw);die();
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
}