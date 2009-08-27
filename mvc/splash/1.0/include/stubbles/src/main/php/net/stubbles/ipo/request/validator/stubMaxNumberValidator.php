<?php
/**
 * Validator to ensure that a value is not greater than a given maximum value.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_validator
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubValidator');
/**
 * Validator to ensure that a value is not greater than a given maximum value.
 *
 * @package     stubbles
 * @subpackage  ipo_request_validator
 */
class stubMaxNumberValidator extends stubBaseObject implements stubValidator
{
    /**
     * the maximum value to use for validation
     *
     * @var  string
     */
    protected $maxValue;

    /**
     * constructor
     *
     * @param  int|double  $maxValue  maximum value
     */
    public function __construct($maxValue)
    {
        $this->maxValue = $maxValue;
    }

    /**
     * returns the minimum value to use for validation
     *
     * @return  double
     */
    public function getValue()
    {
        return $this->maxValue;
    }

    /**
     * validate that the given value is smaller than or equal to the maximum value
     *
     * @param   int|double  $value
     * @return  bool        true if value is smaller than or equal to maximum value, else false
     */
    public function validate($value)
    {
        if ($value > $this->maxValue) {
            return false;
        }

        return true;
    }

    /**
     * returns a list of criteria for the validator
     *
     * <code>
     * array('maxNumber' => [maximum_value]);
     * </code>
     *
     * @return  array<string,mixed>  key is criterion name, value is criterion value
     */
    public function getCriteria()
    {
        return array('maxNumber' => $this->maxValue);
    }
}
?>