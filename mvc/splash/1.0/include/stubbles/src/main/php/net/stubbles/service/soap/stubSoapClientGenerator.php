<?php
/**
 * Factory for SOAP clients.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_soap
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubIllegalArgumentException',
                      'net::stubbles::lang::exceptions::stubRuntimeException',
                      'net::stubbles::reflection::stubReflectionClass',
                      'net::stubbles::service::soap::stubSoapClient',
                      'net::stubbles::service::soap::stubSoapClientConfiguration'
);
/**
 * Factory for SOAP clients.
 *
 * Usage:
 * <code>
 * $client = stubSoapClientGenerator::getInstance()->forConfig($config);
 * try {
 *     $result = $client->invoke('someMethod', $arguments);
 *     // evaluate result
 * } catch (stubSOAPException $e) {
 *     // something does not work
 * }
 * </code>
 *
 * @package     stubbles
 * @subpackage  service_soap
 */
class stubSoapClientGenerator extends stubBaseObject
{
    /**
     * list of available drivers
     *
     * @var  array<string,string|ReflectionClass>
     */
    protected $drivers = array();
    /**
     * the singleton instance
     *
     * @var  stubSoapClientGenerator
     */
    protected static $instance;

    /**
     * static initializing
     */
    // @codeCoverageIgnoreStart
    public static function __static()
    {
        self::$instance = new self();
    }

    /**
     * constructor
     */
    protected function __construct()
    {
        if (extension_loaded('soap') === true) {
            $this->drivers['net::stubbles::service::soap::native::stubNativeSoapClient'] = 'net::stubbles::service::soap::native::stubNativeSoapClient';
        }
    }
    // @codeCoverageIgnoreEnd

    /**
     * returns the singleton instance of the generator
     *
     * @return  stubSoapClientGenerator
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * forbid cloning the factory
     *
     * @throws  stubRuntimeException
     */
    public function __clone()
    {
        throw new stubRuntimeException('Cloning the soap client generator is not necessary.');
    }

    /**
     * adds a client to list of drivers
     *
     * @param   ReflectionClass  $clientClass
     * @throws  stubIllegalArgumentException
     */
    public function addClient(ReflectionClass $clientClass)
    {
        if ($clientClass->implementsInterface('stubSoapClient') === false) {
            throw new stubIllegalArgumentException('Client class must implement interface net::stubbles::service::soap::stubSoapClient.');
        }
        
        $this->drivers[$clientClass->getName()] = $clientClass;
    }

    /**
     * sets the available clients
     *
     * @param  array<string,string|ReflectionClass>  $clients
     */
    public function setAvailableClients(array $clients)
    {
        $this->drivers = $clients;
    }

    /**
     * returns a list of available clients
     *
     * @return  array<string,string|ReflectionClass>
     */
    public function getAvailableClients()
    {
        return $this->drivers;
    }

    /**
     * removes a client class from list of drivers
     *
     * @param  string  $clientClassName
     */
    public function removeClient($clientClassName)
    {
        if (isset($this->drivers[$clientClassName]) === true) {
            unset($this->drivers[$clientClassName]);
        }
    }

    /**
     * creates a client for given config
     *
     * If no valid or suitable client can be found a stubRuntimeException will
     * be thrown.
     *
     * @param   stubSoapClientConfiguration  $config
     * @param   bool                         $mustUseWSDL  optional
     * @return  stubSoapClient
     * @throws  stubRuntimeException
     */
    public function forConfig(stubSoapClientConfiguration $config, $mustUseWSDL = false)
    {
        foreach ($this->drivers as $clientClass) {
            if (is_string($clientClass) === true) {
                $clientClass = new stubReflectionClass($clientClass);
            }
            
            try {
                $client = $clientClass->newInstanceArgs(array($config));
            } catch (Exception $e) {
                continue;
            }
            
            if (true === $mustUseWSDL && $client->supportsWSDL() === false && $config->usesWSDL() === true) {
                continue;
            }
            
            return $client;
        }
        
        throw new stubRuntimeException('No suitable SOAP client found.');
    }
}
?>