<?php defined('DS') or die('Direct access forbid!');

//TODO
//get, set , has, get() == getall()

class Config {

	public static $items = array();

	/**
	 * Determine if a configuration item exists
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public static function has($key)
	{
		return ! is_null(static::get($key));
	}

	/**
	 * Get a configuration item.
	 *
	 * If no item is requested, the entire configuration array will be returned.
	 *
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return array
	 */
	public static function get($key, $default = null)
	{
		list($bundle, $file, $item) = static::parse($key);

		if ( ! static::load($bundle, $file)) return value($default);

		$items = static::$items[$bundle][$file];

		// If a specific configuration item was not requested, the key will be null,
		// meaning we'll return the entire array of configuration items from the
		// requested configuration file. Otherwise we can return the item.
		if (is_null($item))
		{
			return $items;
		}
		else
		{
			return array_get($items, $item, $default);
		}
	}



}