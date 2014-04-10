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
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        
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
                throw new InvalidInputException();
            }
            
            //generate output

            // Create a graph instance
            switch($chartType)
            {
                case 'line':
                case 'scatter':
                case 'bar':
                    $graph = new Graph($width, $height);
                    
                    // Specify what scale we want to use,
                    // int = integer scale for the X-axis
                    // int = integer scale for the Y-axis
                    $graph->SetScale('intint');

                    // Setup titles and X-axis labels
                    $graph->xaxis->title->Set($xAxisTitle);

                    // Setup Y-axis title
                    $graph->yaxis->title->Set($yAxisTitle);
                    break;
                
                case 'pie':
                    $graph = new PieGraph($width, $height);
                    break;
                
                default:
                    throw new InvalidChartTypeException();
            }
            
            // Setup a title for the graph
            $graph->title->Set($title);
            
            $ydata = $chartingData;

            // Create the linear plot
            switch($chartType)
            {
                case 'line':
                    $graphObject = new LinePlot($ydata);
                    break;
            
                case 'scatter':
//                    $graph->SetScale('linlin'); 
        
                    $graph->SetShadow(); 
                    
                    $graph->title->SetFont(FF_FONT1,FS_BOLD); 

                    $graphObject = new ScatterPlot($ydata); 
                    $graphObject->mark->SetType(MARK_FILLEDCIRCLE); 
                    $graphObject->mark->SetFillColor("red"); 
                    $graphObject->mark->SetWidth(5);       
                    break;
                    
                case 'bar':
//                    $graph->SetScale('linlin'); 
        
                    $graph->SetShadow(); 
                    
                    $graphObject = new BarPlot($ydata); 
                
                    break;
                
                case 'pie':
                    
                    $graphObject = new PiePlot3D($ydata); 
                    
                    break;
                
                default:
                    throw new InvalidChartTypeException();
            }

            // Add the plot to the graph
            $graph->Add($graphObject);

            // Display the graph
            $graph->Stroke();

            // Stream the result back as a PNG image
            header("Content-type: image/png");
        }
        catch(InvalidInputException $ex)
        {   
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = '450';
            $outputData['message']    = 'Error: The input data was empty or is not valid';
            
            echo $this->processOutput($outputData);
        }
        catch(InvalidChartTypeException $ex)
        {   
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = '450';
            $outputData['message']    = 'Error: The chart type specified is invalid';
            
            echo $this->processOutput($outputData);
        }
        catch(Exception $ex)
        {
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = '451';
            $outputData['message']    = 'Error: ' . $ex->getMessage();
            
            echo $this->processOutput($outputData);
        }
    }    
    
    /**
    * Generate Graph
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
            $cropDataResponse       = $this->callApi( 'http://' . $this->getDomain() . ':' . $_SERVER['SERVER_PORT'] . $this->getBaseUrl() . "/data/cropdata.json" );
            $shipDataResponse       = $this->callApi( 'http://' . $this->getDomain() . ':' . $_SERVER['SERVER_PORT'] . $this->getBaseUrl() . "/data/shipdata.json" );
            
            $requestData            = Zend_Json::decode($requestDataRaw, true);
            
            //process input
            $buyerLocation          = isset($requestData['buyerLocation']) ? $requestData['buyerLocation'] : '';
            //verify input
            if(empty($buyerLocation))
            {
                throw new InvalidInputException();
            }
            
            //verify input
            $cropData = $cropDataResponse['data']['responseBody'];
            $shipData = $shipDataResponse['data']['responseBody'];
                        
            if(is_array($shipData))
            {
                foreach($shipData as $ship)
                {
                    $shippingOptions = ShipmentAllocator::getShippingOptions($ship['route'], $buyerLocation, $ship['vesselName']);

                    if(!empty($shippingOptions))
                    {
                        $outputData['data'][] = $shippingOptions;
                    }
                }
            }
            
            //generate output
            echo $this->processOutput($outputData);
            
        }
        catch(InvalidInputException $ex)
        {   
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = '450';
            $outputData['message']    = 'Error: The input data was empty or is not valid';
            
            echo $this->processOutput($outputData);
        }
        catch(Exception $ex)
        {
            throw $ex;
            
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = '451';
            $outputData['message']    = 'Error: ' . $ex->getMessage();
            
            echo $this->processOutput($outputData);
        }
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
            
            $cropDataResponse       = $this->callApi( 'http://' . $this->getDomain() . ':' . $_SERVER['SERVER_PORT'] . $this->getBaseUrl() . "/data/cropdata.json" );
            $cropData               = $cropDataResponse['data']['responseBody'];
            
            
            
            $shippingOptionsResponse        = $this->callApi3( 'http://' . $this->getDomain() . ':' . $_SERVER['SERVER_PORT'] . $this->getBaseUrl() . "/api/index/get-shipping-options-to-destination", array('requestData' => Zend_Json::encode(array('buyerLocation' => $buyerLocation)) ) );
                        
            if($shippingOptionsResponse['result'] != 'failure')
            {
                $shippingOptions = $shippingOptionsResponse['data']['responseBody']['data'];
            }
            else
            {
                $shippingOptions = array();
            }
            
            //verify input
            if(empty($shippingOptions))
            {
                throw new NoDataFoundException();
            }
            
            //verify input            
            foreach($shippingOptions as $vesselOptions)
            {                        
                foreach($vesselOptions as $option)
                {
                    $vesselOptionShippingDuration = $option['shippingDuration'];
                    
                    foreach($cropData as $cropDataItem)
                    {
                        if($cropType && strtoupper($cropType) != strtoupper($cropDataItem['cropType']))
                        {
                            continue;
                        }
                        
                        foreach($cropDataItem['harvests'] as $harvest)
                        {                    
                            //if shipping date is between the harvest dates
                            $cropReapDateStart  = new Zend_Date($harvest['availableDateStart'], 'dd/MM/yyyy');
                            $cropReapDateEnd    = new Zend_Date($harvest['availableDateEnd'], 'dd/MM/yyyy');
                            
                            $shippingDate       = new Zend_Date($option['shippingDate'], 'dd/MM/yyyy');
                            
                            if(($cropReapDateStart->isLater($shippingDate) || $cropReapDateEnd->isEarlier($shippingDate) ))
                            {
                                continue;
                            }
                            
                            if($cropDataItem['shelfLife'] > $vesselOptionShippingDuration)
                            {
                                $quantityAvailable = min(
                                                            $option['maximumContainersAvailable'] * 10000, 
                                                            $harvest['quantity']
                                                        );

                                $outputData['data'][] = array(
                                    'cropType'          => $cropDataItem['cropType'],
                                    'maximumQuantity'   => $quantityAvailable,
                                    'dateAvailable'     => $option['arrivalDate']
                                );
                            }
                            else
                            {
                                //die('would perish');
                            }
                        }
                    }
                }
            }
            
            
            //generate output
            if(!empty($outputData['data']))
            {
                $outputData['code'] = '200';
                
                echo $this->processOutput($outputData);
            }
            else
            {
                throw new NoDataFoundException();
            }
            
        }
        catch(InvalidInputException $ex)
        {   
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = '450';
            $outputData['message']    = 'Error: The input data was empty or is not valid';
            
            echo $this->processOutput($outputData);
        }
        catch(NoDataFoundException $ex)
        {   
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = '451';
            $outputData['message']    = 'Error: No data was found for the criteria specified.';
            
            echo $this->processOutput($outputData);
        }
        catch(Exception $ex)
        {
            throw $ex;
            
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = '451';
            $outputData['message']    = 'Error: ' . $ex->getMessage();
            
            echo $this->processOutput($outputData);
        }
    }
}

class ShipmentAllocator
{
    public static function getShippingOptions($route, $destination, $vesselName, $source = "KIN")
    {
        $sourceFound                    = false;
        $destinationFound               = false;
        
        $sourceIndex                    = false;
        $destinationIndex               = false;
        $maximumContainersAvailable     = 0;
        
        $shippingOptions                = array();
        
        $possibleShippingDate           = null;
        $possibleArrivalDate            = null;
        
        for($i = 0; is_array($route) && $i < count($route); $i++)
        {
            $segment = $route[$i];
            
            $sourceFound        = $segment['portName'] == $source ? true : $sourceFound;
            $destinationFound   = $segment['portName'] == $destination ? true : $destinationFound;
            
            if($destinationFound === false)
            {
                $maximumContainersAvailable = $segment['containerSpaces'] < $maximumContainersAvailable ? $segment['containerSpaces'] : $maximumContainersAvailable;
            }
            
            if($sourceFound === true && $destinationFound === false) //if currently at the source
            {
                $sourceFound = true;
                $sourceIndex = $i;
                
                $maximumContainersAvailable     = $segment['containerSpaces'];
                
                $possibleShippingDate           = $segment['landingDate'];
                
            }
            else if($destinationFound !== false && $sourceFound === false) //currently at the destination but not the source
            {
                //ignore and move on
                
                continue;
            }
            else if($destinationFound !== false && $sourceFound !== false) 
            {
                //save the destination
                $destinationFound          = true;
                $destinationIndex          = $i;
                
                $possibleArrivalDate       = $segment['landingDate'];
                
                $shippingDateObject        = $dateObject = new Zend_Date($possibleShippingDate, 'dd/MM/yyyy');
                $arrivalDateObject         = $dateObject = new Zend_Date($possibleArrivalDate, 'dd/MM/yyyy');
                
                $diff                      = $arrivalDateObject->sub($shippingDateObject)->toValue();
                $shippingDuration          = ceil( $diff / 60.0 / 60.0 / 24.0 ) + 1;
            
                $shippingOptions[]         = array(
                                                    'vesselName' => $vesselName,
                                                    'shippingDate' => $possibleShippingDate,
                                                    'arrivalDate' => $possibleArrivalDate,
                                                    'shippingDuration' => $shippingDuration,
                                                    'maximumContainersAvailable' => $maximumContainersAvailable         
                );
                
                //found a source-destination, now reset;
                $sourceFound                    = false;
                $destinationFound               = false;
                $maximumContainersAvailable     = 0;      
                
                
            }
            
        }
        
        return $shippingOptions;
    }
}

class CropAvailability
{
    public static function getShippingOptions($route, $destination, $vesselName, $source = "KIN")
    {
        $sourceFound                    = false;
        $destinationFound               = false;
        
        $sourceIndex                    = false;
        $destinationIndex               = false;
        $maximumContainersAvailable     = 0;
        
        $shippingOptions                = array();
        
        $possibleShippingDate           = null;
        $possibleArrivalDate            = null;
        
        for($i = 0; is_array($route) && $i < count($route); $i++)
        {
            $segment = $route[$i];
            
            $sourceFound        = $segment['portName'] == $source ? true : $sourceFound;
            $destinationFound   = $segment['portName'] == $destination ? true : $destinationFound;
            
            if($destinationFound === false)
            {
                $maximumContainersAvailable = $segment['containerSpaces'] < $maximumContainersAvailable ? $segment['containerSpaces'] : $maximumContainersAvailable;
            }
            
            if($sourceFound === true && $destinationFound === false) //if currently at the source
            {
                $sourceFound = true;
                $sourceIndex = $i;
                
                $maximumContainersAvailable     = $segment['containerSpaces'];
                
                $possibleShippingDate           = $segment['landingDate'];
                
            }
            else if($destinationFound !== false && $sourceFound === false) //currently at the destination but not the source
            {
                //ignore and move on
                
                continue;
            }
            else if($destinationFound !== false && $sourceFound !== false) 
            {
                //save the destination
                $destinationFound          = true;
                $destinationIndex          = $i;
                
                $possibleArrivalDate       = $segment['landingDate'];
                
                $shippingDateObject        = $dateObject = new Zend_Date($possibleShippingDate, 'dd/MM/yyyy');
                $arrivalDateObject         = $dateObject = new Zend_Date($possibleArrivalDate, 'dd/MM/yyyy');
                
                $diff                      = $arrivalDateObject->sub($shippingDateObject)->toValue();
                $shippingDuration          = ceil( $diff / 60.0 / 60.0 / 24.0 ) + 1;
            
                $shippingOptions[]         = array(
                                                    'vesselName' => $vesselName,
                                                    'shippingDate' => $possibleShippingDate,
                                                    'arrivalDate' => $possibleArrivalDate,
                                                    'shippingDuration' => $shippingDuration,
                                                    'maximumContainersAvailable' => $maximumContainersAvailable
                                                    
                );
                
                //found a source-destination, now reset;
                $sourceFound                    = false;
                $destinationFound               = false;
                $maximumContainersAvailable     = 0;      
                
                
            }
            
        }
        
        return $shippingOptions;
    }
}
