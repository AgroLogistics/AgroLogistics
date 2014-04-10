<?php
class App_CropController extends AgroLogistics_Controller_Action
{
    public function indexAction()
    {
        $shipmentComponent          = new AgroLogistics_Component_Crop();
    
        $result                     = $shipmentComponent->getCrops();
        
        if(is_array($result))
        { 
            $this->view->crops          = $result;
        }
        else
        {
            $this->view->crops = null;
        }
            
    }
    
    public function viewAction()
    {
        $shipmentComponent          = new AgroLogistics_Component_Crop();
    
        $result                     = $shipmentComponent->getCrop($this->getRequest()->getParam('cropId'));
        
        if(is_array($result))
        { 
            $this->view->crop          = $result;
        }
        else
        {
            $this->view->crop = null;
        }
            
    }
}