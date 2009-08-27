<?php
/**
 * Filter annotation for mail addresses.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAnnotation',
                      'net::stubbles::ipo::request::broker::annotations::stubAbstractFilterAnnotation',
                      'net::stubbles::ipo::request::filter::stubMailFilter',
                      'net::stubbles::ipo::request::validator::stubMailValidator'
);
/**
 * Filter annotation for mail addresses.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
class stubMailFilterAnnotation extends stubAbstractFilterAnnotation implements stubAnnotation
{
    /**
     * returns the filter defined by the annotation
     *
     * @return  stubMailFilter
     * @throws  stubRequestBrokerException
     */
    protected function doGetFilter()
    {
        $mailFilter  = new stubMailFilter($this->createRVEFactory(), new stubMailValidator());
        return $mailFilter;
    }
}
?>