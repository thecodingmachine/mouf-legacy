<?php
/**
 * Exception to be thrown in case a method is called which is not supported by
 * a specific implementation.
 * 
 * @author      Frank Kleine  <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_exceptions
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubRuntimeException');
/**
 * Exception to be thrown in case a method is called which is not supported by
 * a specific implementation.
 * 
 * @package     stubbles
 * @subpackage  lang_exceptions
 */
class stubMethodNotSupportedException extends stubRuntimeException
{
    // intentionally empty
}
?>
