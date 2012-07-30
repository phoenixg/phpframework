<?php
/*
 * PIP v0.5.3
 */

//开启session
session_start(); 

//定义目录常量

// /home/phoenix/public_html/gitprojects/phpframework/PIP/
// E:\xampp\htdocs\phpframework\PIP/
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');  

// /home/phoenix/public_html/gitprojects/phpframework/PIP/application/
// E:\xampp\htdocs\phpframework\PIP/application/
define('APP_DIR', ROOT_DIR .'application/');  

//加载文件
require(APP_DIR .'config/config.php');//设置base_url, 默认访问控制器，默认错误控制器，数据库信息
require(ROOT_DIR .'system/model.php');//mysql数据库操纵类
require(ROOT_DIR .'system/view.php');//视图操纵类
require(ROOT_DIR .'system/controller.php');//控制器操纵类
require(ROOT_DIR .'system/pip.php');//pip()函数，执行完整的网站运行流程

//定义base_url
global $config;

// http://173.230.150.168/gitprojects/phpframework/PIP/
define('BASE_URL', $config['base_url']);


pip();

