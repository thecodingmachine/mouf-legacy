<?php
/**
 * Tests for net::stubbles::rdbms::stubDatabaseXJConfInitializer.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_test
 */
stubClassLoader::load('net::stubbles::rdbms::stubDatabaseXJConfInitializer');
/**
 * Tests for net::stubbles::rdbms::stubDatabaseXJConfInitializer.
 *
 * @package     stubbles
 * @subpackage  rdbms_test
 * @group       rdbms
 */
class stubDatabaseXJConfInitializerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubDatabaseXJConfInitializer
     */
    protected $dbXJConfInitializer;
    /**
     * a connection data instance to serialize
     *
     * @var  stubDatabaseConnectionData
     */
    protected $connectionData;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->dbXJConfInitializer = new stubDatabaseXJConfInitializer();
        $this->connectionData      = new stubDatabaseConnectionData();
        $this->connectionData->setDSN('dsn');
        $this->connectionData->setUserName('foo');
        $this->connectionData->setPassword('bar');
        stubDatabaseConnectionPool::addConnectionData($this->connectionData);
    }

    /**
     * clear up test environment
     */
    public function tearDown()
    {
        foreach (stubDatabaseConnectionPool::getConnectionDataIds() as $connectionDataId) {
            stubDatabaseConnectionPool::removeConnectionData($connectionDataId);
        }
    }

    /**
     * test that the descriptor is correct
     *
     * @test
     */
    public function descriptor()
    {
        $this->assertEquals('rdbms', $this->dbXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_CONFIG));
        $this->assertEquals('rdbms', $this->dbXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_DEFINITION));
        $this->dbXJConfInitializer->setDescriptor('foo');
        $this->assertEquals('foo', $this->dbXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_CONFIG));
        $this->assertEquals('rdbms', $this->dbXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_DEFINITION));
        $this->assertEquals('rdbms', $this->dbXJConfInitializer->getDescriptor('invalid'));
    }

    /**
     * assure that values are returned correct
     *
     * @test
     */
    public function getCacheData()
    {
        $cacheData = $this->dbXJConfInitializer->getCacheData();
        $this->assertTrue(isset($cacheData[stubDatabaseConnectionData::DEFAULT_ID]));
        $this->assertType('stubSerializedObject', $cacheData[stubDatabaseConnectionData::DEFAULT_ID]);
        $this->assertSame($this->connectionData, $cacheData[stubDatabaseConnectionData::DEFAULT_ID]->getUnserialized());
    }

    /**
     * assure that values are set correct
     *
     * @test
     */
    public function setCacheData()
    {
        $this->dbXJConfInitializer->setCacheData(array(stubDatabaseConnectionData::DEFAULT_ID => $this->connectionData->getSerialized()));
        $this->assertTrue(stubDatabaseConnectionPool::hasConnectionData(stubDatabaseConnectionData::DEFAULT_ID));
        $connectionData = stubDatabaseConnectionPool::hasConnectionData(stubDatabaseConnectionData::DEFAULT_ID);
        $this->assertEquals('dsn', $this->connectionData->getDSN());
    }
}
?>