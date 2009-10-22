<?php

/**
 * This package contains a cache mechanism that relies on temporary files.
 *
 * TODO: make a global garbage collector that passes sometimes (like sessions in PHP)
 * 
 * @Component
 */
class FileCache implements CacheInterface {
	
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
	 * The directory the files are stored in.
	 * If none is specified, they are stored in the "filecache" directory of the default system temporary file directory.
	 * The directory must end with a trailing "/".
	 *
	 * @Property
	 * @var string
	 */
	public $cacheDirectory;
		
	/**
	 * Returns the cached value for the key passed in parameter.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key) {
		$filename = $this->getDirectory().$key.".cache";
		
		if (is_readable($filename)) {
			$fp = fopen($filename, "r");
			$timeout = fgets($fp);
			
			if ($timeout > time() || $timeout==0) {
				$contents = "";
				while (!feof($fp)) {
				  $contents .= fread($fp, 65536);
				}
				fclose($fp);
				$value = unserialize($contents);
				$this->log->trace("Retrieving key '$key' from file cache: value returned:".var_export($value, true));
				return $value;
			} else {
				fclose($fp);
				unlink($filename);
				$this->log->trace("Retrieving key '$key' from file cache: key outdated, cache miss.");
				return null;
			}
		} else {
			$this->log->trace("Retrieving key '$key' from file cache: cache miss.");
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
		$filename = $this->getDirectory().$key.".cache";
		$this->log->trace("Storing value in cache: key '$key', value '".var_export($value, true)."'");
		
		if (!is_writable($filename)) {
			if (!file_exists($this->getDirectory())) {
				mkdir($this->getDirectory(), null, true);
			}
		}
		
		if ($timeToLive == null) {
			if (empty($this->defaultTimeToLive)) {
				$timeOut = 0;
			} else {
				$timeOut = $this->defaultTimeToLive;
			}
		} else {
			$timeOut = time() + $timeToLive;
		}
		
		$fp = fopen($filename, "w");
		fwrite($fp, $timeOut."\n");
		fwrite($fp, serialize($value));
		fclose($fp);
	}
	
	/**
	 * Removes the object whose key is $key from the cache.
	 *
	 * @param string $key The key of the object
	 */
	public function purge($key) {
		$this->log->trace("Purging key '$key' from file cache.");
		$filename = $this->getDirectory().$key.".cache";
		unlink($filename);
	}
	
	/**
	 * Removes all the objects from the cache.
	 *
	 */
	public function purgeAll() {
		$this->log->trace("Purging the whole file cache.");
		$files = glob($this->getDirectory()."*");
		foreach ($files as $filename) {
		    unlink($filename);
		}
	}
	
	private function getDirectory() {
		if (!empty($this->cacheDirectory)) {
			return $this->cacheDirectory;
		} else {
			return sys_get_temp_dir()."filecache/";
		}
	}
}
?>