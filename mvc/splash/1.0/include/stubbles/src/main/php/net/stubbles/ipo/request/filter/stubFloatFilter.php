<?php
/**
 * Filters on request variables of type double / float.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter
 * @version     $Id: stubFloatFilter.php 1903 2008-10-24 21:26:17Z mikey $
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::stubFilter',
                      'net::stubbles::lang::stubRegistry'
);
/**
 * Filters on request variables of type double / float.
 * 
 * This filter takes any value and casts it to float. Afterwards its multiplied
 * with 10^x (x is configureable via the registry) to get an integer value that
 * can be used for mathematical operations for accuracy. If no value for x is
 * configured in the registry the value is returned as is after the cast.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter
 */
class stubFloatFilter extends stubBaseObject implements stubFilter
{
    /**
     * registry key under which the amount of decimals is stored
     */
    const DECIMALS_REGISTRY_KEY = 'net.stubbles.number.decimals';

    /**
     * checks if given value is double, transfers into int with $decimalPlaces
     *
     * @param   mixed  $value  value to filter
     * @return  float
     */
    function execute($value)
    {
        if (null === $value) {
            return null;
        }
        
        settype($value, 'float');
        $decimals = stubRegistry::getConfig(self::DECIMALS_REGISTRY_KEY);
        if (null == $decimals) {
            return $value;
        }
        
        return (int) ($value * pow(10, $decimals));
    }
}
?>