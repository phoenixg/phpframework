<?php
// 命令行下面使用
if(!isset($_SERVER['HTTP_HOST']))
{
  $argv = $_SERVER['argv'];
  $_SERVER['REQUEST_URI'] = $argv[1];

  $queryString = preg_replace('/^.*\?/', '', $_SERVER['REQUEST_URI']);
  parse_str($queryString, $_GET);
}

if (is_file(PROJECT_ROOT.'config'.DIRECTORY_SEPARATOR.$coreConfigClass.'.class.php'))
{
  include(PROJECT_ROOT.'config'.DIRECTORY_SEPARATOR.$coreConfigClass.'.class.php');
}

$nbInitConfig = new $coreConfigClass;
$nbInitConfig->register();