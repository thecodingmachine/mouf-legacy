<?php

/**
 * This package contains a cache mechanism that relies on APC.
 * 
 * @Component
 */
class ApcCache implements CacheInterface {
	
	/**
	 * The default time to live of elements stored in the session (in seconds).
	 * Please note that if the session is flushed, all the elements of the cache will disapear anyway.
	 * If empty, the time to live will be the time of the session. 
	 *
	 * @Property
	 * @var int
	 */
	public $defaultTimeToLive;
	
	/**
	 * The logger used to trace the cache activity.
	 *
	 * @Property
	 * @Compulsory
	 * @var LogInterface
	 */
	public $log;
	
	/**
	 * Returns the cached value for the key passed in parameter.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key) {
		
		$success = false;
		$value = apc_fetch($key, $success);
		
		if ($success) {
			$this->log->trace("Retrieving key '$key' from file cache: value returned:".var_export($value, true));
			return $value;
		} else {
			$this->log->trace("Retrieving key '$key' from file cache: cache miss");
			return null;
		}
	}
	
	/**
	 * Sets the value in the cache.
	 *
	 * @param string $key The key of the value to store
	 * @param mixed $value The value to store
	 * @param float $timeToLive The time to live of the cache, in seconds.
	 */
	public function set($key, $value, $timeToLive = null) {
		$this->log->trace("Storing value in APC cache: key '$key', value '".var_export($value, true)."'");
		
		if ($timeToLive == null) {
			if (empty($this->defaultTimeToLive)) {
				$timeOut = 0;
			} else {
				$timeOut = $this->defaultTimeToLive;
			}
		} else {
			$timeOut = time() + $timeToLive;
		}
		
		$ret = apc_store($key, $value, $timeOut);
		if ($ret == false) {
			$this->log->error("Error while caching the key '$key' with value '".var_export($value, true)."' in APC cache.");
		}
	}
	
	/**
	 * Removes the object whose key is $key from the cache.
	 *
	 * @param string $key The key of the object
	 */
	public function purge($key) {
		$this->log->trace("Purging key '$key' from file cache.");
		apc_delete($key);
	}
	
	/**
	 * Removes all the objects from the cache.
	 *
	 */
	public function purgeAll() {
		throw new Exception("Purgeall is not implemented in ApcCache (and cannot be efficiently implemented)");
	}
	
}
?>