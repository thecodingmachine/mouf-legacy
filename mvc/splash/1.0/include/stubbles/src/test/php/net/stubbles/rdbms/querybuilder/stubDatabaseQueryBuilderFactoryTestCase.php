<?php
/**
 * Test for net::stubbles::rdbms::querybuilder::stubDatabaseQueryBuilderFactory.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_querybuilder_test
 */
stubClassLoader::load('net::stubbles::rdbms::querybuilder::stubDatabaseQueryBuilderFactory');
/**
 * Test for net::stubbles::rdbms::querybuilder::stubDatabaseQueryBuilderFactory.
 *
 * @package     stubbles
 * @subpackage  rdbms_querybuilder_test
 * @group       rdbms
 * @group       rdbms_querybuilder
 */
class stubDatabaseQueryBuilderFactoryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * a mocked database connection
     *
     * @var  SimpleMock
     */
    protected $mockConnection;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockConnection = $this->getMock('stubDatabaseConnection');
        $this->mockConnection->expects($this->any())->method('getDatabase')->will($this->returnValue('mock'));
    }

    /**
     * test that the factory is correctly initialized
     *
     * @test
     */
    public function defaultValues()
    {
        $this->assertTrue(stubDatabaseQueryBuilderFactory::isQueryBuilderAvailable('MySQL'));
        $this->assertFalse(stubDatabaseQueryBuilderFactory::isQueryBuilderInstantiated('MySQL'));
    }

    /**
     * test that the factory throws an exception if a query builder for an unknown database type should be created
     *
     * @test
     * @expectedException  stubDatabaseQueryBuilderException
     */
    public function createWithNonExistingQueryBuilder()
    {
        stubDatabaseQueryBuilderFactory::create($this->mockConnection);
    }

    /**
     * test that the factory throws an exception if a query builder does not implement the correct interface
     *
     * @test
     * @expectedException  stubDatabaseQueryBuilderException
     */
    public function createWrongClass()
    {
        stubDatabaseQueryBuilderFactory::setAvailableQueryBuilder('mock', 'stdClass');
        stubDatabaseQueryBuilderFactory::create($this->mockConnection);
    }

    /**
     * test that the factory with an available querybuilder
     *
     * @test
     */
    public function withAvailableQueryBuilder()
    {
        stubDatabaseQueryBuilderFactory::setAvailableQueryBuilder('mock', get_class($this->getMock('stubDatabaseQueryBuilder')));
        $this->assertTrue(stubDatabaseQueryBuilderFactory::isQueryBuilderAvailable('mock'));
        $this->assertFalse(stubDatabaseQueryBuilderFactory::isQueryBuilderInstantiated('mock'));
        $mockQueryBuilder = stubDatabaseQueryBuilderFactory::create($this->mockConnection);
        $this->assertTrue(stubDatabaseQueryBuilderFactory::isQueryBuilderAvailable('mock'));
        $this->assertTrue(stubDatabaseQueryBuilderFactory::isQueryBuilderInstantiated('mock'));
        $testQueryBuilder = stubDatabaseQueryBuilderFactory::create($this->mockConnection);
        $this->assertSame($mockQueryBuilder, $testQueryBuilder);
        
        stubDatabaseQueryBuilderFactory::removeAvailableQueryBuilder('mock');
        $this->assertFalse(stubDatabaseQueryBuilderFactory::isQueryBuilderAvailable('mock'));
        $this->assertTrue(stubDatabaseQueryBuilderFactory::isQueryBuilderInstantiated('mock'));
        
        stubDatabaseQueryBuilderFactory::removeInstantiatedQueryBuilder('mock');
        $this->assertFalse(stubDatabaseQueryBuilderFactory::isQueryBuilderAvailable('mock'));
        $this->assertFalse(stubDatabaseQueryBuilderFactory::isQueryBuilderInstantiated('mock'));
    }

    /**
     * test that the factory with an instantiated querybuilder
     *
     * @test
     */
    public function withInstantiatedQueryBuilder()
    {
        $mockQueryBuilder = $this->getMock('stubDatabaseQueryBuilder');
        $mockQueryBuilder->expects($this->any())->method('getClassName')->will($this->returnValue(get_class($mockQueryBuilder)));
        stubDatabaseQueryBuilderFactory::setInstantiatedQueryBuilder('mock', $mockQueryBuilder);
        $this->assertTrue(stubDatabaseQueryBuilderFactory::isQueryBuilderAvailable('mock'));
        $this->assertTrue(stubDatabaseQueryBuilderFactory::isQueryBuilderInstantiated('mock'));
        $testQueryBuilder = stubDatabaseQueryBuilderFactory::create($this->mockConnection);
        $this->assertSame($mockQueryBuilder, $testQueryBuilder);
        
        stubDatabaseQueryBuilderFactory::removeInstantiatedQueryBuilder('mock');
        $this->assertFalse(stubDatabaseQueryBuilderFactory::isQueryBuilderAvailable('mock'));
        $this->assertFalse(stubDatabaseQueryBuilderFactory::isQueryBuilderInstantiated('mock'));
    }
}
?>