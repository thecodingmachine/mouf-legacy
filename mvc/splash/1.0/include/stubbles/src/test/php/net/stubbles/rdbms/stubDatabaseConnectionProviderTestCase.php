<?php
/**
 * Test for net::stubbles::rdbms::stubDatabaseConnectionProvider.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_test
 */
stubClassLoader::load('net::stubbles::rdbms::stubDatabaseConnectionProvider');
/**
 * Test for net::stubbles::rdbms::stubDatabaseConnectionProvider.
 *
 * @package     stubbles
 * @subpackage  rdbms_test
 * @group       rdbms
 */
class stubDatabaseConnectionProviderTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * set up test environment
     */
    public function setUp()
    {
        if (class_exists('MockstubDatabaseConnection1') === false) {
            PHPUnit_Framework_MockObject_Mock::generate('stubDatabaseConnection', array(), 'MockstubDatabaseConnection1');
        }
        
        if (class_exists('MockstubDatabaseConnection2') === false) {
            PHPUnit_Framework_MockObject_Mock::generate('stubDatabaseConnection', array(), 'MockstubDatabaseConnection2');
        }
        
        stubDatabaseConnectionPool::removeConnectionData();
    }

    /**
     * clean test environment
     */
    public function tearDown()
    {
        stubDatabaseConnectionPool::removeConnectionData();
    }

    /**
     * named connection does not exist and fallback is disabled > exception
     *
     * @test
     * @expectedException  stubDatabaseException
     */
    public function namedConnectionDoesNotExistFallDisabled()
    {
        $connectionProvider = new stubDatabaseConnectionProvider(false);
        $connectionProvider->get('stubDatabaseConnection', 'namedFoo');
    }

    /**
     * named connection does not exist and fallback is enabled > default connection
     *
     * @test
     */
    public function namedConnectionDoesNotExistFallEnabled()
    {
        $connectionData = new stubDatabaseConnectionData();
        $connectionData->setConnectionClassName('MockstubDatabaseConnection1');
        stubDatabaseConnectionPool::addConnectionData($connectionData);
        $connectionProvider = new stubDatabaseConnectionProvider();
        $connection = $connectionProvider->get('stubDatabaseConnection', 'namedFoo');
        $this->assertType('MockstubDatabaseConnection1', $connection);
    }

    /**
     * named connection does exist > default connection
     *
     * @test
     */
    public function namedConnectionDoesExist()
    {
        $connectionData = new stubDatabaseConnectionData();
        $connectionData->setConnectionClassName('MockstubDatabaseConnection2');
        $connectionData->setId('namedFoo');
        stubDatabaseConnectionPool::addConnectionData($connectionData);
        $connectionData = new stubDatabaseConnectionData();
        $connectionData->setConnectionClassName('MockstubDatabaseConnection1');
        stubDatabaseConnectionPool::addConnectionData($connectionData);
        $connectionProvider = new stubDatabaseConnectionProvider();
        $connection = $connectionProvider->get('stubDatabaseConnection', 'namedFoo');
        $this->assertType('MockstubDatabaseConnection2', $connection);
        $connectionProvider = new stubDatabaseConnectionProvider(false);
        $connection = $connectionProvider->get('stubDatabaseConnection', 'namedFoo');
        $this->assertType('MockstubDatabaseConnection2', $connection);
    }

    /**
     * always return the default connection if no named connection is requested
     *
     * @test
     */
    public function defaultConnection()
    {
        $connectionData = new stubDatabaseConnectionData();
        $connectionData->setConnectionClassName('MockstubDatabaseConnection1');
        stubDatabaseConnectionPool::addConnectionData($connectionData);
        $connectionProvider = new stubDatabaseConnectionProvider();
        $connection = $connectionProvider->get('stubDatabaseConnection');
        $this->assertType('MockstubDatabaseConnection1', $connection);
        $connectionProvider = new stubDatabaseConnectionProvider(false);
        $connection = $connectionProvider->get('stubDatabaseConnection');
        $this->assertType('MockstubDatabaseConnection1', $connection);
    }
}
?>