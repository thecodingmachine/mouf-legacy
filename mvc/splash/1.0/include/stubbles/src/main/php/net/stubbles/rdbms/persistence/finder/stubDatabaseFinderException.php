<?php
/**
 * Exception to be thrown if a problem in the finder occurs.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_finder
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubChainedException');
/**
 * Exception to be thrown if a problem in the finder occurs.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_finder
 */
class stubDatabaseFinderException extends stubChainedException
{
    // intentionally empty
}
?>