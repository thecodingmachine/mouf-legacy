<?php
/**
 * Test for net::stubbles::rdbms::persistence::serializer::stubDatabaseSerializer.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_serializer_test
 */
stubClassLoader::load('net::stubbles::rdbms::persistence::serializer::stubDatabaseSerializer');
require_once dirname(__FILE__) . '/../../querybuilder/TeststubDatabaseQueryBuilder.php';
require_once dirname(__FILE__) . '/../MockNoEntityAnnotationEntity.php';
require_once dirname(__FILE__) . '/../MockSinglePrimaryKeyEntity.php';
/**
 * Test for net::stubbles::rdbms::persistence::serializer::stubDatabaseSerializer.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_serializer_test
 * @group       rdbms
 * @group       rdbms_persistence
 */
class stubDatabaseSerializerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubDatabaseSerializer
     */
    protected $dbSerializer;
    /**
     * mock for pdo
     *
     * @var  SimpleMock
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
        $this->dbSerializer = stubDatabaseSerializer::getInstance($this->mockConnection, true);
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
        $this->assertEquals('serializer:mock', $this->dbSerializer->hashCode());
    }

    /**
     * check that database serializer instances are the same for the same connection
     *
     * @test
     */
    public function instancesAreTheSameForSameConnection()
    {
        $dbSerializer = stubDatabaseSerializer::getInstance($this->mockConnection);
        $this->assertSame($this->dbSerializer, $dbSerializer);
    }

    /**
     * check that database serializer instances differant for the same connection if it should be refreshed
     *
     * @test
     */
    public function instancesAreDifferentForSameConnectionOnRefresh()
    {
        $dbSerializer = stubDatabaseSerializer::getInstance($this->mockConnection, true);
        $this->assertNotSame($this->dbSerializer, $dbSerializer);
    }

    /**
     * check that database serializer instances are differant for for differant connections
     *
     * @test
     */
    public function instancesAreDifferantForDifferantConnections()
    {
        $mockConnection = $this->getMock('stubDatabaseConnection');
        $mockConnection->expects($this->any())->method('hashCode')->will($this->returnValue('mock2'));
        $dbSerializer = stubDatabaseSerializer::getInstance($mockConnection);
        $this->logicalNot(($this->identicalTo($this->dbSerializer, $dbSerializer)));
    }

    /**
     * check that a non-object throws an exception
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function insertNonObject()
    {
        $this->dbSerializer->insert('foo');
    }

    /**
     * test that trying to find a class that does not have an entity annotation throws an exception
     *
     * @test
     * @expectedException  stubPersistenceException
     */
    public function insertNonEntity()
    {
        $this->dbSerializer->insert(new MockNoEntityAnnotationEntity());
    }

    /**
     * check that a non-object throws an exception
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function updateNonObject()
    {
        $this->dbSerializer->update('foo');
    }

    /**
     * test that trying to find a class that does not have an entity annotation throws an exception
     *
     * @test
     * @expectedException  stubPersistenceException
     */
    public function updateNonEntity()
    {
        $this->dbSerializer->update(new MockNoEntityAnnotationEntity());
    }

    /**
     * check that a non-object throws an exception
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function serializeNonObject()
    {
        $this->dbSerializer->serialize('foo');
    }

    /**
     * test that trying to find a class that does not have an entity annotation throws an exception
     *
     * @test
     * @expectedException  stubPersistenceException
     */
    public function serializeNonEntity()
    {
        $this->dbSerializer->serialize(new MockNoEntityAnnotationEntity());
    }

    /**
     * test insert with a single primary key
     *
     * @test
     */
    public function insertWithSinglePrimaryKey()
    {
        $singlePrimaryKeyEntity = new MockSinglePrimaryKeyEntity();
        $this->mockConnection->expects($this->any())->method('getLastInsertId')->will($this->returnValue('mockId'));
        $this->mockConnection->expects($this->once())->method('exec');
        $this->mockQueryBuilder->setInsertQueries(array('foo' => 'foo'));
        $this->assertEquals(stubDatabaseSerializer::INSERT, $this->dbSerializer->insert($singlePrimaryKeyEntity));
        $this->assertEquals(1, $this->mockQueryBuilder->getCallCount('createInsert'));
        $this->assertEquals(0, $this->mockQueryBuilder->getCallCount('createUpdate'));
        $tableRows = $this->mockQueryBuilder->getInsertTableRows();
        $this->assertEquals(1, count($tableRows));
        $this->assertTrue(isset($tableRows['foo']));
        $this->assertEquals('mockId', $singlePrimaryKeyEntity->getId());
        $this->assertEquals(array('bar' => 'this is bar', 'default' => 'example', 'date' => null), $tableRows['foo']->getColumns());
        $this->assertFalse($tableRows['foo']->hasCriterion());
    }

    /**
     * test insert with a single primary key
     *
     * @test
     */
    public function insertWithSinglePrimaryKeyAlreadySet()
    {
        $singlePrimaryKeyEntity = new MockSinglePrimaryKeyEntity();
        $singlePrimaryKeyEntity->setId('mockId');
        $this->mockConnection->expects($this->never())->method('getLastInsertId');
        $this->mockConnection->expects($this->once())->method('exec');
        $this->mockQueryBuilder->setInsertQueries(array('foo' => 'foo'));
        $this->assertEquals(stubDatabaseSerializer::INSERT, $this->dbSerializer->insert($singlePrimaryKeyEntity));
        $this->assertEquals(1, $this->mockQueryBuilder->getCallCount('createInsert'));
        $this->assertEquals(0, $this->mockQueryBuilder->getCallCount('createUpdate'));
        $tableRows = $this->mockQueryBuilder->getInsertTableRows();
        $this->assertEquals(1, count($tableRows));
        $this->assertTrue(isset($tableRows['foo']));
        $this->assertEquals('mockId', $singlePrimaryKeyEntity->getId());
        $this->assertEquals(array('id' => 'mockId', 'bar' => 'this is bar', 'default' => 'example', 'date' => null), $tableRows['foo']->getColumns());
        $this->assertFalse($tableRows['foo']->hasCriterion());
    }

    /**
     * test update with a single primary key
     *
     * @test
     */
    public function updateWithSinglePrimaryKey()
    {
        $singlePrimaryKeyEntity = new MockSinglePrimaryKeyEntity();
        $singlePrimaryKeyEntity->setId('mockId');
        $singlePrimaryKeyEntity->setDefaultValue('anotherExample');
        $singlePrimaryKeyEntity->setDate(new stubDate('2008-10-23 19:27:22'));
        $this->mockConnection->expects($this->never())->method('getLastInsertId');
        $this->mockConnection->expects($this->once())->method('exec');
        $this->mockQueryBuilder->setUpdateQueries(array('foo' => 'foo'));
        $this->assertEquals(stubDatabaseSerializer::UPDATE, $this->dbSerializer->update($singlePrimaryKeyEntity));
        $this->assertEquals(0, $this->mockQueryBuilder->getCallCount('createInsert'));
        $this->assertEquals(1, $this->mockQueryBuilder->getCallCount('createUpdate'));
        $tableRows = $this->mockQueryBuilder->getUpdateTableRows();
        $this->assertEquals(1, count($tableRows));
        $this->assertTrue(isset($tableRows['foo']));
        $this->assertEquals('mockId', $singlePrimaryKeyEntity->getId());
        $this->assertEquals(array('bar' => 'this is bar', 'default' => 'anotherExample', 'date' => '2008-10-23 19:27:22'), $tableRows['foo']->getColumns());
        $this->assertTrue($tableRows['foo']->hasCriterion());
        $this->assertEquals("(`foo`.`id` = 'mockId')", $tableRows['foo']->getCriterion()->toSQL());
    }

    /**
     * test insert with a single primary key
     *
     * @test
     */
    public function serializeInsertWithSinglePrimaryKey()
    {
        $singlePrimaryKeyEntity = new MockSinglePrimaryKeyEntity();
        $this->mockConnection->expects($this->any())->method('getLastInsertId')->will($this->returnValue('mockId'));
        $this->mockConnection->expects($this->once())->method('exec');
        $this->mockQueryBuilder->setInsertQueries(array('foo' => 'foo'));
        $this->assertEquals(stubDatabaseSerializer::INSERT, $this->dbSerializer->serialize($singlePrimaryKeyEntity));
        $this->assertEquals(1, $this->mockQueryBuilder->getCallCount('createInsert'));
        $this->assertEquals(0, $this->mockQueryBuilder->getCallCount('createUpdate'));
        $tableRows = $this->mockQueryBuilder->getInsertTableRows();
        $this->assertEquals(1, count($tableRows));
        $this->assertTrue(isset($tableRows['foo']));
        $this->assertEquals('mockId', $singlePrimaryKeyEntity->getId());
        $this->assertEquals(array('bar' => 'this is bar', 'default' => 'example', 'date' => null), $tableRows['foo']->getColumns());
        $this->assertFalse($tableRows['foo']->hasCriterion());
    }

    /**
     * test update with a single primary key
     *
     * @test
     */
    public function serializeUpdateWithSinglePrimaryKey()
    {
        $singlePrimaryKeyEntity = new MockSinglePrimaryKeyEntity();
        $singlePrimaryKeyEntity->setId('mockId');
        $singlePrimaryKeyEntity->setDefaultValue('anotherExample');
        $singlePrimaryKeyEntity->setDate(new stubDate('2008-10-23 19:27:22'));
        $this->mockConnection->expects($this->never())->method('getLastInsertId');
        $this->mockConnection->expects($this->once())->method('exec');
        $this->mockQueryBuilder->setUpdateQueries(array('foo' => 'foo'));
        $this->assertEquals(stubDatabaseSerializer::UPDATE, $this->dbSerializer->serialize($singlePrimaryKeyEntity));
        $this->assertEquals(0, $this->mockQueryBuilder->getCallCount('createInsert'));
        $this->assertEquals(1, $this->mockQueryBuilder->getCallCount('createUpdate'));
        $tableRows = $this->mockQueryBuilder->getUpdateTableRows();
        $this->assertEquals(1, count($tableRows));
        $this->assertTrue(isset($tableRows['foo']));
        $this->assertEquals('mockId', $singlePrimaryKeyEntity->getId());
        $this->assertEquals(array('bar' => 'this is bar', 'default' => 'anotherExample', 'date' => '2008-10-23 19:27:22'), $tableRows['foo']->getColumns());
        $this->assertTrue($tableRows['foo']->hasCriterion());
        $this->assertEquals("(`foo`.`id` = 'mockId')", $tableRows['foo']->getCriterion()->toSQL());
    }
}
?>