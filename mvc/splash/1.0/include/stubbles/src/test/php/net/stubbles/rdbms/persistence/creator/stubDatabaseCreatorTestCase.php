<?php
/**
 * Test for net::stubbles::rdbms::persistence::creator::stubDatabaseCreator
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_creator_test
 */
stubClassLoader::load('net::stubbles::rdbms::persistence::creator::stubDatabaseCreator');
require_once dirname(__FILE__) . '/../../querybuilder/TeststubDatabaseQueryBuilder.php';
require_once dirname(__FILE__) . '/../MockNoEntityAnnotationEntity.php';
require_once dirname(__FILE__) . '/../MockNoTableAnnotationEntity.php';
require_once dirname(__FILE__) . '/../MockSinglePrimaryKeyEntity.php';
/**
 * Test for net::stubbles::rdbms::persistence::creator::stubDatabaseCreator
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_creator_test
 * @group       rdbms
 * @group       rdbms_persistence
 */
class stubDatabaseCreatorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubDatabaseCreator
     */
    protected $dbCreator;
    /**
     * mock for pdo
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockConnection;
    /**
     * a test query builder
     *
     * @var  TeststubDatabaseQueryBuilder
     */
    protected $mockQueryBuilder;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockConnection = $this->getMock('stubDatabaseConnection');
        $this->mockConnection->expects($this->any())->method('hashCode')->will($this->returnValue('mock'));
        $this->mockConnection->expects($this->any())->method('getDatabase')->will($this->returnValue('mock'));
        $this->mockQueryBuilder = new TeststubDatabaseQueryBuilder();
        stubDatabaseQueryBuilderFactory::setInstantiatedQueryBuilder('mock', $this->mockQueryBuilder);
        $this->dbCreator = stubDatabaseCreator::getInstance($this->mockConnection);
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        stubDatabaseQueryBuilderFactory::removeInstantiatedQueryBuilder('mock');
    }

    /**
     * test the hash code
     *
     * @test
     */
    public function hashCode()
    {
        $this->assertEquals('creator:mock', $this->dbCreator->hashCode());
    }

    /**
     * check that database creator instances are the same for the same connection
     *
     * @test
     */
    public function instancesAreTheSameForSameConnection()
    {
        $dbCreator = stubDatabaseCreator::getInstance($this->mockConnection);
        $this->assertSame($this->dbCreator, $dbCreator);
    }

    /**
     * check that database creator instances differant for the same connection if it should be refreshed
     *
     * @test
     */
    public function instancesAreDifferentForSameConnectionOnRefresh()
    {
        $dbCreator = stubDatabaseCreator::getInstance($this->mockConnection, true);
        $this->assertNotSame($this->dbCreator, $dbCreator);
    }

    /**
     * check that database creator instances are differant for for differant connections
     *
     * @test
     */
    public function instancesAreDifferantForDifferantConnections()
    {
        $mockConnection = $this->getMock('stubDatabaseConnection');
        $mockConnection->expects($this->any())->method('hashCode')->will($this->returnValue('mock2'));
        $dbCreator = stubDatabaseCreator::getInstance($mockConnection);
        $this->logicalNot(($this->identicalTo($this->dbCreator, $dbCreator)));
    }

    /**
     * check that a class that is missing the DBTable annotation throws an exception
     *
     * @test
     * @expectedException  stubPersistenceException
     */
    public function classWithoutEntityAnnotation()
    {
        $this->dbCreator->createTable(new stubReflectionClass('MockNoEntityAnnotationEntity'));
    }

    /**
     * check that a class that is missing the DBTable annotation throws an exception
     *
     * @test
     */
    public function classWithoutDBTableAnnotation()
    {
        $this->dbCreator->createTable(new stubReflectionClass('MockNoTableAnnotationEntity'));
        $tableDescription = $this->mockQueryBuilder->getTableDescription();
        $this->assertEquals($tableDescription->getName(), 'MockNoTableAnnotationEntitys');
        $columns = $tableDescription->getColumns();
        $this->assertEquals(6, count($columns));
        $this->assertEquals('id', $columns[1]->getName());
        $this->assertEquals('INT', $columns[1]->getType());
        $this->assertEquals(10, $columns[1]->getSize());
        $this->assertTrue($columns[1]->isPrimaryKey());
        $this->assertEquals('bar', $columns[2]->getName());
        $this->assertEquals('VARCHAR', $columns[2]->getType());
        $this->assertEquals(10, $columns[2]->getSize());
        $this->assertFalse($columns[2]->isPrimaryKey());
        $this->assertEquals('defaultValue', $columns[3]->getName());
        $this->assertEquals('VARCHAR', $columns[3]->getType());
        $this->assertEquals(255, $columns[3]->getSize());
        $this->assertFalse($columns[3]->isPrimaryKey());
        $this->assertEquals('intValue', $columns[4]->getName());
        $this->assertEquals('INT', $columns[4]->getType());
        $this->assertEquals(10, $columns[4]->getSize());
        $this->assertFalse($columns[4]->isPrimaryKey());
        $this->assertEquals('boolValue', $columns[5]->getName());
        $this->assertEquals('TINYINT', $columns[5]->getType());
        $this->assertEquals(1, $columns[5]->getSize());
        $this->assertFalse($columns[5]->isPrimaryKey());
        $this->assertEquals('floatValue', $columns[6]->getName());
        $this->assertEquals('FLOAT', $columns[6]->getType());
        $this->assertEquals(10, $columns[6]->getSize());
        $this->assertFalse($columns[6]->isPrimaryKey());
    }

    /**
     * check that a class that is missing the DBTable annotation throws an exception
     *
     * @test
     */
    public function classWithDBTableAnnotation()
    {
        $this->dbCreator->createTable(new stubReflectionClass('MockSinglePrimaryKeyEntity'));
        $tableDescription = $this->mockQueryBuilder->getTableDescription();
        $this->assertEquals($tableDescription->getName(), 'foo');
        $columns = $tableDescription->getColumns();
        $this->assertEquals(4, count($columns));
        $this->assertEquals('id', $columns[1]->getName());
        $this->assertTrue($columns[1]->isPrimaryKey());
        $this->assertEquals('bar', $columns[2]->getName());
        $this->assertFalse($columns[2]->isPrimaryKey());
        $this->assertEquals('default', $columns[3]->getName());
        $this->assertFalse($columns[3]->isPrimaryKey());
        $this->assertEquals('date', $columns[4]->getName());
        $this->assertEquals('DATETIME', $columns[4]->getType());
        $this->assertFalse($columns[3]->isPrimaryKey());
    }
}
?>