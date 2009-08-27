<?php
/**
 * Filter annotation for HTTP URLs.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAnnotation',
                      'net::stubbles::ipo::request::filter::stubHTTPURLFilter',
                      'net::stubbles::ipo::request::broker::annotations::stubAbstractFilterAnnotation'
);
/**
 * Filter annotation for HTTP URLs.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
class stubHTTPURLFilterAnnotation extends stubAbstractFilterAnnotation implements stubAnnotation
{
    /**
     * switch whether DNS should be checked or not
     *
     * @var  bool
     */
    protected $checkDNS = false;

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
     * returns the filter defined by the annotation
     *
     * @return  stubHTTPURLFilter
     * @throws  stubRequestBrokerException
     */
    protected function doGetFilter()
    {
        $httpURLFilter = new stubHTTPURLFilter($this->createRVEFactory());
        $httpURLFilter->setCheckDNS($this->checkDNS);
        return $httpURLFilter;
    }
}
?>