<?php
//这是我写的框架，名称叫做phx
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

error_reporting(E_ALL);

include('./core/debug/dBug.php');
$arr = array('apple', 'banana'=>array('a' => 123 , 'b' => 456), 'pine apple');
new dBug($arr); 

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



	// Set the current directory correctly for CLI requests
	if (defined('STDIN'))
	{
		chdir(dirname(__FILE__));
	}

	if (realpath($system_path) !== FALSE)
	{
		$system_path = realpath($system_path).'/';
	}

	// ensure there's a trailing slash
	$system_path = rtrim($system_path, '/').'/';

	// Is the system path correct?
	if ( ! is_dir($system_path))
	{
		exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
	}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
	// The name of THIS file
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// The PHP file extension
	// this global constant is deprecated.
	define('EXT', '.php');

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