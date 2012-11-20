<?php
// set timzone
date_default_timezone_set('Asia/Shanghai');

// include dBug class
include('./core/debug/dBug.php');

require('constants.php');

// set error reporting level
error_reporting(E_ALL);

// set error handler
// 全部预定义的Error常量对应的数值（即$errorNo）见：http://php.net/manual/en/errorfunc.constants.php
// error发生时自动触发，或手动触发：trigger_error("发生了一个错误")
set_error_handler(function ($errorNo, $errMsg, $errFilePath, $errLine){ 
    echo '错误：' . $errorNo . '<br />';
    echo '信息：' . $errMsg . '<br />';
    echo '行号：' . $errLine . '<br />';
    echo '位置：' . $errFilePath . '<br />';

    $logInfo = '[' . date('Y/m/d H:i:s') . ']' . ' ' . $errorNo . ' ' . $errMsg . ' On Line:' . $errLine . ' ' . $errFilePath . "\n";
    error_log($logInfo, 3, FILE_LOG_ERRORS);
});


// set exception handler
set_exception_handler(function ($e) {
    echo $e;
    echo "Uncaught exception: " , $e->getMessage(), "\n";
});

throw new Exception('未捕获的异常');
echo "Not Executed\n";

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



// load config class, intitialize configuration mechanism
include(PATH_CORE_LIBS . 'config.php');
$CFG = new Phx\Config($config);
unset($config);



//echo $CFG::get('application.aaa.ddd.eee');

/*
new dBug($GLOBALS);

$constants = get_defined_constants(true);
new dBug($constants['user']);
*/
