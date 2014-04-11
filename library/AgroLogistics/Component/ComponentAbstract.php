<?php

abstract class AgroLogistics_Component_ComponentAbstract
{
    protected function callApi($url, $parameters = array(), $method = "POST")
    {
        return $this->callApi2($url, $parameters, $method);
        
        $responseData               = array();
        $responseData['result']     = 'failure';
        $responseData['data']       = array();
        $responseData['errors']     = array();
        
        $client = new \Zend_Http_Client($url, array(
                    'timeout'      => 30
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
    
    protected function callApi2($url, $parameters = array(), $method = "POST")
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
                    'Accept: text/html,application/json;q=0.9,*/*;q=0.8', // optional
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

                $responseData['data']['status']             = '';               //;$response->getStatus();
                $responseData['data']['headers']            = '';               //$response->getHeaders();
                $responseData['data']['responseBody']       = $responseBody;    //$response->getBody();
                
            }
        } 
        catch (HttpException $ex) 
        {
            $responseData['errors'] = $responseData['errors'] . ' || ' . $ex->getMessage();
        }

        if(!empty($responseData['data']['responseBody']) && $responseData['data']['responseBody'] != null)
        {
            try
            {                
                $responseData['data']['responseBody'] = Zend_Json::decode($responseData['data']['responseBody'], true);
            } 
            catch (Exception $ex) 
            {
                $responseData['errors'] = $responseData['errors'] . ' || ' . $ex->getMessage();
            }
        }
        
        
        return $responseData;
    }
    
    protected function callApi3($url, $parameters = array(), $method = "POST")
    {
        $responseData               = array();
        $responseData['result']     = 'failure';
        $responseData['data']       = array();
        $responseData['errors']     = array();
        
        try 
        {
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

                $responseData['data']['status']             = '';               //;$response->getStatus();
                $responseData['data']['headers']            = '';               //$response->getHeaders();
                $responseData['data']['responseBody']       = $responseBody;    //$response->getBody();
                
            }
        } 
        catch (HttpException $ex) 
        {
            $responseData['errors'] = $responseData['errors'] . ' || ' . $ex->getMessage();
        }
        
        if(!empty($responseData['data']['responseBody']) && $responseData['data']['responseBody'] != null)
        {      
            try
            {                
                $responseData['data']['responseBody'] = Zend_Json::decode($responseData['data']['responseBody'], true);
            } 
            catch (Exception $ex) 
            {
                $responseData['errors'] = $responseData['errors'] . ' || ' . $ex->getMessage();
            }
        }
        
        return $responseData;
    }
    
    protected function processOutput($outputData)
    {                
        return Zend_Json::encode($outputData);
    }
    
    protected function getBaseUrl()
    {       
        if(!isset($this->config))
        {
            $this->config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        }
        
        return $this->config->applicationSettings->baseUrl;
    }
    
    protected function getDomain()
    {       
        if(!isset($this->config))
        {
            $this->config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        }
        
        return $this->config->applicationSettings->domain;
    }
}