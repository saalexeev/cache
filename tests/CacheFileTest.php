<?php


namespace TestWorkMax\Classes;


use PHPUnit\Framework\TestCase;

class CacheFileTest extends TestCase
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
	protected function setUp(): void
	{
		$this->key = 'file_key';
		$this->value = 'file_value';
		Cache::init('file', [
			'path' => '/c/Users/User/PhpStormProjects/cache/cache'
		]);
	}

	protected function tearDown(): void
	{
		Cache::destroy();
	}
}