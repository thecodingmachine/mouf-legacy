<?php
/**
 * Exception for wrapping xjconf exceptions.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_xjconf
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubChainedException');
/**
 * Exception for wrapping xjconf exceptions.
 *
 * @package     stubbles
 * @subpackage  util_xjconf
 */
class stubXJConfException extends stubChainedException
{
    // intentionally empty
}
?>