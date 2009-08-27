<?php
/**
 * Exception to be thrown in case a binding is invalid or missing
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_exceptions
 */

stubClassLoader::load('net::stubbles::ioc::exceptions::stubInjectionException');

/**
 * Exception to be thrown in case a binding is invalid or missing
 *
 * @package     stubbles
 * @subpackage  ioc_exceptions
 */
class stubBindingException extends stubInjectionException
{
    // intentionally empty
}
?>