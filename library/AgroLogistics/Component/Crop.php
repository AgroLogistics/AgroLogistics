<?php
class AgroLogistics_Component_Crop extends AgroLogistics_Component_ComponentAbstract
{
    public function getCrops()
    {
        $result                     = $this->callApi( 'http://' . $this->getDomain() . ':' . $_SERVER['SERVER_PORT'] . $this->getBaseUrl() . "/data/cropdata.json" );
        
        $data                       = array();
        
        if($result['result'] == 'success' && is_array($result['data']))
        { 
            $data                       = $result['data']['responseBody'];
        }
        else
        {
            
        }
        
        return $data;
    }
    
    public function getCrop($id)
    {
        $result                     = $this->callApi( 'http://' . $this->getDomain() . ':' . $_SERVER['SERVER_PORT'] . $this->getBaseUrl() . "/data/cropdata.json" );
        
        $data                       = array();
        
        if($result['result'] == 'success' && is_array($result['data']))
        {             
            foreach($result['data']['responseBody'] as $item)
            {
                if($item['cropId'] == $id)
                {
                    return $item;
                }
            }
        }
        else
        {
            
        }
        
        //raise a quiet error
        $ex = new AgroLogistics_Component_Exception_NoDataFoundException("No crop with id '$id' was found.");
        
        return $data;
    }
    
    public function getCropsAvailableToDestination($buyerLocation, $cropType)
    {
        $data = array();
        
        $cropData = $this->getCrops();

        $shippingOptionsResponse            = $this->callApi3( 'http://' . $this->getDomain() . ':' . $_SERVER['SERVER_PORT'] . $this->getBaseUrl() . "/api/ship/get-shipping-options-to-destination", array('requestData' => Zend_Json::encode(array('buyerLocation' => $buyerLocation)) ) );
                
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
            throw new AgroLogistics_Component_Exception_NoDataFoundException();
        }
        
        if(is_array($shippingOptions) && is_array($cropData))       
        {
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

                                $data[] = array(
                                    'cropId'          => $cropDataItem['cropId'],
                                    'cropType'          => $cropDataItem['cropType'],
                                    'maximumQuantity'   => $quantityAvailable,
                                    'dateAvailable'     => $option['arrivalDate']
                                );
                            }
                            else
                            {
                            }
                        }
                    }
                }
            }
        }
        
        return $data;
    }
}
