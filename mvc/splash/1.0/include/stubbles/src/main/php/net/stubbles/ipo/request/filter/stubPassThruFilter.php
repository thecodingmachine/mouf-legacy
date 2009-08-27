<?php
/**
 * Filter that filters nothing.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::stubFilter');
/**
 * Filter that filters nothing.
 *
 * Use this filter only when you know what you are doing.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter
 */
class stubPassThruFilter extends stubBaseObject implements stubFilter
{
    /**
     * execute the filter
     *
     * @param   mixed  $value  value to filter
     * @return  mixed  filtered value
     * @throws  stubFilterException
     */
    public function execute($value)
    {
        return $value;
    }
}
?>