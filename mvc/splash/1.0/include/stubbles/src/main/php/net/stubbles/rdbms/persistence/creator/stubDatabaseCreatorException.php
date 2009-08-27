<?php
/**
 * Exception to be thrown if a problem in the creator occurs.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_creator
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubChainedException');
/**
 * Exception to be thrown if a problem in the creator occurs.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_creator
 */
class stubDatabaseCreatorException extends stubChainedException
{
    // intentionally empty
}
?>