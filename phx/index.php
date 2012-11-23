<?php
/**
 * Phx - A Micro PHP Framework For Beginners With Some Cool Features
 *
 * @author   PHOENIX <gopher.huang@gmail.com>
 * @link     https://github.com/phoenixg/phpframework
 */


/*
 *---------------------------------------------------------------
 * SET TIMEZONE
 *---------------------------------------------------------------
 */
date_default_timezone_set('Asia/Shanghai');

/*
 *---------------------------------------------------------------
 * INCLUDE dBug
 *---------------------------------------------------------------
 */
include('./core/debug/dBug.php');

/*
 *---------------------------------------------------------------
 * INCLUDE ALL CONSTANTS WE NEED
 *---------------------------------------------------------------
 */
require('constants.php');

/*
 *---------------------------------------------------------------
 * SET ERROR REPORTING LEVEL TO ALL
 *---------------------------------------------------------------
 */
error_reporting(E_ALL);

/*
 *---------------------------------------------------------------
 * SET ERROR HANDLER
 *---------------------------------------------------------------
 * 
 * 全部预定义的Error常量对应的数值（即$errorNo）见：http://php.net/manual/en/errorfunc.constants.php
 * error发生时自动触发，或手动触发：trigger_error("发生了一个错误")
 */
set_error_handler(function ($errorNo, $errMsg, $errFilePath, $errLine){ 
    $logInfo = '['.date('Y-m-d H:i:s').'] Error: '.$errMsg.', on line: '.$errLine.', in file: '.$errFilePath.EOL;
    echo $logInfo;
    error_log($logInfo, 3, FILE_LOG_ERRORS);
});

/*
 *---------------------------------------------------------------
 * SET DEFAULT EXCEPTION HANDLER
 *---------------------------------------------------------------
 * 
 * For handling exceptions that are not caught by try...catch...
 * For instance, you could setup an exception_handler that logs all of the exceptions to file
 * 使用 throw new Exception('异常信息') 手动触发该异常处理
 */
set_exception_handler(function ($e) {
    $logInfo = '['.date("Y-m-d H:i:s").'] Exception on line: '.$e->getLine().', in file: '.$e->getFile()
               .', with message: '.$e->getMessage().EOL;
    echo $logInfo;
    error_log($logInfo, 3, FILE_LOG_ERRORS);
});

/*
 *---------------------------------------------------------------
 * SET CUSTOM EXCEPTION HANDLER FOR TRY...CATCH...
 *---------------------------------------------------------------
 * 
 *  try {
 *      throw new Phxexception("异常信息");
 *  } catch(Phxexception $e) {
 *      echo $e->getMsg();
 *  }
 */
class Phxexception extends Exception
{
    public function __construct($message) {
        parent::__construct($message);
    }

    public function getMsg()
    {
        $logInfo = '['.date("Y-m-d H:i:s").'] Custom Exception on line: '.$this->getLine().', in file: '.$this->getFile()
                   .', with message: '.$this->getMessage().EOL;
        error_log($logInfo, 3, FILE_LOG_ERRORS);
        return $logInfo;
    }
}

/*
 *---------------------------------------------------------------
 * INCLUDE ALL CORE FILES
 *---------------------------------------------------------------
 */
$lib_files = glob(PATH_CORE_LIBS . '*' . EXT);
foreach ($lib_files as $file) {
    require $file;
    unset($file);
}
unset ($lib_files);

/*
 *---------------------------------------------------------------
 * RETRIEVE CONFIGURATION ARRAY FROM CONFIGURATION FILES
 *---------------------------------------------------------------
 */
$config_files = glob(PATH_APP . 'config' . DS . '*' . EXT);
$config = array();
foreach ($config_files as $config_file) {
    $key = substr(strrchr($config_file, DS), 1, -strlen(EXT));
    $config[$key] = include($config_file);
    unset($key);
    unset($config_file);
}
unset($config_files);

/*
 *---------------------------------------------------------------
 * INITIALIZE CONFIG CLASS
 *---------------------------------------------------------------
 * Retrieve config item via: $CFG::get('application.aaa.ddd.eee');
 */
$CFG = new Phx\Config($config);
unset($config);

/*
 *---------------------------------------------------------------
 * INCLUDE IoC CONTAINER
 *---------------------------------------------------------------
 * Append your own IoC class in ioc.php file
 */
require('ioc'.EXT);

/*
 *---------------------------------------------------------------
 * RESOLVE ALL CLASSES
 *---------------------------------------------------------------
 * $test = IoC::resolve('classname');
 */




new dBug($GLOBALS);

/*
$constants = get_defined_constants(true);
new dBug($constants['user']);

*/

