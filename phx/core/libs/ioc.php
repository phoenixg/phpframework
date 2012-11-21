<?php
//TODO: singleton
class IoC {  
   protected static $registry = array();  
   public static $singletons = array();
   
   // 注册一个类
   public static function register($name, Closure $resolve)  
   {  
      static::$registry[$name] = $resolve;  
   }  
   
   // 获取已注册类的实例
   public static function resolve($name)  
   {  
      try{
         if (!static::registered($name)) {
            throw new Phxexception('没有注册过这个类：' . $name); 
         }
         $name = static::$registry[$name];  
         return $name();  
      } catch (Phxexception $e) {
         echo $e->getMsg();
      }      
   }  
   
   // 检查该类是否已注册
   public static function registered($name)  
   {  
      return array_key_exists($name, static::$registry);  
   }  
}  

