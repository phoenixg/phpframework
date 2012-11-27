<?php

class Route {

	// 根据控制器和方法名称，执行控制器对应的方法
	public static function run()
	{	
		global $CFG;

		$controller = empty($_GET['c']) ? $CFG::get('application.default_controller') : trim($_GET['c']);
		$action = empty($_GET['a']) ? $CFG::get('application.default_action') : trim($_GET['a']);
		$action = 'hello';

		$controllerName = ucfirst($controller) . '_Controller';
		$controllerHandler = new $controllerName();

		$action = 'action_'.$action; 
		if(!method_exists($controllerHandler, $action)) 
		    throw new Exception('不存在方法：'.$action);
		    
		$controllerHandler->$action();
	}
}

