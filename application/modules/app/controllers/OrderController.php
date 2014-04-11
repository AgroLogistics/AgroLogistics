<?php
class App_OrderController extends AgroLogistics_Controller_Action
{
    public function indexAction()
    {
        $domain = $this->getDomain();
        $baseUrl = $this->getBaseUrl();
        
        $cropComponent = new AgroLogistics_Component_Crop();
        
        $this->view->buyerLocation    = $this->getRequest()->getParam('buyerLocation');
        $this->view->cropType         = $this->getRequest()->getParam('cropType');   
        
        $result = null;
        
        if(!empty($this->view->buyerLocation))
        {
            try
            {
                $result = $cropComponent->getCropsAvailableToDestination($this->view->buyerLocation, $this->view->cropType);
            }
            catch(Exception $ex)
            {
            }
        }
        
        if(is_array($result))
        { 
            $this->view->crops          = $result;
        }
        else
        {
            $this->view->crops          = null;
        }
    }
    
    public function confirmOrderAction()
    {
        
    }
}