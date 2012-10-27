<?php
//要开源，让群里的高手们参与

/*
设计原则
- 约定优于配置原则
- KISS原则（极简主义）
- 灵活性/非灵活性
- 内置firephp调试
- 优秀的代码书写体验
- 先进性：优质的思想，优质的代码架构
- 易读性：不易读的代码不要
- 表达性：借鉴laravel，方法的操纵让人直接明白它要做的意思
*/

// /home/phoenix/public_html/gitprojects/phpframework/phx/index.php

error_reporting(E_ALL);

define('DS', DIRECTORY_SEPARATOR);
define('PATH_BASE', realpath(''));
define('PATH_APP', PATH_BASE . DS . 'app'); 
define('PATH_ASSETS', PATH_BASE . DS . 'assets'); 
define('PATH_CORE', PATH_BASE . DS . 'core'); 

define('EXT', '.php');
define('FILE_BASE', PATH_BASE . DS . 'index' . EXT);


require PATH_APP . DS . 'config' . DS . 'application' . EXT;

include('./core/debug/dBug.php');
new dBug(PATH_APP . DS . 'config' . DS . 'application' . EXT);
/// ------ ////

die;

define('SERVER_ROOT' , '/home/phoenix/public_html/gitprojects/phpframework/fw-johnsquibb/');
define('SITE_ROOT' , 'http://173.230.150.168/gitprojects/phpframework/fw-johnsquibb/');

require_once(SERVER_ROOT . '/controllers/' . 'router.php');



/// ------ ////



require '../paths.php';


// --------------------------------------------------------------
// Launch Laravel.
// --------------------------------------------------------------
require path('sys').'laravel.php';



/// ------ ////


	$system_path = 'system';


	$application_folder = 'application';

	// Path to the system folder
	define('BASEPATH', str_replace("\\", "/", $system_path));

	// Path to the front controller (this file)
	define('FCPATH', str_replace(SELF, '', __FILE__));

	// Name of the "system folder"
	define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

	//paygentモジュールの経路
	define('DIR_MODULES', dirname(__FILE__).'/application/modules/');
	//Soapエラー時の戻り値 systemerror
	define('SOAP_MESSAGETYPE_E', 'E');
	//Soapエラー時の戻り値 warning
	define('SOAP_MESSAGETYPE_W', 'W');
	// The path to the "application" folder
	if (is_dir($application_folder))
	{
		define('APPPATH', $application_folder.'/');
	}
	else
	{
		if ( ! is_dir(BASEPATH.$application_folder.'/'))
		{
			exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
		}

		define('APPPATH', BASEPATH.$application_folder.'/');
	}




require_once BASEPATH.'core/CodeIgniter.php';
