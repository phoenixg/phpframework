<?php
//TODO
class IoC {  
   protected static $registry = array();  
   
   // 注册一个类
   public static function register($name, Closure $resolve)  
   {  
      static::$registry[$name] = $resolve;  
   }  
   
   // 获取已注册类的实例
   public static function resolve($name)  
   {  
      if ( static::registered($name) )  
      {  
         $name = static::$registry[$name];  
         return $name();  
      }  
      throw new Phxexception('没有注册这个类！');  
   }  
   
   // 检查该类是否已注册
   public static function registered($name)  
   {  
      return array_key_exists($name, static::$registry);  
   }  
}  

