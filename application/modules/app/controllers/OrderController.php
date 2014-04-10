<?php
class App_CropController extends AgroLogistics_Controller_Action
{
    public function indexAction()
    {
        $domain = $this->getDomain();
        $baseUrl = $this->getBaseUrl();
        
        $shipmentAllocator = new AgroLogistics_Component_Ship();
        
//        
//        
////        $cropDataResponse       = $this->callApi( 'http://uwi-agrologistics.appspot.com/api/index/get-shipping-options-to-destination' );
//        $cropDataResponse       = $this->callApi( "http://$domain$baseUrl/api/index/get-shipping-options-to-destination", array('requestData' =>
//                                                                                                                                            array(
//                                                                                                                                                'buyerLocation' => 'BEI'
//                                                                                                                                            )
//            ));
//        
////        if($cropDataResponse['result'] === 'success')
////        {
////            $this->view->data = $cropDataResponse['data'] = '{"code":null,"data":[[{"vesselName":"Titanic","shippingDate":"15\/03\/2014","arrivalDate":"25\/03\/2014","shippingDuration":11,"maximumContainersAvailable":"9"},{"vesselName":"Titanic","shippingDate":"15\/04\/2014","arrivalDate":"25\/04\/2014","shippingDuration":11,"maximumContainersAvailable":"10"}],[{"vesselName":"Carnival","shippingDate":"15\/03\/2014","arrivalDate":"25\/03\/2014","shippingDuration":11,"maximumContainersAvailable":"4"}]],"debug":null,"message":null} ';
////                    
////        }
//        
        
        $result = $shipmentAllocator->getShips();
        
        
        var_dump($result);die();
            
    }
}