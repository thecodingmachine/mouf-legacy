<?php
/**
 * Validator that denies validaty of values.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_validator
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubValidator');
/**
 * Validator that denies validaty of values.
 *
 * @package     stubbles
 * @subpackage  ipo_request_validator
 */
class stubDenyValidator extends stubBaseObject implements stubValidator
{
    /**
     * validate that the given value complies with the regular expression
     *
     * @param   mixed  $value
     * @return  bool   always true
     */
    public function validate($value)
    {
        return false;
    }

    /**
     * returns a list of criteria for the validator
     *
     * @return  array<string,array>  key is criterion name, value is criterion value
     */
    public function getCriteria()
    {
        return array();
    }

}
?>