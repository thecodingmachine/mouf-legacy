<?php
/**
 * Configuration container for SOAP clients.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_soap
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubIllegalArgumentException',
                      'net::stubbles::peer::http::stubHTTPURL'
);
/**
 * Configuration container for SOAP clients.
 *
 * @package     stubbles
 * @subpackage  service_soap
 */
class stubSoapClientConfiguration extends stubBaseObject
{
    /**
     * the url where the SOAP service can be reached at
     *
     * @var  stubHTTPURL
     */
    protected $endPoint;
    /**
     * the uri to use
     *
     * @var  string
     */
    protected $uri;
    /**
     * whether to use WSDL mode or not
     *
     * @var  bool
     */
    protected $useWSDL      = false;
    /**
     * the SOAP version to use
     *
     * @var  string
     */
    protected $version      = null;
    /**
     * encoding of the data
     *
     * @var  string
     */
    protected $dataEncoding = 'iso-8859-1';
    /**
     * style of the request
     *
     * @var  string
     */
    protected $requestStyle = null;
    /**
     * the usage
     *
     * @var  string
     */
    protected $usage        = null;
    /**
     * list of wsdl type to class mapping
     *
     * @var  array<string,string>
     */
    protected $classMapping = array();

    /**
     * constructor
     *
     * @param   string|stubHTTPURL  $endPoint
     * @param   string              $uri
     * @throws  stubIllegalArgumentException
     */
    public function __construct($endPoint, $uri)
    {
        if (is_string($endPoint) === true) {
            $endPoint = stubHTTPURL::fromString($endPoint);
            if (null === $endPoint) {
                throw new stubIllegalArgumentException('Endpoint must be a string denoting an URL or an instance of net::stubbles::peer::http::stubHTTPURL.');
            }
        } elseif (($endPoint instanceof stubHTTPURL) === false) {
            throw new stubIllegalArgumentException('Endpoint must be a string denoting an URL or an instance of net::stubbles::peer::http::stubHTTPURL.');
        }
        
        $this->endPoint = $endPoint;
        $this->uri      = $uri;
    }

    /**
     * returns url where the SOAP service can be reached at
     *
     * @return  stubHTTPURL
     */
    public function getEndpoint()
    {
        return $this->endPoint;
    }

    /**
     * returns the uri to use
     *
     * @return  string
     */
    public function getURI()
    {
        return $this->uri;
    }

    /**
     * switch whether to use WSDL mode or not
     *
     * @param  bool  $useWSDL
     */
    public function useWSDL($useWSDL)
    {
        $this->useWSDL = (bool) $useWSDL;
    }

    /**
     * checks whether to use WSDL mode or not
     *
     * @return  bool
     */
    public function usesWSDL()
    {
        return $this->useWSDL;
    }

    /**
     * sets the SOAP version
     *
     * @param  string  $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * returns the SOAP version
     *
     * @return  string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * sets the encoding of the data
     *
     * @param  string  $dataEncoding
     */
    public function setDataEncoding($dataEncoding)
    {
        $this->dataEncoding = $dataEncoding;
    }

    /**
     * returns the encoding of the data
     *
     * @return  string
     */
    public function getDataEncoding()
    {
        return $this->dataEncoding;
    }

    /**
     * sets the style of the request
     *
     * @param  string  $requestStyle
     */
    public function setRequestStyle($requestStyle)
    {
        $this->requestStyle = $requestStyle;
    }

    /**
     * returns the style of the request
     *
     * @return  string
     */
    public function getRequestStyle()
    {
        return $this->requestStyle;
    }

    /**
     * sets the usage
     *
     * @param  string  $usage
     */
    public function setUsage($usage)
    {
        $this->usage = $usage;
    }

    /**
     * returns the usage
     *
     * @return  string
     */
    public function getUsage()
    {
        return $this->usage;
    }

    /**
     * registers a class mapping
     *
     * @param  string           $wsdlType
     * @param  ReflectionClass  $class
     */
    public function registerClassMapping($wsdlType, ReflectionClass $class)
    {
        $this->classMapping[$wsdlType] = $class->getName();
    }

    /**
     * checks whether at least one class mapping exists
     *
     * @return  bool
     */
    public function hasClassMapping()
    {
        return (count($this->classMapping) > 0);
    }

    /**
     * returns the class mapping
     *
     * @return  array<string,string>
     */
    public function getClassMapping()
    {
        return $this->classMapping;
    }
}
?>