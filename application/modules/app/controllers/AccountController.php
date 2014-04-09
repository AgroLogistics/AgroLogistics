<?php
class App_AccountController extends Zend_Controller_Action
{
    public function loginAction()
    {
        $this->view->redirectUrl = $this->getBaseUrl() . '/app/crop';
        
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