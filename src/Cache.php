<?php

namespace TestWorkMax;

use Exception;
use TestWorkMax\Engines\{CacheRedis, File, Memory};

class Cache
{
	/** @var CacheInterface $instance */
	private static $instance = null;

	/**
	 * Cache initialization
	 *
	 * @param string $engine
	 * @param array  $params
	 *
	 * @return CacheInterface
	 * @throws Exception
	 */
	public static function init(string $engine = 'file', $params = [])
	{
		if (self::$instance === null) {
			switch ($engine) {
				case 'redis':
					self::$instance = new CacheRedis($params);
					break;
				case 'file':
					self::$instance = new File($params);
					break;
				case 'memory':
					self::$instance = new Memory();
					break;
				default:
					throw new Exception("Cache engine $engine not found.");
					break;
			}
		}
		return self::$instance;
	}

	/**
	 * @param string $key
	 * @param mixed  $default
	 * @param null   $prefix
	 *
	 * @return mixed
	 */
	public static function get($key, $default = false, $prefix = null)
	{
		$value = self::$instance->get($key, $prefix);
		return $value === false ? $default : $value;
	}

	/**
	 * @param string      $key
	 * @param mixed       $value
	 * @param int|null    $ttl
	 * @param string|null $prefix
	 *
	 * @return bool
	 */
	public static function set($key, $value, $ttl = null, $prefix = null)
	{
		return self::$instance->set($key, $value, $ttl, $prefix);
	}

	/**
	 * Determines whether an item is present in the cache
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public static function has($key): bool
	{
		return self::$instance->has($key);
	}

	/**
	 * Deletes presented key from cache
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public static function delete($key)
	{
		return self::$instance->forget($key);
	}

	/**
	 * Clears all keys in cache
	 *
	 * @return bool
	 */
	public static function clear()
	{
		return self::$instance->clear();
	}

	public static function destroy()
	{
		self::$instance = null;
	}
}