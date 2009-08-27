<?php
/**
 * Exception to be thrown if a problem in the eraser occurs.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_eraser
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubChainedException');
/**
 * Exception to be thrown if a problem in the eraser occurs.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_eraser
 */
class stubDatabaseEraserException extends stubChainedException
{
    // intentionally empty
}
?>