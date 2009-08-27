<?php
/**
 * Validator to ensure that a string is not longer than a given maximum length.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_validator
 * @version     $Id: stubMaxLengthValidator.php 1763 2008-08-04 22:19:07Z mikey $
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubValidator');
/**
 * Validator to ensure that a string is not longer than a given maximum length.
 *
 * @package     stubbles
 * @subpackage  ipo_request_validator
 */
class stubMaxLengthValidator extends stubBaseObject implements stubValidator
{
    /**
     * the maximum length to use for validation
     *
     * @var  string
     */
    protected $maxLength;

    /**
     * constructor
     *
     * @param  int  $maxLength  maximum length
     */
    public function __construct($maxLength)
    {
        $this->maxLength = $maxLength;
    }

    /**
     * returns the maximum length to use for validation
     *
     * @return  int
     */
    public function getValue()
    {
        return $this->maxLength;
    }

    /**
     * validate that the given value is not longer than the maximum length
     *
     * @param   string  $value
     * @return  bool    true if value is not longer than maximal length, else false
     */
    public function validate($value)
    {
        if (iconv_strlen($value) > $this->maxLength) {
            return false;
        }
        
        return true;
    }

    /**
     * returns a list of criteria for the validator
     * 
     * <code>
     * array('maxLength' => [max_length_of_string]);
     * </code>
     *
     * @return  array<string,mixed>  key is criterion name, value is criterion value
     */
    public function getCriteria()
    {
        return array('maxLength' => $this->maxLength);
    }
}
?>