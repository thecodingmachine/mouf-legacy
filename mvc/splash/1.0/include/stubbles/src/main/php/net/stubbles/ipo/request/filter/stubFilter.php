<?php
/**
 * Interface for filters.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::stubFilterException');
/**
 * Interface for filter.
 * 
 * Filters can be used to take request values, validate them and change them
 * into any other value.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter
 */
interface stubFilter
{
    /**
     * execute the filter
     *
     * @param   mixed                $value  value to filter
     * @return  mixed                filtered value
     * @throws  stubFilterException  in case $value has errors
     */
    public function execute($value);
}
?>