<?php

namespace TestWorkMax;


/**
 * Interface CacheInterface
 * @package TestWorkMax\Interfaces
 */
interface CacheInterface
{
	/**
	 * Retrieves value from cache.
	 *
	 * @param string      $key
	 * @param string|null $prefix
	 *
	 * @return mixed
	 */
	public function get($key, $prefix = null);

	/**
	 * Saves value in the cache.
	 *
	 * @param string      $key
	 * @param mixed       $value
	 * @param int|null    $ttl
	 * @param string|null $prefix
	 *
	 * @return bool
	 */
	public function set($key, $value, $ttl = null, $prefix = null);

	/**
	 * Determines whether an item is present in the cache
	 *
	 * @param string $key
	 * @param string|null $prefix
	 * @return bool
	 */
	public function has($key, $prefix = null): bool;

	/**
	 * Deletes presented key from cache
	 *
	 * @param string $key
	 * @param string|null $prefix
	 *
	 * @return bool
	 */
	public function forget($key, $prefix = null);

	/**
	 * Clears all keys in cache
	 *
	 * @return bool
	 */
	public function clear();
}