<?php

/*
 * Require needed classes
 */
spl_autoload_register(function($class) {
    $file = __DIR__.'/lib/'.strtr($class, '\\', '/').'.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }
});

/**
 * Redis PHP Bindings. Only works with single database configuration.
 * @author Paul Bouchequet <p.bouchequet@thecodingmachine.com>
 * @Component
 */
class Predis
{
	/**
	 * The Redis host instance IP address
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $host = "127.0.0.1";
	
	/**
	 * The Redis host instance post
	 *
	 * @Property
	 * @Compulsory
	 * @var int
	 */
	public $port = 6379;
	
	/**
	 * The Redis host instance database number
	 *
	 * @Property
	 * @Compulsory
	 * @var int
	 */
	public $database = 15;
	
	/**
	 * The Predis client instance.
	 * 
	 * @var Predis\Client
	 */
	private $redis;
	
	/**
	 * Execute all requested functions
	 */
	public function __call($method, $args)
	{
		if($this->redis == null)
		{
			$this->redis = new Predis\Client(array(
    					'host'     => $this->host,
    					'port'     => $this->port,
    					'database' => $this->database
						));
		}
		
		return call_user_func_array(array($this->redis, $method),$args);
	}
}