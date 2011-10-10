<?php

/* For logging PHP errors */
include_once('../../lib/confs/log_settings.php');

/* Added for compatibility with current orangehrm code 
 * OrangeHRM Root directory 
 */
define('ROOT_PATH', dirname(__FILE__) . '/../../');
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
define('WPATH', $scriptPath . "/../../");

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('orangehrm', 'prod', true);
sfContext::createInstance($configuration)->dispatch();
