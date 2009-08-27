<?php
/**
 * Test for net::stubbles::rdbms::stubDatabaseConnectionPool.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_test
 */
stubClassLoader::load('net::stubbles::rdbms::stubDatabaseConnectionPool');
/**
 * Test for net::stubbles::rdbms::stubDatabaseConnectionPool.
 *
 * @package     stubbles
 * @subpackage  rdbms_test
 * @group       rdbms
 */
class stubDatabaseConnectionPoolTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * set up test environment
     */
    public function setUp()
    {
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
     * check that setting/getting the connection data works as expected
     *
     * @test
     */
    public function connectionData()
    {
        $this->assertFalse(stubDatabaseConnectionPool::hasConnectionData());
        $this->assertNull(stubDatabaseConnectionPool::getConnectionData());
        $this->assertEquals(array(), stubDatabaseConnectionPool::getConnectionDataIds());
        $connectionData = new stubDatabaseConnectionData();
        stubDatabaseConnectionPool::addConnectionData($connectionData);
        $this->assertTrue(stubDatabaseConnectionPool::hasConnectionData());
        $this->assertSame($connectionData, stubDatabaseConnectionPool::getConnectionData());
        $this->assertEquals(array(stubDatabaseConnectionData::DEFAULT_ID), stubDatabaseConnectionPool::getConnectionDataIds());
        $this->assertFalse(stubDatabaseConnectionPool::hasConnectionData('foo'));
        $this->assertNull(stubDatabaseConnectionPool::getConnectionData('foo'));
        stubDatabaseConnectionPool::removeConnectionData();
        $this->assertFalse(stubDatabaseConnectionPool::hasConnectionData());
        $this->assertEquals(array(), stubDatabaseConnectionPool::getConnectionDataIds());
    }

    /**
     * assert that getting a connection without a saved connection and without
     * connection data throws an stubDatabaseException
     *
     * @test
     * @expectedException  stubDatabaseException
     */
    public function noConnectionNoData()
    {
        stubDatabaseConnectionPool::getConnection();
    }

    /**
     * assert that getting a connection without a saved connection and with an
     * invalid connection data throws an stubDatabaseException
     *
     * @test
     * @expectedException  stubDatabaseException
     */
    public function noConnectionInvalidData()
    {
        $connectionData = new stubDatabaseConnectionData();
        $connectionData->setConnectionClassName('stdClass');
        stubDatabaseConnectionPool::addConnectionData($connectionData);
        stubDatabaseConnectionPool::getConnection();
    }

    /**
     * assert that getting a connection without a saved connection but with
     * connection data delivers the correct connection instance
     *
     * @test
     */
    public function noConnectionValidData()
    {
        $connectionData = new stubDatabaseConnectionData();
        PHPUnit_Framework_MockObject_Mock::generate('stubDatabaseConnection', array(), 'MockstubDatabaseConnection1');
        $connectionData->setConnectionClassName('MockstubDatabaseConnection1');
        stubDatabaseConnectionPool::addConnectionData($connectionData);
        $connection = stubDatabaseConnectionPool::getConnection();
        $this->assertType('MockstubDatabaseConnection1', $connection);
    }

    /**
     * assert that getting a connection with a saved connection delivers the 
     * correct connection instance
     *
     * @test
     */
    public function connection()
    {
        $connectionData = new stubDatabaseConnectionData();
        $connection     = $this->getMock('stubDatabaseConnection');
        $connection->expects($this->once())->method('getConnectionData')->will($this->returnValue($connectionData));
        stubDatabaseConnectionPool::setConnection($connection);
        $connectionTest = stubDatabaseConnectionPool::getConnection();
        $this->assertSame($connection, $connectionTest);
    }

    /**
     * assert that a getting a closed connection without connection data
     * throws an stubDatabaseException
     *
     * @test
     */
    public function closeConnection()
    {
        $connectionData = new stubDatabaseConnectionData();
        $connection     = $this->getMock('stubDatabaseConnection');
        $connection->expects($this->once())->method('getConnectionData')->will($this->returnValue($connectionData));
        $connection->expects($this->once())->method('disconnect');
        stubDatabaseConnectionPool::setConnection($connection);
        stubDatabaseConnectionPool::closeConnection();
    }
}
?>