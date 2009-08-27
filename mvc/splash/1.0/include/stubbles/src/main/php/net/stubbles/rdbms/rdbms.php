<?php
/**
 * Database handling bootstrap file.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms
 */
// @codeCoverageIgnoreStart
stubClassLoader::load('net::stubbles::rdbms::stubDatabaseException',
                      'net::stubbles::rdbms::stubDatabaseStatement',
                      'net::stubbles::rdbms::stubDatabaseResult',
                      'net::stubbles::rdbms::stubDatabaseConnectionData',
                      'net::stubbles::rdbms::stubDatabaseConnection',
                      'net::stubbles::rdbms::stubDatabaseConnectionPool'
);
// @codeCoverageIgnoreEnd
?>