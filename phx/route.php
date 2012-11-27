<?php

class Controller{}
class Model{}


class Route {

	// 根据控制器和方法名称，执行控制器对应的方法
	public static function run()
	{
		$controller = empty($_GET['c']) ? 'default' : trim($_GET['c']); //设置了默认的控制器
		$action = empty($_GET['a']) ? 'index' : trim($_GET['a']); //设置了默认的action

		$action = 'hello';
		$controllerFilePath = 'D:\xampp\htdocs\phpframework\phx\app\mvc\controllers\default_controller.php';

		$controllerName = ucfirst($controller) . '_Controller';
		$controllerHandler = new $controllerName();

		$action = 'action_'.$action; 
		if(!method_exists($controllerHandler, $action)) 
		    throw new Exception('不存在方法：'.$action);
		    
		$controllerHandler->$action();
	}
}

// 设置控制器和模型的所有类为自动加载
function __autoload($classname) 
{
	//var_dump($classname);  eg. Default_Controller
	$fileController = PATH_APP_C . strtolower($classname) . EXT;
		
	if (is_file($fileController)) {
		include $fileController;
	} else {
		$fileModel = PATH_APP_M . strtolower($classname) . EXT;
		
		if (is_file($fileModel)) {
			include $fileModel;
		} else {
			throw new Exception('无法自动加载该类：'.$classname);
		}
	}  
}  
