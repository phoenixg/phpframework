<?php

function pip()
{
	// 使用全局的配置变量
	global $config;
    
    // 设置默认值
    $controller = $config['default_controller'];//eg. main
    $action = 'index';//index
    $url = '';
	
	// 获取请求URL和脚本URL
	$request_url = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : ''; // '/phpframework/pip/' 
	$script_url  = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : ''; // '/phpframework/pip/index.php' 
  	
	//echo $request_url;// /gitprojects/phpframework/PIP/index.php/main/aaa
	//echo $script_url;// /gitprojects/phpframework/PIP/index.php/main/aaa


	// 获取URL路径，trim掉左右的/
	// $url = '' 								 -> when uri is : http://localhost/phpframework/pip/index.php/main/index
	// $url = phpframework/pip/index.ext/aaa/bbb -> when uri is : http://localhost/phpframework/pip/index.ext/aaa/bbb
	if($request_url != $script_url) $url = trim(preg_replace('/'. str_replace('/', '\/', str_replace('index.php', '', $script_url)) .'/', '', $request_url, 1), '/');
 	
	// 将 $url 分段成 $segments
	// $segments eq. array('0' => '') when uri is : http://localhost/phpframework/pip/index.php
	// $segments eq. array('0' => '') when uri is : http://localhost/phpframework/pip/index.php/main/index
	// $segments eq. array('0' => '') when uri is : http://localhost/phpframework/pip/index.php/aaa/bbb
	// $segments eq. Array ( [0] => phpframework [1] => pip [2] => index.ext [3] => aaa [4] => bbb ) 
	//	 							  when uri is : http://localhost/phpframework/pip/index.ext/aaa/bbb
	$segments = explode('/', $url);

	// 默认检查，$segments没有内容的话就是之前设置的默认值
	if(isset($segments[0]) && $segments[0] != '') $controller = $segments[0];//main
	if(isset($segments[1]) && $segments[1] != '') $action = $segments[1];//index


	// 加载控制器类文件
    $path = APP_DIR . 'controllers/' . $controller . '.php';
	if(file_exists($path)){
        require_once($path);
	} else {
        $controller = $config['error_controller'];
        require_once(APP_DIR . 'controllers/' . $controller . '.php');
	}
    
    // 检查请求的控制器和方法是否存在，如果不存在就使用所定义的错误控制器里的控制器和方法
    if(!method_exists($controller, $action)){
        $controller = $config['error_controller'];
        require_once(APP_DIR . 'controllers/' . $controller . '.php');
        $action = 'index';
    }
		
	var_dump($controller);
	var_dump($action);
	var_dump($url);

	// 创建最终请求的控制器对象，并调用请求的方法，执行失败的话就终止程序
	$obj = new $controller;
    die(call_user_func_array(array($obj, $action), array_slice($segments, 2)));
}

?>
