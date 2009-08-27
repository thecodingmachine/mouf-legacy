<?php
/**
 * Test for net::stubbles::rdbms::querybuilder::stubDatabaseTableDescription.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_querybuilder_test
 */
stubClassLoader::load('net::stubbles::rdbms::querybuilder::stubDatabaseTableDescription');
/**
 * Test for net::stubbles::rdbms::querybuilder::stubDatabaseTableDescription.
 *
 * @package     stubbles
 * @subpackage  rdbms_querybuilder_test
 * @group       rdbms
 * @group       rdbms_querybuilder
 */
class stubDatabaseTableDescriptionTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubDatabaseTableDescription
     */
    protected $tableDescription;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->tableDescription = new stubDatabaseTableDescription();
    }

    /**
     * check that setting and getting the name works as expected
     *
     * @test
     */
    public function name()
    {
        $this->assertEquals('', $this->tableDescription->getName());
        $this->tableDescription->setName('foo');
        $this->assertEquals('foo', $this->tableDescription->getName());
    }

    /**
     * check that setting and getting the type works as expected
     *
     * @test
     */
    public function type()
    {
        $this->assertNull($this->tableDescription->getType());
        $this->assertFalse($this->tableDescription->hasType());
        $this->tableDescription->setType('foo');
        $this->assertEquals('foo', $this->tableDescription->getType());
        $this->assertTrue($this->tableDescription->hasType());
    }

    /**
     * check that setting and getting the character set works as expected
     *
     * @test
     */
    public function characterSet()
    {
        $this->assertNull($this->tableDescription->getCharacterSet());
        $this->assertFalse($this->tableDescription->hasCharacterSet());
        $this->tableDescription->setCharacterSet('utf-8');
        $this->assertEquals('utf-8', $this->tableDescription->getCharacterSet());
        $this->assertTrue($this->tableDescription->hasCharacterSet());
    }

    /**
     * check that setting and getting the collation works as expected
     *
     * @test
     */
    public function collation()
    {
        $this->assertNull($this->tableDescription->getCollation());
        $this->assertFalse($this->tableDescription->hasCollation());
        $this->tableDescription->setCollation('utf-8');
        $this->assertEquals('utf-8', $this->tableDescription->getCollation());
        $this->assertTrue($this->tableDescription->hasCollation());
    }

    /**
     * check that setting and getting the comment works as expected
     *
     * @test
     */
    public function comment()
    {
        $this->assertNull($this->tableDescription->getComment());
        $this->tableDescription->setComment('foo');
        $this->assertEquals('foo', $this->tableDescription->getComment());
    }

    /**
     * check that adding table columns works as expected
     *
     * @test
     */
    public function addColumnsWithoutOwnOrder()
    {
        $column1 = new stubDatabaseTableColumn();
        $column1->setName('foo');
        $this->tableDescription->addColumn($column1);
        $column2 = new stubDatabaseTableColumn();
        $column2->setName('bar');
        $this->tableDescription->addColumn($column2);
        $column3 = new stubDatabaseTableColumn();
        $column3->setName('baz');
        $this->tableDescription->addColumn($column3);
        $this->assertEquals(array(1 => $column1,
                                  2 => $column2,
                                  3 => $column3
                            ),
                            $this->tableDescription->getColumns()
        );
    }

    /**
     * check that adding table columns works as expected
     *
     * @test
     */
    public function addColumnsWithOwnOrder()
    {
        $column1 = new stubDatabaseTableColumn();
        $column1->setName('foo');
        $column1->setOrder(2);
        $this->tableDescription->addColumn($column1);
        $column2 = new stubDatabaseTableColumn();
        $column2->setName('bar');
        $column2->setOrder(4);
        $this->tableDescription->addColumn($column2);
        $column3 = new stubDatabaseTableColumn();
        $column3->setName('baz');
        $column3->setOrder(1);
        $this->tableDescription->addColumn($column3);
        $this->assertEquals(array(1 => $column3,
                                  2 => $column1,
                                  4 => $column2,
                            ),
                            $this->tableDescription->getColumns()
        );
    }

    /**
     * check that adding table columns works as expected
     *
     * @test
     * @expectedException  stubDatabaseException
     */
    public function addColumnsWithOwnOrderWithTwoColumnsOfSameOrder()
    {
        $column1 = new stubDatabaseTableColumn();
        $column1->setName('foo');
        $column1->setOrder(2);
        $this->tableDescription->addColumn($column1);
        $column2 = new stubDatabaseTableColumn();
        $column2->setName('bar');
        $column2->setOrder(4);
        $this->tableDescription->addColumn($column2);
        $column3 = new stubDatabaseTableColumn();
        $column3->setName('baz');
        $column3->setOrder(2);
        $this->tableDescription->addColumn($column3);
    }

    /**
     * check that adding table columns works as expected
     *
     * @test
     * @expectedException  stubDatabaseException
     */
    public function addColumnsWithAndWithoutOwnOrderWithTwoColumnsOfSameOrder()
    {
        $column1 = new stubDatabaseTableColumn();
        $column1->setName('foo');
        $this->tableDescription->addColumn($column1);
        $column2 = new stubDatabaseTableColumn();
        $column2->setName('bar');
        $this->tableDescription->addColumn($column2);
        $column3 = new stubDatabaseTableColumn();
        $column3->setName('baz');
        $column3->setOrder(2);
        $this->tableDescription->addColumn($column3);
    }

    /**
     * check that adding table columns works as expected
     *
     * @test
     * @expectedException  stubDatabaseException
     */
    public function adColumnsWithTwoColumnsOfSameName()
    {
        $column1 = new stubDatabaseTableColumn();
        $column1->setName('foo');
        $this->tableDescription->addColumn($column1);
        $column2 = new stubDatabaseTableColumn();
        $column2->setName('bar');
        $this->tableDescription->addColumn($column2);
        $column3 = new stubDatabaseTableColumn();
        $column3->setName('foo');
        $this->tableDescription->addColumn($column3);
    }
}
?>