<?php
class Zend_View_Helper_BaseUrl {

      function baseUrl() 
    {
        $fc = Zend_controller_front::getInstance()->getBaseUrl();
        return $fc->getBaseUrl();
      }

}