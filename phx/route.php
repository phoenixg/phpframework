<?php

class Controller{}
class Model{}


class Route {

	public static function run()
	{
		$controller = empty($_GET['c']) ? 'default' : trim($_GET['c']); //设置了默认的控制器
		$action = empty($_GET['a']) ? 'index' : trim($_GET['a']); //设置了默认的action

		$action = 'hello';
		$controllerFilePath = 'D:\xampp\htdocs\phpframework\phx\app\mvc\controllers\default.php';


		if (!is_file($controllerFilePath)) 
		    throw new Exception('不存在控制器文件：'.$controllerFilePath);

		include $controllerFilePath;

		$controllerName = ucfirst($controller) . '_Controller';
		if(!class_exists($controllerName)) 
		    throw new Exception('不存在类：'.$controllerName);

		$controllerHandler = new $controllerName();

		$action = 'action_'.$action; 
		if(!method_exists($controllerHandler, $action)) 
		    throw new Exception('不存在方法：'.$action);
		    
		$controllerHandler->$action();
	}
}

// 设置控制器和模型的所有类为自动加载
function __autoload($classname) {
	$classname = strtolower(substr($classname, 0, strpos($classname, '_')));

	$fileController = PATH_APP_C . $classname . EXT;
	if (is_file($fileController)) {
		include $fileController;
	} else {
		$fileModel = PATH_APP_M . $classname . EXT;
		if (is_file($fileModel)) {
			include $fileModel;
		} else {
			throw new Exception('无法自动加载该类：'.$classname);
		}
	}   
}  
