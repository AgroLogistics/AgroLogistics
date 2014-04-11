<?php

session_start();

function myErrorHandler($errno, $errstr, $errfile, $errline) {
//    throw new Exception("$errstr $errfile $errline", $errno);
    
}

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
if(defined('SERVER_SOFTWARE') || defined('IS_GAE') || isset($_SERVER['IS_GAE']))
{
    define('APPLICATION_ENV', 'gae');
    
    set_error_handler("myErrorHandler");
}
else
{
    defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

}

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$bootstrap = $application->bootstrap();

$zendConfiguration = $bootstrap->getOptions(); 

$application->run();