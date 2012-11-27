<?php

class FrontController {
    private static $_instance = null;
    
    // 防止直接new
    private function __construct() {}
    
    // 防止直接clone
    private function __clone() {}

    public static function getInstance() {
        if(!(self::$_instance instanceof self)) {
            self::$_instance = new FrontController();
        }
        return self::$_instance;
    }
    
    // 根据控制器和方法名称，执行控制器对应的方法
    public static function route($controller, $action) {
        $controllerName = ucfirst($controller) . '_Controller';
        $controllerHandler = new $controllerName();

        $action = 'action_'.$action; 
        if(!method_exists($controllerHandler, $action)) 
            throw new Exception('不存在方法：'.$action);
            
        $controllerHandler->$action();
    }
}
