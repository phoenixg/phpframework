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
    
    public function run() {
        Route::run();
    }
}