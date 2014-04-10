<?php
class App_ShipController extends AgroLogistics_Controller_Action
{
    public function indexAction()
    {   
        $shipmentComponent          = new AgroLogistics_Component_Ship();
    
        $result                     = $shipmentComponent->getShips();
        
        if(is_array($result))
        { 
            $this->view->ships          = $result;
        }
        else
        {
            $this->view->ships = null;
        }
    }
    
    public function viewAction()
    {
        $shipmentComponent          = new AgroLogistics_Component_Ship();
    
        $result                     = $shipmentComponent->getShip($this->getRequest()->getParam('shipId'));
        
        if(is_array($result))
        { 
            $this->view->ship          = $result;
        }
        else
        {
            $this->view->ship = null;
        }
            
    }
}