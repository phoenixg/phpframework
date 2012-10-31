<?php
// 本框架教程页面： http://johnsquibb.com/tutorials/mvc-framework-in-1-hour-part-one
// http://173.230.150.168/gitprojects/phpframework/fw-johnsquibb/index.php?news&article=howtobuildaframework

error_reporting(E_ALL);

//linux
//define('SERVER_ROOT' , '/home/phoenix/public_html/gitprojects/phpframework/fw-johnsquibb/');
//define('SITE_ROOT' , 'http://173.230.150.168/gitprojects/phpframework/fw-johnsquibb/');
//require_once(SERVER_ROOT . '/controllers/' . 'router.php');

//windows
define('SERVER_ROOT' , 'E:\xampp\htdocs\phpframework\fw-johnsquibb\\');
define('SITE_ROOT' , 'http://localhost/phpframework/fw-johnsquibb/');

include('../share/debug/dBug.php');

require_once(SERVER_ROOT . '\controllers\\' . 'router.php');

// new dBug($GLOBALS);