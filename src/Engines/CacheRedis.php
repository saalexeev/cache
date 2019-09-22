<?php


namespace TestWorkMax\Engines;

use Redis;
use TestWorkMax\CacheInterface;

class CacheRedis implements CacheInterface
{
	const REDIS_DEFAULT_HOST = '127.0.0.1';
	const REDIS_DEFAULT_PORT = '6379';
	/**
	 * @var Redis | null
	 */
	private $instance = null;

	public function __construct(array $params = [])
	{
		if ($this->instance === null) {
			$this->instance = new Redis();
			$host = $params['host'] ?? self::REDIS_DEFAULT_HOST;
			$port = $params['port'] ?? self::REDIS_DEFAULT_PORT;
			$this->instance->pconnect($host, $port);
			$this->instance->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
		}
	}

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

		return $this->instance->get($key);
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

		return $this->instance->set($key, $value, $ttl);
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

		return $this->instance->exists($key);
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

		return $this->instance->delete($key) > 0;
	}

	/**
	 * Clears all keys in cache
	 *
	 * @return bool
	 */
	public function clear()
	{
		return $this->instance->flushAll();
	}
}