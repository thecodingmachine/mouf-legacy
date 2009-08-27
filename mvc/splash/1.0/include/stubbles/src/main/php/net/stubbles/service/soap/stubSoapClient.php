<?php
/**
 * Interface for SOAP clients.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_soap
 */
stubClassLoader::load('net::stubbles::service::soap::stubSoapClientConfiguration',
                      'net::stubbles::service::soap::stubSoapException'
);
/**
 * Interface for SOAP clients.
 *
 * @package     stubbles
 * @subpackage  service_soap
 */
interface stubSoapClient extends stubObject
{
    /**
     * constructor
     *
     * @param  stubSoapClientConfiguration  $config
     */
    #public function __construct(stubSoapClientConfiguration $config);

    /**
     * returns the configuration
     *
     * @return  stubSoapClientConfiguration
     */
    public function getConfig();

    /**
     * checks whether the client supports WSDL or not
     *
     * @return  bool
     */
    public function supportsWSDL();

    /**
     * invoke method call
     *
     * @param   string  $method  name of method to invoke
     * @param   array   $args    list of arguments for method
     * @return  mixed
     * @throws  stubSoapException
     */
    public function invoke($method, array $args = array());

    /**
     * returns data about last invoke() call usable for debugging
     *
     * @return  array<string,mixed>
     */
    public function getDebugData();
}
?>