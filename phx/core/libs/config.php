<?php defined('DS') or die('Direct access forbid!');

//TODO
//get, set , has, get() == getall()

class Config {

	public static $items = array();

	public function __construct(array $arr)
	{
		static::$items = $arr;
	}

	public static function get($key, $default = null)
	{
		$key = static::parse($key);
		var_dump($key);
		if( $key == null ){ 
			return 'yes';
		} else {
			return 'no';
		}
		die;
		if (is_null(static::$items) || !isset(static::$items[$key])) {
			return $default;
		} else {
			return static::$items[$key];
		}
	}

	protected static function parse($str)
	{
		$str = 'static::$items["'.str_replace('.','"]["',$str).'"]';
		$key =  eval('return isset('.$str.')?'.$str.':null;'); 
		return $key;
	}



///////////////
	public static function set()
	{
		return null;
	}

	public static function has($key)
	{
		return ! is_null(static::get($key));
	}






}