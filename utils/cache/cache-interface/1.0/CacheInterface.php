<?php

interface CacheInterface {
	
	/**
	 * Returns the cached value for the key passed in parameter.
	 *
	 * @param string $key
	 * @return mixed
	 */
	function get($key);
	
	/**
	 * Sets the value in the cache.
	 *
	 * @param string $key The key of the value to store
	 * @param mixed $value The value to store
	 * @param int $timeToLive The time to live of the cache, in seconds.
	 */
	function set($key, $value, $timeToLive = null);
	
	/**
	 * Removes the object whose key is $key from the cache.
	 *
	 * @param string $key The key of the object
	 */
	function purge($key);
	
	/**
	 * Removes all the objects from the cache.
	 *
	 */
	function purgeAll();
}
?>