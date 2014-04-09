<?php
class App_IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_helper->redirector('login', 'account');
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