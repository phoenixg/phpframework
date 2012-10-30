<?php defined('DS') or die('Direct access forbid!');

class Config {

	public static $items = array();
	public static $cache = array();
	const loader = 'laravel.config.loader';

	public static function has($key)
	{
		return ! is_null(static::get($key));
	}


	public static function get($key, $default = null)
	{
		list($bundle, $file, $item) = static::parse($key);

		if ( ! static::load($bundle, $file)) return value($default);

		$items = static::$items[$bundle][$file];

		// If a specific configuration item was not requested, the key will be null,
		// meaning we'll to return the entire array of configuration item from the
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


	public static function set($key, $value)
	{
		list($bundle, $file, $item) = static::parse($key);

		static::load($bundle, $file);

		// If the item is null, it means the developer wishes to set the entire
		// configuration array to a given value, so we will pass the entire
		// array for the bundle into the array_set method.
		if (is_null($item))
		{
			array_set(static::$items[$bundle], $file, $value);
		}
		else
		{
			array_set(static::$items[$bundle][$file], $item, $value);
		}
	}


	protected static function parse($key)
	{
		// First, we'll check the keyed cache of configuration items, as this will
		// be the fastest method of retrieving the configuration option. After an
		// item is parsed, it is always stored in the cache by its key.
		if (array_key_exists($key, static::$cache))
		{
			return static::$cache[$key];
		}

		$bundle = Bundle::name($key);

		$segments = explode('.', Bundle::element($key));

		// If there are not at least two segments in the array, it means that the
		// developer is requesting the entire configuration array to be returned.
		// If that is the case, we'll make the item field "null".
		if (count($segments) >= 2)
		{
			$parsed = array($bundle, $segments[0], implode('.', array_slice($segments, 1)));
		}
		else
		{
			$parsed = array($bundle, $segments[0], null);
		}

		return static::$cache[$key] = $parsed;
	}

	public static function load($bundle, $file)
	{
		if (isset(static::$items[$bundle][$file])) return true;

		// We allow a "config.loader" event to be registered which is responsible for
		// returning an array representing the configuration for the bundle and file
		// requested. This allows many types of config "drivers".
		$config = Event::first(static::loader, func_get_args());

		// If configuration items were actually found for the bundle and file we
		// will add them to the configuration array and return true, otherwise
		// we will return false indicating the file was not found.
		if (count($config) > 0)
		{
			static::$items[$bundle][$file] = $config;
		}

		return isset(static::$items[$bundle][$file]);
	}

	/**
	 * Load the configuration items from a configuration file.
	 *
	 * @param  string  $bundle
	 * @param  string  $file
	 * @return array
	 */
	public static function file($bundle, $file)
	{
		$config = array();

		// Configuration files cascade. Typically, the bundle configuration array is
		// loaded first, followed by the environment array, providing the convenient
		// cascading of configuration options across environments.
		foreach (static::paths($bundle) as $directory)
		{
			if ($directory !== '' and file_exists($path = $directory.$file.EXT))
			{
				$config = array_merge($config, require $path);
			}
		}

		return $config;
	}

	/**
	 * Get the array of configuration paths that should be searched for a bundle.
	 *
	 * @param  string  $bundle
	 * @return array
	 */
	protected static function paths($bundle)
	{
		$paths[] = Bundle::path($bundle).'config/';

		// Configuration files can be made specific for a given environment. If an
		// environment has been set, we will merge the environment configuration
		// in last, so that it overrides all other options.
		if ( ! is_null(Request::env()))
		{
			$paths[] = $paths[count($paths) - 1].Request::env().'/';
		}

		return $paths;
	}

}