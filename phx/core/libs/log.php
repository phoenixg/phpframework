<?php //namespace Phx;
//TODO
class Log {

    private static $instance; 
  
    private function __construct()  
    {  
    }  
  
    public static function getInstance() 
    {  
        if(!self::$instance)  
        {  
            self::$instance = new Log();  
        }  
        return self::$instance;
    }  

	public static function exception($e)
	{
		static::write('exception', static::exception_line($e));
	}

	protected static function exception_line($e)
	{
		return $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	}

	public static function write($type, $message)
	{

		$message = static::format($type, $message);
		echo $message;
		//File::append(path('storage').'logs/'.date('Y-m-d').'.log', $message);
	}

	protected static function format($type, $message)
	{
		return date('Y-m-d H:i:s').' '.strtoupper($type)." - {$message}".PHP_EOL;
	}

	public static function __callStatic($method, $parameters)
	{
		static::write($method, $parameters[0]);
	}

}