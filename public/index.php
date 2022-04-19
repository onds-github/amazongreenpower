<?php

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library') . PATH_SEPARATOR .
    realpath(APPLICATION_PATH . '/models'),
    realpath(APPLICATION_PATH . '/modules/account/models'),
    realpath(APPLICATION_PATH . '/modules/admin/models'),
    realpath(APPLICATION_PATH . '/modules/development/models'),
    realpath(APPLICATION_PATH . '/modules/finance/models'),
    realpath(APPLICATION_PATH . '/modules/settings/models'),
    realpath(APPLICATION_PATH . '/modules/blog/models'),
    realpath(APPLICATION_PATH . '/modules/website/models'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
        APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini'
);

//Connect DataBase
$config = new Zend_Config_Ini('application/configs/application.ini', 'database');
$default = Zend_Db::factory($config->resources->default->adapter, $config->resources->default->config->toArray());
$wordpress = Zend_Db::factory($config->resources->wordpress->adapter, $config->resources->wordpress->config->toArray());
Zend_Db_Table_Abstract::setDefaultAdapter($default);
Zend_Registry::set('default', $default);
Zend_Registry::set('wordpress', $wordpress);

$application->bootstrap()
        ->run();
