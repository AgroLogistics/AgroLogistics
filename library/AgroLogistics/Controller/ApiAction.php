<?php

class AgroLogistics_Controller_ApiAction extends AgroLogistics_Controller_Action
{
    public function init()
    {   
        parent::init();
        
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }        
    
    public function indexAction()
    {
        
    }    
}