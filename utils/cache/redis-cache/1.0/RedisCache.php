<?php

/**
 * This package contains a cache mechanism that use Redis to cache data.
 * 
 * @Component
 */
class RedisCache implements CacheInterface {
	
	/**
	 * The Predis instance
	 * 
	 * @Property
	 * @Compulsory
	 * @var Predis
	 */
	public $redis;
	
	/**
	 * Returns the cached value for the key passed in parameter.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key)
	{
		return $this->redis->get($key);
	}
	
	/**
	 * Sets the value in the cache.
	 *
	 * @param string $key The key of the value to store
	 * @param mixed $value The value to store
	 * @param float $timeToLive The time to live of the cache, in seconds.
	 */
	public function set($key, $value, $timeToLive = null) 
	{
		$this->redis->set($key,$value);
		if ($timeToLive != null)
		{
			$this->redis->expire($key,$timeToLive);
		}
	}
	
	/**
	 * Removes the object whose key is $key from the cache.
	 *
	 * @param string $key The key of the object
	 */
	public function purge($key)
	{
		return $this->redis->del($key);
	}
	
	/**
	 * Removes all the objects from the cache.
	 *
	 */
	public function purgeAll()
	{
		return $this->redis->flushdb();
	}
	
}
?>