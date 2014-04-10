<?php
class App_CropController extends Zend_Controller_Action
{
    public function indexAction()
    {
        
//        $cropDataResponse       = $this->callApi( 'http://uwi-agrologistics.appspot.com/api/index/get-shipping-options-to-destination' );
        $cropDataResponse       = $this->callApi( 'http://localhost/api/index/get-shipping-options-to-destination', array('requestData' =>
                                                                                                                                            array(
                                                                                                                                                'buyerLocation' => 'BEI'
                                                                                                                                            )
            ));
        
//        if($cropDataResponse['result'] === 'success')
//        {
            $this->view->data = $cropDataResponse['data'] = '{"code":null,"data":[[{"vesselName":"Titanic","shippingDate":"15\/03\/2014","arrivalDate":"25\/03\/2014","shippingDuration":11,"maximumContainersAvailable":"9"},{"vesselName":"Titanic","shippingDate":"15\/04\/2014","arrivalDate":"25\/04\/2014","shippingDuration":11,"maximumContainersAvailable":"10"}],[{"vesselName":"Carnival","shippingDate":"15\/03\/2014","arrivalDate":"25\/03\/2014","shippingDuration":11,"maximumContainersAvailable":"4"}]],"debug":null,"message":null} ';
//                    
//        }
        
            
    }
    
    private function callApi($url, $parameters = array(), $method = "POST")
    {
//        return $this->callApi2($url, $parameters, $method);
        
        $responseData               = array();
        $responseData['result']     = 'failure';
        $responseData['data']       = array();
        $responseData['errors']     = array();
        
        $client = new \Zend_Http_Client('' . $url, array(
//                    'maxredirects' => 0,
                    'timeout'      => 30,
//                    'adapter'   => new \Zend_Http_Client_Adapter_Curl(),
//                    'curloptions' => array(CURLOPT_FOLLOWLOCATION => true),
                    ));
        
        if(isset($parameters) && isset($parameters) && is_array($parameters))
        {
            foreach($parameters as $name => $value)
            {
                $client->setParameterPost($name, $value);
            }
        }

        $client->request($method);

        $response = $client->getLastResponse();
        
        if(!$response->isError())
        {
            $responseData['result'] = 'success';

            $responseData['data']['status']             = $response->getStatus();
            $responseData['data']['headers']            = $response->getHeaders();
            $responseData['data']['responseBody']       = $response->getBody();
        }
        
        return $responseData;
    }
    
    private function callApi2($url, $parameters = array(), $method = "POST")
    {
        $responseData               = array();
        $responseData['result']     = 'failure';
        $responseData['data']       = array();
        $responseData['errors']     = array();
        
        
        
        try {
            
            
            $data = http_build_query($parameters);
            
            $context = [
              'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", array(
                    'Content-type: application/x-www-form-urlencoded',
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8', // optional
                    'Accept-Language: en-us,en;q=0.5', // optional
                    'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7' // optional
                )),
                'content' => $data
              ]
            ];
            
            $context = stream_context_create($context);
            
            $responseBody = file_get_contents($url, FILE_TEXT, $context);
                        
            if(!empty($responseBody) && $responseBody != null)
            {
                $responseData['result'] = 'success';

                $responseData['data']['status']             = '';//;$response->getStatus();
                $responseData['data']['headers']            = '';//$response->getHeaders();
                $responseData['data']['responseBody']       = $responseBody; //$response->getBody();
            }
        } 
        catch (HttpException $ex) 
        {
            echo $ex;
        }
        
//        $client = new \Zend_Http_Client('' . $url, array(
////                    'maxredirects' => 0,
//                    'timeout'      => 30));
//        
//        if(isset($parameters) && isset($parameters) && is_array($parameters))
//        {
//            foreach($parameters as $name => $value)
//            {
//                $client->setParameterPost($name, $value);
//            }
//        }
//
//        $client->request($method);
//
//        $response = $client->getLastResponse();
//        
//        if(!$response->isError())
//        {
//            $responseData['result'] = 'success';
//
//            $responseData['data']['status']             = $response->getStatus();
//            $responseData['data']['headers']            = $response->getHeaders();
//            $responseData['data']['responseBody']       = $response->getBody();
//        }
        
        return $responseData;
    }
    
    private function processOutput($outputData)
    {                
        return json_encode($outputData);
    }
    
    private function getBaseUrl()
    {       
        if(!isset($this->config))
        {
            $this->config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        }
        
        return $this->config->applicationSettings->baseUrl;
    }
    
    private function getDomain()
    {       
        if(!isset($this->config))
        {
            $this->config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        }
        
        return $this->config->applicationSettings->domain;
    }
}