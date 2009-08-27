<?php
/**
 * Exception to be thrown in case the variant configuration contains an error.
 * 
 * @author      Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubChainedException');
/**
 * Exception to be thrown in case the variant configuration contains an error.
 * 
 * @package     stubbles
 * @subpackage  websites_variantmanager
 */
class stubVariantConfigurationException extends stubChainedException
{
    // intentionally empty
}
?>