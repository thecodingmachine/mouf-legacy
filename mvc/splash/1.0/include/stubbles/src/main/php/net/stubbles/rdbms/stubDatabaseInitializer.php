<?php
/**
 * Marker interface for database initializers.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms
 */
stubClassLoader::load('net::stubbles::lang::initializer::stubInitializer');
/**
 * Marker interface for database initializers.
 *
 * @package     stubbles
 * @subpackage  rdbms
 */
interface stubDatabaseInitializer extends stubInitializer
{
    // intentionally empty
}
?>