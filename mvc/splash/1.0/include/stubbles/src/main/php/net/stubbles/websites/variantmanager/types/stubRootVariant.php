<?php
/**
 * Root variant that contains all other variants.
 * 
 * @author      Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager_types
 */
stubClassLoader::load('net::stubbles::websites::variantmanager::types::stubAbstractVariant');
/**
 * Root variant that contains all other variants.
 * 
 * @package     stubbles
 * @subpackage  websites_variantmanager_types
 */
class stubRootVariant extends stubAbstractVariant
{
    /**
     * constructor
     */
    public function __construct()
    {
        $this->name = 'root';
    }
    
    /**
     * sets the name of the variant
     *
     * @param  string  $name
     */
    public function setName($name)
    {
        // can not reset name of RootVariant
    }
    
    /**
     * check whether the variant is an enforcing variant
     * 
     * @param   stubSession  $session  access to session
     * @param   stubRequest  $request  access to request parameters
     * @return  bool
     */
    public function isEnforcing(stubSession $session, stubRequest $request)
    {
        return true;
    }
    
    /**
     * check whether the variant is valid
     * 
     * @param   stubSession  $session  access to session
     * @param   stubRequest  $request  access to request parameters
     * @return  bool
     */
    public function isValid(stubSession $session, stubRequest $request)
    {
        return true;
    }
}
?>