<?php
/**
 * Validator to ensure that a string is a mail address.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_validator
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubValidator');
/**
 * Validator to ensure that a string is a mail address.
 *
 * @package     stubbles
 * @subpackage  ipo_request_validator
 */
class stubMailValidator extends stubBaseObject implements stubValidator
{
    /**
     * validate that the given value is not longer than the maximum length
     *
     * @param   string  $value
     * @return  bool    true if value is not longer than maximal length, else false
     */
    public function validate($value)
    {
        if (null == $value || strlen($value) == 0) {
            return false;
        }
        
        $url = @parse_url('mailto://' . $value);
        if (isset($url['host']) === false || preg_match('/^([a-z0-9-]*)\.([a-z]{2,4})$/', $url['host']) == false) {
            return false;
        }
        
        if (isset($url['user']) === false || strlen($url['user']) == 0 || preg_match('/^[0-9a-z]([-_\.]?[0-9a-z])*$/', $url['user']) == false) {
            return false;
        }
        
        return true;
    }
    
    /**
     * returns a list of criteria for the validator
     * 
     * <code>
     * array();
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