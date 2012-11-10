<?php defined('DS') or die('Direct access forbid!');

class Config {

	public static $items = array();

	public function __construct(array $arr)
	{
		static::$items = $arr;
		//var_export(static::$items);
		//var_dump(static::$items["application"]["aaa"]["ddd"]["eee"]["out"]);
	}

	public static function get($str, $default = null)
	{
		$str = static::parse($str);
		if (is_null($str) || !isset($str)) {
			return $default;
		}

		return $str;
	}

	protected static function parse($str)
	{
		$str = 'static::$items["'.str_replace('.','"]["',$str).'"]';
		$str_parent = substr($str, 0 , strrpos($str, '['));
		/*
		echo $str;echo '<br />';

		var_dump(is_array(static::$items["application"]["aaa"]["ddd"]));
		var_dump(is_array(static::$items["application"]["aaa"]["ddd"]["eee"]));
		var_dump(isset(static::$items["application"]["aaa"]["ddd"]["eee"]["out"]));

		var_dump('return is_array('.$str_parent.');');
		var_dump('return isset('.$str.');');

		var_dump(eval('return is_array('.'static::$items["application"]["aaa"]["ddd"]'.');'));
		var_dump(eval('return is_array('.'static::$items["application"]["aaa"]["ddd"]["eee"]'.');'));
		var_dump(eval('return is_array('.'static::$items["application"]["aaa"]["ddd"]["eee"]["out"]'.');'));
		var_dump(eval('return is_array(array("test"));'));
		*/
		var_dump(eval('return is_array('.$str_parent.');'));
		var_dump(eval('return isset('.$str.');'));

		if(eval('return is_array('.$str_parent.');') && eval('return isset('.$str.');')){
			echo  'okbuy';
		}else {
			echo  'not ok';
		}
		die;
		echo ($str);
		var_dump(isset($str));
		eval('return isset('.$str.')?'.$str.':null;');
	}

	public static function has($str)
	{
		return ! is_null(static::get($str));
	}






}