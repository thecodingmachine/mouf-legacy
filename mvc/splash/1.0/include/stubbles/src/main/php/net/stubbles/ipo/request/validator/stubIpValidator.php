<?php
/**
 * Class for validating that something is an ip address.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_validator
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubValidator');
/**
 * Class for validating that something is an ip address.
 *
 * @package     stubbles
 * @subpackage  ipo_request_validator
 */
class stubIpValidator extends stubBaseObject implements stubValidator
{
    /**
     * validate that the given value is eqal in content and type to the expected value
     *
     * @param   mixed  $value
     * @return  bool   true if value is equal to expected value, else false
     */
    public function validate($value)
    {
        return (bool) preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $value);
    }
    
    /**
     * returns a list of criteria for the validator
     * 
     * <code>
     * array('expected' => [expected_value]);
     * </code>
     *
     * @return  array<string,mixed>  key is criterion name, value is criterion value
     */
    public function getCriteria()
    {
        return array();
    }
}
?>