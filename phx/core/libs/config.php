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
		if (is_null(static::$items) || !isset(static::$items[$key])) {
			return $default;
		} else {
			return static::$items[$key];
		}
	}

	public static function parse($string)
	{
		var_dump($string);
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