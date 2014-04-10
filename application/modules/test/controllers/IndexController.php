<?php
class Test_IndexController extends AgroLogistics_Controller_Action
{
    public function indexAction()
    {
        $this->view->test1 = $this->getBaseUrl() . "/test/index/test-generate-graph";
        $this->view->test2 = $this->getBaseUrl() . "/test/index/test-get-shipping-options-to-destination";
        $this->view->test3 = $this->getBaseUrl() . "/test/index/test-get-products-available";
    }
    
    public function testGenerateGraphAction()
    {
        $this->view->apiUrl = $this->getBaseUrl() . "/api/graph-generator/generate-graph";
    }
    
    public function testGetShippingOptionsToDestinationAction()
    {
        $this->view->apiUrl = $this->getBaseUrl() . "/api/ship/get-shipping-options-to-destination";
    }
    
    public function testGetProductsAvailableAction()
    {
        $this->view->apiUrl = $this->getBaseUrl() . "/api/crop/get-products-available";
    }
}