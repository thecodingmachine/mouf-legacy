<?php
/**
 * Basic implementation for SOAP clients.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_soap
 */
stubClassLoader::load('net::stubbles::service::soap::stubSoapClient');
/**
 * Basic implementation for SOAP clients.
 *
 * @package     stubbles
 * @subpackage  service_soap
 */
abstract class stubAbstractSoapClient extends stubBaseObject implements stubSoapClient
{
    /**
     * configuration data for client
     *
     * @var  stubSoapClientConfiguration
     */
    protected $config;
    /**
     * debug data for last call to invoke()
     *
     * @var  array<string,mixed>
     */
    protected $debugData = array();

    /**
     * constructor
     *
     * @param  stubSoapClientConfiguration  $config
     */
    public function __construct(stubSoapClientConfiguration $config)
    {
        $this->config = $config;
    }

    /**
     * returns the configuration
     *
     * @return  stubSoapClientConfiguration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * returns data about last invoke() call usable for debugging
     *
     * @return  array<string,mixed>
     */
    public function getDebugData()
    {
        return $this->debugData;
    }
}
?>