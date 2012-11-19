<?php
function _PhxErrorHandler ($errorNo, $errStr, $errFilePath, $errLine)
{
    echo '错误：' . $errorNo . '<br />';
    echo '信息：' . $errStr . '<br />';
    echo '行号：' . $errLine . '<br />';
    echo '位置：' . $errFilePath . '<br />';
}