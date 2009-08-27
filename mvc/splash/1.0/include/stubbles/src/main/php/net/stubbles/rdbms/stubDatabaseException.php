<?php
/**
 * Exception for general database problems.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubChainedException');
/**
 * Exception for general database problems.
 *
 * @package     stubbles
 * @subpackage  rdbms
 */
class stubDatabaseException extends stubChainedException
{
    // intentionally empty
}
?>