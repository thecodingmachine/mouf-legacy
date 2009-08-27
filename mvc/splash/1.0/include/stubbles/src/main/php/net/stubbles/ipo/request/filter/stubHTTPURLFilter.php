<?php
/**
 * Class for filtering strings for valid HTTP URLs.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::stubFilter',
                      'net::stubbles::peer::http::stubHTTPURL'
);
/**
 * Class for filtering strings for valid HTTP URLs.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter
 */
class stubHTTPURLFilter extends stubBaseObject implements stubFilter
{
    /**
     * request value error factory
     *
     * @var  stubRequestValueErrorFactory
     */
    protected $rveFactory;
    /**
     * switch whether DNS should be checked or not
     *
     * @var  bool
     */
    protected $checkDNS  = false;

    /**
     * constructor
     *
     * @param  stubRequestValueErrorFactory  $rveFactory  factory to create stubRequestValueErrors
     */
    public function __construct(stubRequestValueErrorFactory $rveFactory)
    {
        $this->rveFactory = $rveFactory;
    }

    /**
     * switch the dns check on or off
     *
     * @param  bool  $checkDNS
     */
    public function setCheckDNS($checkDNS)
    {
        $this->checkDNS = $checkDNS;
    }

    /**
     * checks whether DNS check is enabled
     *
     * @return  bool
     */
    public function isDNSCheckEnabled()
    {
        return $this->checkDNS;
    }

    /**
     * check if value is a valid HTTP URL
     *
     * @param   string               $value
     * @return  string               valid HTTP URL
     * @throws  stubFilterException  when $value has errors
     */
    public function execute($value)
    {
        try {
            $http = stubHTTPURL::fromString($value);
        } catch (stubMalformedURLException $murle) {
            throw new stubFilterException($this->rveFactory->create('URL_INCORRECT'));
        }
        
        if (null === $http) {
            return null;
        }
        
        if (true === $this->checkDNS && $http->checkDNS() === false) {
            throw new stubFilterException($this->rveFactory->create('URL_NOT_AVAILABLE'));
        }
    
        return $http->get(!$http->hasDefaultPort());
    }
}
?>