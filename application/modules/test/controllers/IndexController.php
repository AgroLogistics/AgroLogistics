<?php
class Test_IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->view->test1 = $this->getBaseUrl() . "/test/index/test-generate-graph";
        $this->view->test2 = $this->getBaseUrl() . "/test/index/test-get-shipping-options-to-destination";
        $this->view->test3 = $this->getBaseUrl() . "/test/index/test-get-products-available";
    }
    
    public function testGenerateGraphAction()
    {
        $this->view->apiUrl = $this->getBaseUrl() . "/api/index/generate-graph";
    }
    
    public function testGetShippingOptionsToDestinationAction()
    {
        $this->view->apiUrl = $this->getBaseUrl() . "/api/index/get-shipping-options-to-destination";
    }
    
    public function testGetProductsAvailableAction()
    {
        $this->view->apiUrl = $this->getBaseUrl() . "/api/index/get-products-available";
    }
    
    private function getBaseUrl()
    {       
        if(!isset($this->config))
        {
            $this->config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        }
        
        return $this->config->applicationSettings->baseUrl;
    }
}