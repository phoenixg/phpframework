<?php
/*
 * PIP v0.5.3
 */

//开启session
session_start(); 

//定义目录常量
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');  //E:\xampp\htdocs\phpframework\PIP/
define('APP_DIR', ROOT_DIR .'application/'); //E:\xampp\htdocs\phpframework\PIP/application/ 

//加载文件
require(APP_DIR .'config/config.php');//设置base_url, 默认访问控制器，默认错误控制器，数据库信息
require(ROOT_DIR .'system/model.php');//mysql数据库操纵类
require(ROOT_DIR .'system/view.php');//视图操纵类
require(ROOT_DIR .'system/controller.php');
require(ROOT_DIR .'system/pip.php');

//定义base_url
global $config;
define('BASE_URL', $config['base_url']);

pip();

?>
