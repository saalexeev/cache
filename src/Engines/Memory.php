<?php


namespace TestWorkMax\Engines;


use TestWorkMax\CacheInterface;

/**
 * Class Memory
 * @package TestWorkMax\Engines
 */
class Memory implements CacheInterface
{

	/** @var array $storage */
	private $storage = [];

	/**
	 * Retrieves value from cache.
	 *
	 * @param string      $key
	 * @param string|null $prefix
	 *
	 * @return mixed
	 */
	public function get($key, $prefix = null)
	{
		if (!is_null($prefix) && is_string($prefix)) {
			$key = "$prefix:$key";
		}
		if (!isset($this->storage[$key])) {
			return false;
		}
		$item = $this->storage[$key];
		if ($this->isExpired($item)) {
			$this->forget($key);
			return false;
		}

		return $item['value'];
	}

	/**
	 * @param array $item
	 *
	 * @return bool
	 */
	private function isExpired(array $item): bool
	{
		$expiresAt = $item['expiresAt'];
		return $expiresAt !== 0 && time() > $expiresAt;
	}

	/**
	 * Deletes presented key from cache
	 *
	 * @param string      $key
	 * @param string|null $prefix
	 *
	 * @return bool
	 */
	public function forget($key, $prefix = null)
	{
		if (!is_null($prefix) && is_string($prefix)) {
			$key = "$prefix:$key";
		}
		if (array_key_exists($key, $this->storage)) {
			unset($this->storage[$key]);
			return true;
		}

		return false;
	}

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
	public function set($key, $value, $ttl = null, $prefix = null)
	{
		if (!is_null($prefix) && is_string($prefix)) {
			$key = "$prefix:$key";
		}
		$this->storage[$key] = [
			'expiresAt' => intval($ttl) > 0 ? time() + $ttl : 0,
			'value' => $value,
		];
		return true;
	}

	/**
	 * Determines whether an item is present in the cache
	 *
	 * @param string      $key
	 * @param string|null $prefix
	 *
	 * @return bool
	 */
	public function has($key, $prefix = null): bool
	{
		if (!is_null($prefix) && is_string($prefix)) {
			$key = "$prefix:$key";
		}
		if (!isset($this->storage[$key])) {
			return false;
		}

		return !$this->isExpired($this->storage[$key]);
	}

	/**
	 * Clears all keys in cache
	 *
	 * @return void
	 */
	public function clear()
	{
		$this->storage = [];
	}
}