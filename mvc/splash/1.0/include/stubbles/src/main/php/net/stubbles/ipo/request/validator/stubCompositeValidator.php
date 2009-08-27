<?php
/**
 * Interface for composite validators.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_validator
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubValidator');
/**
 * Interface for composite validators.
 * 
 * Composite validators can be used to combine two or more validators
 * into a single validator.
 *
 * @package     stubbles
 * @subpackage  ipo_request_validator
 */
interface stubCompositeValidator extends stubValidator
{
    /**
     * add a validator
     *
     * @param  stubValidator  $validator
     */
    public function addValidator(stubValidator $validator);
}
?>