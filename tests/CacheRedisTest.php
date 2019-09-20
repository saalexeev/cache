<?php

namespace TestWorkMax\Classes;


use PHPUnit\Framework\TestCase;

class CacheRedisTest extends TestCase
{

	private $key;
	private $value;

	public function testSetGet()
	{
		$setResult = Cache::set($this->key, $this->value);
		$this->assertTrue($setResult);

		$valueFromCache = Cache::get($this->key, null);
		$this->assertNotNull($valueFromCache);
		$this->assertEquals($this->value, $valueFromCache);
	}

	public function testSetGetWithTTL()
	{
		$ttl = 1;
		$default = 'default value';

		Cache::set($this->key, $this->value, $ttl);

		sleep(2);

		$valueFromCache = Cache::get($this->key, $default);

		$this->assertNotNull($valueFromCache);
		$this->assertIsString($valueFromCache);
		$this->assertEquals($default, $valueFromCache, $valueFromCache);
	}

	public function testGetNotExists()
	{
		$value = Cache::get('non-existing-key', 'default-value');

		$this->assertIsString($value);
		$this->assertEquals('default-value', $value);
	}

	protected function setUp(): void
	{
		$this->key = 'redis_test';
		$this->value = 'redis_value';
		Cache::init('redis');
	}
}
