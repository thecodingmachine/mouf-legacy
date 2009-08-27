<?php
/**
 * Exception to be thrown when an error on a network connection occurs.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  peer
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubChainedException');
/**
 * Exception to be thrown when an error on a network connection occurs.
 *
 * @package     stubbles
 * @subpackage  peer
 */
class stubConnectionException extends stubChainedException
{
    // intentionally empty
}
?>