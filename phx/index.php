<?php
// set error reporting level
error_reporting(E_ALL);

// include dBug class
include('./core/debug/dBug.php');

// define paths of folders
define('DS', DIRECTORY_SEPARATOR);
define('PATH_BASE', __DIR__ . DS);
define('PATH_APP', PATH_BASE . 'app' . DS); 
define('PATH_ASSETS', PATH_BASE . 'assets' . DS); 
define('PATH_CORE', PATH_BASE . 'core' . DS); 
define('PATH_CORE_LIBS', PATH_BASE . 'core' . DS . 'libs' . DS);

// define paths of files
define('EXT', '.php');
define('FILE_BASE', PATH_BASE . 'index' . EXT);

// load configuration files
$config_files = glob(PATH_APP . 'config' . DS . '*' . EXT);

// retrieve configuration array
$config = array();
foreach ($config_files as $config_file) {
	$key = substr(strrchr($config_file, DS), 1, -strlen(EXT));
	$config[$key] = include($config_file);
	unset($key);
	unset($config_file);
}
unset($config_files);

// load config class, intitialize configuration array
include(PATH_CORE_LIBS . 'config.php');
$CFG= new Config($config);
unset($config);

$CFG::parse('application.environment');
new dBug($CFG::$items);
new dBug($CFG::get('application'));
new dBug($CFG::get('application.environment'));



new dBug($GLOBALS);

$constants = get_defined_constants(true);
new dBug($constants['user']);


