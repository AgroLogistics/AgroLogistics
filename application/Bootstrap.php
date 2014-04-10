<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function __construct($application)
    {
        parent::__construct($application);
        
        Zend_Layout::startMvc();
        
    }
    
    protected function _initDoctype()
    {
        $this->bootstrap('view');

        $view = $this->getResource('view');

        $view->doctype('XHTML1_STRICT');
    }
    
    function _initCache() {

        $frontendDriver = 'Core';
        $frontendOptions = array(
            'lifetime' => 7200, // cache lifetime of 2 hours
            'automatic_serialization' => true
        );

        $backendDriver      = extension_loaded('memcached') ? 'Memcached' : 'File';
        $backendOptions     = array();
        
//        if(isset($_SERVER['IS_GAE']) && $backendDriver == 'File')
//        {
//            
//            $backendDriver = new \AgroLogistics_Cache_Backend_File();
//            
////            var_dump($backendDriver);die('fafa');
//        } 
        
//        if($backendDriver == 'File')
//        {
//            $file = Zend\Cache\File::factory(array(
//                'adapter' => array(
//                    'name'    => 'filesystem',
//                    'options' => array(
//                        'cache_dir' => 'gs://cache/',
//                        'dir_level' => 0,
//                    )
//                ),
//                'plugins' => array(
//                    'exception_handler' => array(
//                        'throw_exceptions' => false
//                    ),
//                    'Serializer'
//                )
//            ));
//        }

        // getting a Zend_Cache_Core object
        $cache = Zend_Cache::factory($frontendDriver, $backendDriver, $frontendOptions, $backendOptions);
//        $cache = Zend_Cache::factory($backendDriver, $backendDriver, $backendOptions, $backendOptions);
        
        Zend_Registry::set('Zend_Cache', $cache);
    }

}

