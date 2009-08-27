<?php
/**
 * Base class for composite validators.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_validator
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubCompositeValidator',
                      'net::stubbles::lang::exceptions::stubRuntimeException'
);
/**
 * Base class for composite validators.
 * 
 * A composite validator can be used to combine two or more validators
 * into a single validator which then applies all those validators for the
 * value to validate.
 *
 * @package     stubbles
 * @subpackage  ipo_request_validator
 */
abstract class stubAbstractCompositeValidator extends stubBaseObject implements stubCompositeValidator
{
    /**
     * list of validators to combine
     *
     * @var  array<stubValidator>
     */
    protected $validators = array();
    
    /**
     * add a validator
     *
     * @param  stubValidator  $validator
     */
    public function addValidator(stubValidator $validator)
    {
        $this->validators[] = $validator;
    }
    
    /**
     * validate the given value
     *
     * @param   mixed  $value
     * @return  bool   true if value is ok, else false
     * @throws  stubRuntimeException
     */
    public function validate($value)
    {
        if (count($this->validators) == 0) {
            throw new stubRuntimeException('No validators set for composite ' . __CLASS__);
        }
        
        return $this->doValidate($value);
    }
    
    /**
     * validate the given value
     *
     * @param   mixed  $value
     * @return  bool   true if value is ok, else false
     */
    protected abstract function doValidate($value);
    
    /**
     * returns a list of criteria for the validator
     *
     * @return  array<string,mixed>  key is criterion name, value is criterion value
     */
    public function getCriteria()
    {
        $criterias = array();
        foreach ($this->validators as $validator) {
            $criterias = array_merge($criterias, $validator->getCriteria());
        }
        
        return $criterias;
    }
}
?>