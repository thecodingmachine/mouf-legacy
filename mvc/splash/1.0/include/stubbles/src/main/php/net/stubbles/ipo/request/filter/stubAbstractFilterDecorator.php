<?php
/**
 * Base class for filter decorators:
 * Delegates everything to the decorated filter.
 *
 * @author      Richard Sternagel <richard.sternagel@1und1.de>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::stubFilter');
/**
 * Base class for filter decorators:
 * Delegates everything to the decorated filter.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter
 */
abstract class stubAbstractFilterDecorator extends stubBaseObject implements stubFilter
{
    /**
     * decorated filter
     *
     * @var  stubFilter
     */
    protected $decoratedFilter;

    /**
     * setter method
     *
     * @param  stubFilter  $decoratedFilter
     */
    public function setDecoratedFilter($decoratedFilter)
    {
        $this->decoratedFilter = $decoratedFilter;
    }

    /**
     * getter method
     *
     * @return  stubFilter
     */
    public function getDecoratedFilter()
    {
        return $this->decoratedFilter;
    }
}
?>