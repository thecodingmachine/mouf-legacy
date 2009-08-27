<?php
/**
 * Test for net::stubbles::rdbms::persistence::finder::stubDatabaseFinder.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_finder_test
 * @version     $Id: stubDatabaseFinderTestCase.php 1901 2008-10-24 14:10:24Z mikey $
 */
stubClassLoader::load('net::stubbles::rdbms::persistence::finder::stubDatabaseFinder',
                      'net::stubbles::rdbms::stubDatabaseResult'
);
require_once dirname(__FILE__) . '/../../querybuilder/TeststubDatabaseQueryBuilder.php';
require_once dirname(__FILE__) . '/../MockNoTableAnnotationEntity.php';
require_once dirname(__FILE__) . '/../MockSinglePrimaryKeyEntity.php';
require_once dirname(__FILE__) . '/../MockNoEntityAnnotationEntity.php';
/**
 * Test for net::stubbles::rdbms::persistence::finder::stubDatabaseFinder.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_finder_test
 * @group       rdbms
 * @group       rdbms_persistence
 */
class stubDatabaseFinderTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubDatabaseFinder
     */
    protected $dbFinder;
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
        $this->dbFinder = stubDatabaseFinder::getInstance($this->mockConnection, true);
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
        $this->assertEquals('finder:mock', $this->dbFinder->hashCode());
    }

    /**
     * check that database finder instances are the same for the same connection
     *
     * @test
     */
    public function instancesAreTheSameForSameConnection()
    {
        $dbFinder = stubDatabaseFinder::getInstance($this->mockConnection);
        $this->assertSame($this->dbFinder, $dbFinder);
    }

    /**
     * check that database finder instances differant for the same connection if it should be refreshed
     *
     * @test
     */
    public function instancesAreDifferentForSameConnectionOnRefresh()
    {
        $dbFinder = stubDatabaseFinder::getInstance($this->mockConnection, true);
        $this->assertNotSame($this->dbFinder, $dbFinder);
    }

    /**
     * check that database finder instances are differant for for differant connections
     *
     * @test
     */
    public function instancesAreDifferantForDifferantConnections()
    {
        $mockConnection = $this->getMock('stubDatabaseConnection');
        $mockConnection->expects($this->any())->method('hashCode')->will($this->returnValue('mock2'));
        $dbFinder = stubDatabaseFinder::getInstance($mockConnection);
        $this->logicalNot(($this->identicalTo($this->dbFinder, $dbFinder)));
    }

    /**
     * test that trying to find a class that does not have an entity annotation throws an exception
     *
     * @test
     * @expectedException  stubPersistenceException
     */
    public function byPrimaryKeysNonEntity()
    {
        $this->dbFinder->findByPrimaryKeys(new stubReflectionClass('MockNoEntityAnnotationEntity'), array());
    }

    /**
     * test that finding data of an object with its primary keys
     *
     * @test
     */
    public function byPrimaryKeys()
    {
        $mockResult = $this->getMock('stubDatabaseResult');
        $this->mockConnection->expects($this->any())->method('query')->will($this->returnValue($mockResult));
        $mockResult->expects($this->exactly(2))
                   ->method('fetch')
                   ->will($this->onConsecutiveCalls(false, array('id' => 'mock', 'bar' => 'Here is bar.', 'default' => 'And this is default.')));
        $this->assertNull($this->dbFinder->findByPrimaryKeys(new stubReflectionClass('MockSinglePrimaryKeyEntity'), array('id' => 'mock')));
        $singlePrimaryKey = $this->dbFinder->findByPrimaryKeys(new stubReflectionClass('MockSinglePrimaryKeyEntity'), array('id' => 'mock'));
        $this->assertEquals('mock', $singlePrimaryKey->getId());
        $this->assertEquals('Here is bar.', $singlePrimaryKey->withAnnotation());
        $this->assertEquals('And this is default.', $singlePrimaryKey->withDefaultValue());
        $this->assertEquals('foo', $this->mockQueryBuilder->getSelect()->getBaseTableName());
    }

    /**
     * test that finding data of an object with its primary keys
     *
     * @test
     */
    public function byPrimaryKeysWithoutTableAnnotation()
    {
        $mockResult = $this->getMock('stubDatabaseResult');
        $this->mockConnection->expects($this->any())->method('query')->will($this->returnValue($mockResult));
        $mockResult->expects($this->exactly(2))
                   ->method('fetch')
                   ->will($this->onConsecutiveCalls(false, array('id' => 'mock', 'bar' => 'Here is bar.', 'defaultValue' => 'And this is default.')));
        $this->assertNull($this->dbFinder->findByPrimaryKeys(new stubReflectionClass('MockNoTableAnnotationEntity'), array('id' => 'mock')));
        $entity = $this->dbFinder->findByPrimaryKeys(new stubReflectionClass('MockNoTableAnnotationEntity'), array('id' => 'mock'));
        $this->assertEquals('mock', $entity->getId());
        $this->assertEquals('Here is bar.', $entity->withAnnotation());
        $this->assertEquals('And this is default.', $entity->getDefaultValue());
        $this->assertEquals('MockNoTableAnnotationEntitys', $this->mockQueryBuilder->getSelect()->getBaseTableName());
    }

    /**
     * test that trying to find a class that does not have an entity annotation throws an exception
     *
     * @test
     * @expectedException  stubPersistenceException
     */
    public function byCriterionNonEntity()
    {
        $this->dbFinder->findByCriterion($this->getMock('stubCriterion'), new stubReflectionClass('MockNoEntityAnnotationEntity'));
    }

    /**
     * test that finding data of an object with a criterion works as expected
     *
     * @test
     */
    public function byCriterion()
    {
        $mockCriterion = $this->getMock('stubCriterion');
        $mockCriterion->expects($this->any())->method('toSQL')->will($this->returnValue('example'));
        $mockResult = $this->getMock('stubDatabaseResult');
        $this->mockConnection->expects($this->any())->method('query')->will($this->returnValue($mockResult));
        $mockResult->expects($this->exactly(2))
                   ->method('fetchAll')
                   ->will($this->onConsecutiveCalls(false, array(array('bar' => 'Here is bar.', 'default' => 'And this is default.'))));
        $finderResult = $this->dbFinder->findByCriterion($mockCriterion, new stubReflectionClass('MockSinglePrimaryKeyEntity'));
        $this->assertEquals(0, $finderResult->count());
        $finderResult = $this->dbFinder->findByCriterion($mockCriterion, new stubReflectionClass('MockSinglePrimaryKeyEntity'));
        $this->assertEquals(1, $finderResult->count());
        $data = $finderResult->current();
        $this->assertEquals('Here is bar.', $data->withAnnotation());
        $this->assertEquals('And this is default.', $data->withDefaultValue());
        $select = $this->mockQueryBuilder->getSelect();
        $this->assertEquals('foo', $select->getBaseTableName());
        $this->assertEquals('bar ASC', $select->getOrderedBy());
        $this->assertTrue($select->hasCriterion());
    }

    /**
     * test that finding data of an object with a criterion works as expected
     *
     * @test
     */
    public function byCriterionOverruleOrderBy()
    {
        $mockCriterion = $this->getMock('stubCriterion');
        $mockCriterion->expects($this->any())->method('toSQL')->will($this->returnValue('example'));
        $mockResult = $this->getMock('stubDatabaseResult');
        $this->mockConnection->expects($this->any())->method('query')->will($this->returnValue($mockResult));
        $mockResult->expects($this->once())
                   ->method('fetchAll')
                   ->will($this->returnValue(array(array('bar' => 'Here is bar.', 'default' => 'And this is default.'))));
        $finderResult = $this->dbFinder->findByCriterion($mockCriterion, new stubReflectionClass('MockSinglePrimaryKeyEntity'), 'blub DESC');
        $this->assertEquals(1, $finderResult->count());
        $data = $finderResult->current();
        $this->assertEquals('Here is bar.', $data->withAnnotation());
        $this->assertEquals('And this is default.', $data->withDefaultValue());
        $select = $this->mockQueryBuilder->getSelect();
        $this->assertEquals('foo', $select->getBaseTableName());
        $this->assertEquals('blub DESC', $select->getOrderedBy());
        $this->assertFalse($select->hasLimit());
        $this->assertNull($select->getOffset());
        $this->assertNull($select->getAmount());
        $this->assertTrue($select->hasCriterion());
    }

    /**
     * test that finding data of an object with a criterion works as expected
     *
     * @test
     */
    public function byCriterionOverruleLimitClause()
    {
        $mockCriterion = $this->getMock('stubCriterion');
        $mockCriterion->expects($this->any())->method('toSQL')->will($this->returnValue('example'));
        $mockResult = $this->getMock('stubDatabaseResult');
        $this->mockConnection->expects($this->any())->method('query')->will($this->returnValue($mockResult));
        $mockResult->expects($this->once())
                   ->method('fetchAll')
                   ->will($this->returnValue(array(array('bar' => 'Here is bar.', 'default' => 'And this is default.'))));
        $finderResult = $this->dbFinder->findByCriterion($mockCriterion, new stubReflectionClass('MockSinglePrimaryKeyEntity'), null, 50, 10);
        $this->assertEquals(1, $finderResult->count());
        $data = $finderResult->current();
        $this->assertEquals('Here is bar.', $data->withAnnotation());
        $this->assertEquals('And this is default.', $data->withDefaultValue());
        $select = $this->mockQueryBuilder->getSelect();
        $this->assertEquals('foo', $select->getBaseTableName());
        $this->assertEquals('bar ASC', $select->getOrderedBy());
        $this->assertTrue($select->hasLimit());
        $this->assertEquals(50, $select->getOffset());
        $this->assertEquals(10, $select->getAmount());
        $this->assertTrue($select->hasCriterion());
    }

    /**
     * test that finding data for all instances of an object works as expected
     *
     * @test
     */
    public function findAll()
    {
        $mockResult = $this->getMock('stubDatabaseResult');
        $this->mockConnection->expects($this->any())->method('query')->will($this->returnValue($mockResult));
        $mockResult->expects($this->exactly(2))
                   ->method('fetchAll')
                   ->will($this->onConsecutiveCalls(false, array(array('bar' => 'Here is bar.', 'default' => 'And this is default.'))));
        $finderResult = $this->dbFinder->findAll(new stubReflectionClass('MockSinglePrimaryKeyEntity'));
        $this->assertEquals(0, $finderResult->count());
        $finderResult = $this->dbFinder->findAll(new stubReflectionClass('MockSinglePrimaryKeyEntity'));
        $this->assertEquals(1, $finderResult->count());
        $data = $finderResult->current();
        $this->assertEquals('Here is bar.', $data->withAnnotation());
        $this->assertEquals('And this is default.', $data->withDefaultValue());
        $select = $this->mockQueryBuilder->getSelect();
        $this->assertEquals('foo', $select->getBaseTableName());
        $this->assertEquals('bar ASC', $select->getOrderedBy());
        $this->assertFalse($select->hasLimit());
        $this->assertNull($select->getOffset());
        $this->assertNull($select->getAmount());
        $this->assertFalse($select->hasCriterion());
    }

    /**
     * test that finding data for all instances of an object works as expected
     *
     * @test
     */
    public function findAllOverruleOrderBy()
    {
        $mockResult = $this->getMock('stubDatabaseResult');
        $this->mockConnection->expects($this->any())->method('query')->will($this->returnValue($mockResult));
        $mockResult->expects($this->once())
                   ->method('fetchAll')
                   ->will($this->returnValue(array(array('bar' => 'Here is bar.', 'default' => 'And this is default.'))));
        $finderResult = $this->dbFinder->findAll(new stubReflectionClass('MockSinglePrimaryKeyEntity'), 'blub DESC');
        $this->assertEquals(1, $finderResult->count());
        $data = $finderResult->current();
        $this->assertEquals('Here is bar.', $data->withAnnotation());
        $this->assertEquals('And this is default.', $data->withDefaultValue());
        $select = $this->mockQueryBuilder->getSelect();
        $this->assertEquals('foo', $select->getBaseTableName());
        $this->assertEquals('blub DESC', $select->getOrderedBy());
        $this->assertFalse($select->hasLimit());
        $this->assertNull($select->getOffset());
        $this->assertNull($select->getAmount());
        $this->assertFalse($select->hasCriterion());
    }

    /**
     * test that finding data for all instances of an object works as expected
     *
     * @test
     */
    public function findAllOverruleLimitClause()
    {
        $mockResult = $this->getMock('stubDatabaseResult');
        $this->mockConnection->expects($this->any())->method('query')->will($this->returnValue($mockResult));
        $mockResult->expects($this->once())
                   ->method('fetchAll')
                   ->will($this->returnValue(array(array('bar' => 'Here is bar.', 'default' => 'And this is default.'))));
        $finderResult = $this->dbFinder->findAll(new stubReflectionClass('MockSinglePrimaryKeyEntity'), null, 50, 10);
        $this->assertEquals(1, $finderResult->count());
        $data = $finderResult->current();
        $this->assertEquals('Here is bar.', $data->withAnnotation());
        $this->assertEquals('And this is default.', $data->withDefaultValue());
        $select = $this->mockQueryBuilder->getSelect();
        $this->assertEquals('foo', $select->getBaseTableName());
        $this->assertEquals('bar ASC', $select->getOrderedBy());
        $this->assertTrue($select->hasLimit());
        $this->assertEquals(50, $select->getOffset());
        $this->assertEquals(10, $select->getAmount());
        $this->assertFalse($select->hasCriterion());
    }
}
?>