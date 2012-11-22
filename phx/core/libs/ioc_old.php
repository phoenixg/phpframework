<?php
class IoC {  
   protected static $registry = array();  
   protected static $singleton = array();
   
   // 注册一个类在IoC容器里 (普通)
   public static function register($name, Closure $resolver)  
   {  
      static::$registry[$name] = $resolver;  
   }

   // 注册一个类在IoC容器里（单例）
   public static function singleton($name, Closure $resolver)
   {
      static::$singleton[$name] = $resolver;
   }

   // 获取已注册类的实例
   public static function resolve($name)  
   {  
      try{
         if (array_key_exists($name, static::$registry)) {
            $name = static::$registry[$name];
            return $name(); 
         } elseif (array_key_exists($name, static::$singleton)) {
            $name = static::$singleton[$name];

            if (is_null($name())) {
               return $name();
            }

            return $name();


         } else {
            throw new Exception('这个类：' . $name . '没有被注册过'); 
         }

         
      } catch (Exception $e) {
         echo $e->getMessage();
      }      
   }  
   
   // 检查该类是否已注册
   public static function registered($name)  
   {  
      return array_key_exists($name, static::$registry) || array_key_exists($name, static::$singleton);
   }  
}  

