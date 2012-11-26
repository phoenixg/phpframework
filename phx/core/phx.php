<?php
class Controller{}

$controller = empty($_GET['c']) ? 'default' : trim($_GET['c']); //设置了默认的控制器
$action = empty($_GET['a']) ? 'index' : trim($_GET['a']); //设置了默认的action

$action = 'index';

$controllerFilePath = 'D:\xampp\htdocs\phpframework\phx\app\mvc\controllers\defaultphp';

if (!is_file($controllerFilePath)) {
	throw new Exception('不存在控制器文件'.$controllerFilePath);
}


include $controllerFilePath;
$controllerName = ucfirst($controller) . '_Controller';

if(class_exists($controllerName)) {
	$controllerHandler = new $controllerName();

	$action = 'action_'.$action; 

	if(method_exists($controllerHandler, $action)) {
		$controllerHandler->$action();
	} else {
		echo 'the method does not exists';
	}
} else {
	echo 'the class does not exists';
}





