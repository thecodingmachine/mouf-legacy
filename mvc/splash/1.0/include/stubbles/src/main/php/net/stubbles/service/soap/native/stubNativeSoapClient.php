<?php
/**
 * Implementation of a SOAP client using PHP's native SOAPClient class.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_soap_native
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubIllegalArgumentException',
                      'net::stubbles::lang::exceptions::stubRuntimeException',
                      'net::stubbles::service::soap::stubAbstractSoapClient',
                      'net::stubbles::service::soap::stubSoapFault'
);
/**
 * Implementation of a SOAP client using PHP's native SOAPClient class.
 *
 * @package     stubbles
 * @subpackage  service_soap_native
 * @see         http://php.net/soap
 */
class stubNativeSoapClient extends stubAbstractSoapClient
{
    /**
     * constructor
     *
     * @param   stubSoapClientConfiguration  $config
     * @throws  stubRuntimeException
     * @throws  stubIllegalArgumentException
     */
    public function __construct(stubSoapClientConfiguration $config)
    {
        if (extension_loaded('soap') === false) {
            throw new stubRuntimeException('net::stubbles::service::soap::stubNativeSoapClient requires PHP extension soap.');
        }
        
        $version = $config->getVersion();
        if (null !== $version && SOAP_1_1 !== $version && SOAP_1_2 !== $version) {
            throw new stubIllegalArgumentException('Configuration error: version must be one of SOAP_1_1 or SOAP_1_2.');
        }
        
        $requestStyle = $config->getRequestStyle();
        if (null === $requestStyle) {
            $config->setRequestStyle(SOAP_RPC);
        } elseif (SOAP_RPC !== $requestStyle && SOAP_DOCUMENT !== $requestStyle) {
            throw new stubIllegalArgumentException('Configuration error: request style must be one of SOAP_RPC or SOAP_DOCUMENT.');
        }
        
        $usage = $config->getUsage();
        if (null === $usage) {
            $config->setUsage(SOAP_ENCODED);
        } elseif (SOAP_ENCODED !== $usage && SOAP_LITERAL !== $usage) {
            throw new stubIllegalArgumentException('Configuration error: usage must be one of SOAP_ENCODED or SOAP_LITERAL.');
        }
        
        parent::__construct($config);
    }

    /**
     * checks whether the client supports WSDL or not
     *
     * @return  bool
     */
    public function supportsWSDL()
    {
        return true;
    }

    /**
     * invoke method call
     *
     * @param   string  $method  name of method to invoke
     * @param   array   $args    list of arguments for method
     * @return  mixed
     * @throws  stubSoapException
     */
    public function invoke($method, array $args = array())
    {
        $options = array('exceptions' => false,
                         'trace'      => true,
                         'encoding'   => $this->config->getDataEncoding()
                   );
        if (null !== $this->config->getVersion()) {
            $options['version'] = $this->config->getVersion();
        }
        
        if ($this->config->hasClassMapping() === true) {
            $options['classmap'] = $this->config->getClassMapping();
        }
        
        $endPoint = $this->config->getEndPoint();
        $user     = $endPoint->getUser();
        if (null !== $user) {
            $options['login'] = $user;
        }
        
        $password = $endPoint->getPassword();
        if (null !== $password) {
            $options['password'] = $password;
        }
        
        if (true === $this->config->usesWSDL()) {
            $client = $this->createClient($endPoint->get(true), $options);
        } else {
            $options['location'] = $endPoint->get(true);
            $options['uri']      = $this->config->getURI();
            $options['style']    = $this->config->getRequestStyle();
            $options['use']      = $this->config->getUsage();
            $client              = $this->createClient(null, $options);
        }
        
        $result = $client->__soapCall($method, array_values($args));
        $this->debugData = array('endPoint'           => $endPoint->get(),
                                 'usedWsdl'           => $this->config->usesWSDL(),
                                 'lastMethod'         => $method,
                                 'lastRequestHeader'  => $client->__getLastRequestHeaders(),
                                 'lastRequest'        => $client->__getLastRequest(),
                                 'lastResponseHeader' => $client->__getLastResponseHeaders(),
                                 'lastResponse'       => $client->__getLastResponse()
                           );
        if (is_soap_fault($result) === true) {
            throw new stubSoapException(new stubSoapFault($result->faultcode,
                                                          $result->faultstring,
                                                          (isset($result->faultactor) === false) ? (null) : ($result->faultactor),
                                                          (isset($result->detail) === false) ? (null) : ($result->detail)
                                            )
                      );
        }
        
        return $result;
    }

    /**
     * creates the client
     *
     * @param   string               $url
     * @param   array<string,mixed>  $options
     * @return  SoapClient
     */
    // @codeCoverageIgnoreStart
    protected function createClient($url, $options)
    {
        return new SoapClient($url, $options);
    }
    // @codeCoverageIgnoreEnd
}
?>