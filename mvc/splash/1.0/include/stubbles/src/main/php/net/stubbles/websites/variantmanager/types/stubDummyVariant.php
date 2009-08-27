<?php
/**
 * This variant type is only used to include variants in the
 * configuration, that can only be set from php code.
 *
 * @author      Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager_types
 */
stubClassLoader::load('net::stubbles::websites.variantmanager::types::stubAbstractVariant');
/**
 * This variant type is only used to include variants in the
 * configuration, that can only be set from php code.
 *
 * @package     stubbles
 * @subpackage  websites_variantmanager_types
 */
class stubDummyVariant extends stubAbstractVariant
{
    /**
     * check whether the variant is an enforcing variant
     * 
     * @param   stubSession  $session  access to session
     * @param   stubRequest  $request  access to request parameters
     * @return  bool
     */
    public function isEnforcing(stubSession $session, stubRequest $request)
    {
        return false;
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
        return false;
    }
}
?>