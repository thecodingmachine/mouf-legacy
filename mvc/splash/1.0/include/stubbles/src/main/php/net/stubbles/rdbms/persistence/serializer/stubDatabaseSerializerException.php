<?php
/**
 * Exception to be thrown in case the serializer locates a problem.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence
 */
stubClassLoader::load('net::stubbles::rdbms::stubDatabaseException');
/**
 * Exception to be thrown in case the serializer locates a problem.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence
 */
class stubDatabaseSerializerException extends stubDatabaseException
{
    // intentionally empty
}
?>