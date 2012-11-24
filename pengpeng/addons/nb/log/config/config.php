<?php
/**
 * please don't modify this config file, copy it to PROJECT_ROOT . "/config/config.php";
 */

$config['cacheFile'] = CACHE_ROOT.'log'.DIRECTORY_SEPARATOR.'log';
$config['enable'] = true;
$config['alwaysOpenForIp'] = array('127.0.0.1');

$config['formatValueFrom']['%day%'] = 'nbLogHelper::getDay';
$config['formatValueFrom']['%time%'] = 'nbLogHelper::getTime';
$config['formatValueFrom']['%microtime%'] = 'nbLogHelper::getMicrotime';
$config['formatValueFrom']['%app%'] = 'nbLogHelper::getApp';
$config['formatValueFrom']['%message%'] = 'nbLogHelper::getMessage';
$config['formatValueFrom']['%file%'] = 'nbLogHelper::getFile';
$config['formatValueFrom']['%line%'] = 'nbLogHelper::getLine';

$config['format'] = '[TIME] %day% %time% %microtime% [APP] %app% [MESSAGE] %message%';

$config['cs-filter-debug/item']['log'] = array(
  'name' => 'Log',
  'image' => '/log/debugbar/log.png',
  'path' => 'nb-log/nbLog/toolbarLog',
);