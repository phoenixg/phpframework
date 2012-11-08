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

	public static function parse($str)
	{
		$str = 'static::$items["'.str_replace('.','"]["',$str).'"]';
		return eval('return isset('.$str.')?'.$str.':"not exists";'); 





		/*
		$keys = explode('.', $str);
		$error = '';

		if(is_array($keys)){
			foreach ($keys as $k){
				if(is_array(static::$items) && key_exists($k, static::$items)){
					$v = static::$items = static::$items[$k];
				}else{
					$error =  'path not found';
					break;
				}
			}
		} else {
			$error = 'format error';
		}

		if($error == ''){
			var_dump(static::$items);
			return $v;
		}else{
			echo $error;
		}
		*/











		/*
		$string_arr = explode('.', $string);
		$string_arr_count = count($string_arr);


		if($string_arr_count == 1)
		{
			return static::$items[$string];
		} else {
			$index = '';
			foreach ($string_arr as $v) {
				$index .= "['".$v."']";
			}
			echo 'bbb';
			echo $index;
			echo 'bbb';
			echo static::$items['application']['debug_soft'];
			echo 'aaa';
			echo static::$items.$index;		
			echo 'aaa';
			var_dump(eval('static::\$items\$index'));
		}

		if($string_arr_count == 2)
		{
			list($filename, $item) = $string_arr;
			return static::$items[$filename][$item];
		}

		if($string_arr_count == 3)
		{
			list($filename, $item, $subitem) = $string_arr;
			new dBug(static::$items[$filename][$item][$subitem]);
		}
		*/

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