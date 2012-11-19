<?php
/**
 * error发生时自动触发，或手动触发：trigger_error("发生了一个错误")
 * 全部预定义的Error常量对应的数值（即$errorNo）见：http://php.net/manual/en/errorfunc.constants.php
 */

function _PhxErrorHandler ($errorNo, $errMsg, $errFilePath, $errLine)
{ 
    echo '错误：' . $errorNo . '<br />';
    echo '信息：' . $errMsg . '<br />';
    echo '行号：' . $errLine . '<br />';
    echo '位置：' . $errFilePath . '<br />';

    $logInfo = '[' . date('Y/m/d H:i:s') . ']' . ' ' . $errorNo . ' ' . $errMsg . ' On Line:' . $errLine . ' ' . $errFilePath . "\n";
    error_log($logInfo, 3, FILE_LOG_ERRORS);
}