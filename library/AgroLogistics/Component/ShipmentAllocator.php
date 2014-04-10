<?php
class AgroLogistics_Component_ShipmentAllocator extends AgroLogistics_Component_ComponentAbstract
{
    public function getShippingOptionsToDestination($buyerLocation)
    {
        $data = array();
        
        //process input
        $shipDataResponse       = $this->callApi( 'http://' . $this->getDomain() . ':' . $_SERVER['SERVER_PORT'] . $this->getBaseUrl() . "/data/shipdata.json" );
        
        //verify input
        if(empty($buyerLocation))
        {
            throw new AgroLogistics_Component_Exception_InvalidInputException("'buyerLocation' should not be empty.");
        }

        //verify input
        $shipData = $shipDataResponse['data']['responseBody'];
        
        if(is_array($shipData))
        {
            foreach($shipData as $ship)
            {
                $shippingOptions = $this->getShippingOptions($ship['route'], $buyerLocation, $ship['vesselName']);

                if(!empty($shippingOptions))
                {
                    $data[] = $shippingOptions;
                }
            }
        }
        
        return $data;        
    }
    
    public function getShippingOptions($route, $destination, $vesselName, $source = "KIN")
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
            
            $sourceFound        = strtoupper($segment['portName']) == strtoupper($source) ? true : $sourceFound;
            $destinationFound   = strtoupper($segment['portName']) == strtoupper($destination) ? true : $destinationFound;
            
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