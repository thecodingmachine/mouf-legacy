<?php
/**
 * Exception to be thrown in case any component has not been configured correctly
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_exceptions
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubChainedException');
/**
 * Exception to be thrown in case any component has not been configured correctly
 *
 * @package     stubbles
 * @subpackage  lang_exceptions
 */
class stubConfigurationException extends stubChainedException
{
    // nothing to do
}
?>