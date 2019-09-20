<?php


namespace TestWorkMax\Engines;


use Exception;
use TestWorkMax\CacheInterface;

class File implements CacheInterface
{

	private $cacheDir;

	/**
	 * File constructor.
	 *
	 * @param array $params
	 *
	 * @throws Exception
	 */
	public function __construct(array $params)
	{
		$this->cacheDir = $params['path'] ?? ($_SERVER['DOCUMENT_ROOT'] . '/cache');
		if (!file_exists($this->cacheDir)) {
			if (!mkdir($this->cacheDir, 0777, false)) {
				throw new Exception('Cannot create cache directory');
			}
			chmod($this->cacheDir, 0777);
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
		$key = $this->hashKeyWithPrefix($key, $prefix);
		$filename = $this->cacheDir . $key;
		if (!file_exists($filename)) {
			return false;
		}

		$content = file_get_contents($filename);
		list($value, $expiresAt) = explode("\n", $content);
		if ($this->isExpired(intval($expiresAt))) {
			$this->forget($filename);
			return false;
		}

		$item = unserialize($value);

		return $item;
	}

	private function hashKeyWithPrefix($key, $prefix)
	{
		$key = '/' . md5($key);
		if (!is_null($prefix) && is_string($prefix)) {
			$key = "/$prefix$key";
		}

		return $key;
	}

	/**
	 * @param int $expiresAt
	 *
	 * @return bool
	 */
	private function isExpired(int $expiresAt): bool
	{
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
		if (!file_exists($key)) {
			return false;
		}

		return unlink($key);
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
		$key = $this->hashKeyWithPrefix($key, $prefix);
		$filename = $this->cacheDir . $key;
		$expiresAt = intval($ttl) > 0 ? time() + $ttl : 0;
		$serializedValue = serialize($value) . "\n" . $expiresAt;

		if (!is_null($prefix) && is_string($prefix)) {
			if (!file_exists($this->cacheDir . '/' . $prefix)) {
				mkdir($this->cacheDir . '/' . $prefix, 0777, true);
			}
		}

		return file_put_contents($filename, $serializedValue) > 0;
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
		$key = $this->hashKeyWithPrefix($key, $prefix);
		$filename = $this->cacheDir . $key;

		if (!file_exists($filename)) {
			return false;
		}

		$content = file_get_contents($filename);
		list($value, $expiresAt) = explode("\n", $content);

		return !$this->isExpired(intval($expiresAt));
	}

	/**
	 * Clears all keys in cache
	 *
	 * @param null $directory
	 *
	 * @return void
	 */
	public function clear($directory = null)
	{
		if (!$directory) {
			$directory = $this->cacheDir;
		}
		if (is_dir($directory)) {
			$objects = scandir($directory);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (is_dir($directory . "/" . $object)) {
						$this->clear($directory . "/" . $object);
					} else {
						unlink($directory . "/" . $object);
					}
				}
			}
			rmdir($directory);
		}
	}
}