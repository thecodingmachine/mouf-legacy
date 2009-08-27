<?php
/**
 * Integration test for database.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test_integration
 * @version     $Id: DatabaseTestCase.php 1906 2008-10-25 15:14:00Z mikey $
 */
stubClassLoader::load('net::stubbles::rdbms::stubDatabaseXJConfInitializer');
/**
 * Integration test for database.
 *
 * @package     stubbles
 * @subpackage  test_integration
 * @group       integration
 */
class DatabaseTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * helper method to initialize the connection pool
     */
    protected function initInitializer()
    {
        $dbInitializer = new stubDatabaseXJConfInitializer();
        $dbInitializer->init();
    }

    /**
     * assure that creating the logger instances works correct
     * @test
     */
    public function databaseInitializer()
    {
        $this->initInitializer();
        $this->assertTrue(stubDatabaseConnectionPool::hasConnectionData());
        $connectionData = stubDatabaseConnectionPool::getConnectionData();
        $this->assertEquals('mysql:host=localhost;dbname=example', $connectionData->getDSN());
        $this->assertEquals('root', $connectionData->getUserName());
        $this->assertEquals('foo', $connectionData->getPassword());
        $this->assertTrue($connectionData->hasInitialQuery());
        $this->assertEquals('set names utf8', $connectionData->getInitialQuery());
        
        // cached
        $this->initInitializer();
        $this->assertTrue(stubDatabaseConnectionPool::hasConnectionData());
        $connectionData = stubDatabaseConnectionPool::getConnectionData();
        $this->assertEquals('mysql:host=localhost;dbname=example', $connectionData->getDSN());
        $this->assertEquals('root', $connectionData->getUserName());
        $this->assertEquals('foo', $connectionData->getPassword());
        $this->assertTrue($connectionData->hasInitialQuery());
        $this->assertEquals('set names utf8', $connectionData->getInitialQuery());
    }
}
?>