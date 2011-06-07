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
class Predis extends Predis\Client
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
	
	public function __construct()
	{
		parent::__construct(array(
    		'host'     => $this->host,
    		'port'     => $this->port,
    		'database' => $this->database
		));
	}
	
}