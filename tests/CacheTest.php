<?php

namespace TestWorkMax\Classes;


use Exception;
use PHPUnit\Framework\TestCase;
use TestWorkMax\Engines\CacheRedis;
use TestWorkMax\Interfaces\CacheInterface;

class CacheTest extends TestCase
{

	public function testInitFailed()
	{
		$this->expectException(Exception::class);
		Cache::init('not-redis');
	}


	public function testInitSuccess()
	{
		$instance = Cache::init('redis');
		$this->assertNotNull($instance);
		$this->assertInstanceOf(CacheRedis::class, $instance);
		$this->assertInstanceOf(CacheInterface::class, $instance);
	}

	protected function tearDown(): void
	{
		Cache::destroy();
	}
}
