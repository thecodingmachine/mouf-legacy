<?php
/**
 * Basic class for filters on request variables of type integer.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter
 * @version     $Id: stubIntegerFilter.php 1903 2008-10-24 21:26:17Z mikey $
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::stubFilter');
/**
 * Basic class for filters on request variables of type integer.
 *
 * This filter takes any value and casts it to int.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter
 */
class stubIntegerFilter extends stubBaseObject implements stubFilter
{
    /**
     * checks if given value is an integer
     *
     * @param   mixed  $value  value to filter
     * @return  int
     */
    function execute($value)
    {
        if (null !== $value) {
            settype($value, 'integer');
        }
        
        return $value;
    }
}
?>