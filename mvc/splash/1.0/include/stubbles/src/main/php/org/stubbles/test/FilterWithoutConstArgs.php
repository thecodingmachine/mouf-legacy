<?php
/**
 * Filter that requires no argument for its constructor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles_test
 * @subpackage  filterprovider
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::stubFilter');
/**
 * Filter that requires no argument for its constructor.
 *
 * @package     stubbles_test
 * @subpackage  filterprovider
 */
class FilterWithoutConstArgs extends stubBaseObject implements stubFilter
{
    /**
     * does the filtering
     *
     * @param   string  $value
     * @return  string
     */
    public function execute($value)
    {
         return $value;
    }
}
?>