<?php
/**
 * RequestParamVariant
 * 
 * Will be triggered, if the request contains a specified parameter
 *
 * @author      Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager_types
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubEqualValidator',
                      'net::stubbles::websites::variantmanager::types::stubAbstractVariant'
);
/**
 * RequestParamVariant
 * 
 * Will be triggered, if the request contains a specified parameter
 *
 * @package     stubbles
 * @subpackage  websites_variantmanager_types
 */
class stubRequestParamVariant extends stubAbstractVariant
{
    /**
     * the name of the request parameter
     * 
     * @var  string
     */
    private $paramName  = null;
    /**
     * the value of the request parameter
     * 
     * @var  string
     */
    private $paramValue = null;
    
    /**
     * check whether the variant is an enforcing variant
     * 
     * @param   stubSession  $session  access to session
     * @param   stubRequest  $request  access to request parameters
     * @return  bool
     * @throws  stubVariantConfigurationException
     */
    public function isEnforcing(stubSession $session, stubRequest $request)
    {
        return $this->isValid($session, $request);
    }
    
    /**
     * check whether the variant is valid
     * 
     * @param   stubSession  $session  access to session
     * @param   stubRequest  $request  access to request parameters
     * @return  bool
     * @throws  stubVariantConfigurationException
     */
    public function isValid(stubSession $session, stubRequest $request)
    {
        if (null == $this->paramName) {
            throw new stubVariantConfigurationException('RequestParamVariant requires the param name to be set.');
        }
        
        if ($request->hasValue($this->paramName) == false) {
            return false;
        }
        
        if (null == $this->paramValue) {
            return true;
        }
        
        return $request->validateValue(new stubEqualValidator($this->paramValue), $this->paramName);
    }
    
    /**
     * Set the name of the request parameter
     * 
     * @param  string  $paramName  the paramName to set
     */
    public function setParamName($paramName)
    {
        $this->paramName = $paramName;
    }

    /**
     * Set the desired value of the request parameter
     * 
     * @param  string  $paramValue  the paramValue to set
     */
    public function setParamValue($paramValue)
    {
        $this->paramValue = $paramValue;
    }
}
?>