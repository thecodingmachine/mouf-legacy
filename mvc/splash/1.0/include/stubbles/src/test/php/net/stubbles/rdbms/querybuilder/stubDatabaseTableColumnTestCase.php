<?php
/**
 * Test for net::stubbles::rdbms::querybuilder::stubDatabaseTableColumn.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_querybuilder_test
 * @version     $Id: stubDatabaseTableColumnTestCase.php 1897 2008-10-24 10:22:57Z mikey $
 */
stubClassLoader::load('net::stubbles::rdbms::querybuilder::stubDatabaseTableColumn');
/**
 * Test for net::stubbles::rdbms::querybuilder::stubDatabaseTableColumn.
 *
 * @package     stubbles
 * @subpackage  rdbms_querybuilder_test
 * @group       rdbms
 * @group       rdbms_querybuilder
 */
class stubDatabaseTableColumnTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubDatabaseTableColumn
     */
    protected $tableColumn;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->tableColumn = new stubDatabaseTableColumn();
    }

    /**
     * check that setting and getting the order works as expected
     *
     * @test
     */
    public function order()
    {
        $this->assertEquals(0, $this->tableColumn->getOrder());
        $this->tableColumn->setOrder(5);
        $this->assertEquals(5, $this->tableColumn->getOrder());
    }

    /**
     * check that setting and getting the name works as expected
     *
     * @test
     */
    public function name()
    {
        $this->assertEquals('', $this->tableColumn->getName());
        $this->tableColumn->setName('foo');
        $this->assertEquals('foo', $this->tableColumn->getName());
    }

    /**
     * check that setting and getting the type works as expected
     *
     * @test
     */
    public function type()
    {
        $this->assertEquals('', $this->tableColumn->getType());
        $this->tableColumn->setType('foo');
        $this->assertEquals('foo', $this->tableColumn->getType());
    }

    /**
     * check that setting and getting the size works as expected
     *
     * @test
     */
    public function size()
    {
        $this->assertNull($this->tableColumn->getSize());
        $this->tableColumn->setSize("'1','2','3'");
        $this->assertEquals("'1','2','3'", $this->tableColumn->getSize());
        $this->tableColumn->setSize(6);
        $this->assertEquals(6, $this->tableColumn->getSize());
    }

    /**
     * check that setting and getting the character set works as expected
     *
     * @test
     */
    public function characterSet()
    {
        $this->assertNull($this->tableColumn->getCharacterSet());
        $this->assertFalse($this->tableColumn->hasCharacterSet());
        $this->tableColumn->setCharacterSet('utf-8');
        $this->assertEquals('utf-8', $this->tableColumn->getCharacterSet());
        $this->assertTrue($this->tableColumn->hasCharacterSet());
    }

    /**
     * check that setting and getting the collation works as expected
     *
     * @test
     */
    public function collation()
    {
        $this->assertNull($this->tableColumn->getCollation());
        $this->assertFalse($this->tableColumn->hasCollation());
        $this->tableColumn->setCollation('utf-8');
        $this->assertEquals('utf-8', $this->tableColumn->getCollation());
        $this->assertTrue($this->tableColumn->hasCollation());
    }

    /**
     * check that setting and getting the isUnsigned property works as expected
     *
     * @test
     */
    public function isUnsigned()
    {
        $this->assertFalse($this->tableColumn->isUnsigned());
        $this->tableColumn->setIsUnsigned(true);
        $this->assertFalse($this->tableColumn->isUnsigned());
        $this->tableColumn->setType('smallint');
        $this->assertTrue($this->tableColumn->isUnsigned());
        $this->tableColumn->setType('mediumint');
        $this->assertTrue($this->tableColumn->isUnsigned());
        $this->tableColumn->setType('int');
        $this->assertTrue($this->tableColumn->isUnsigned());
        $this->tableColumn->setType('bigint');
        $this->assertTrue($this->tableColumn->isUnsigned());
        $this->tableColumn->setType('text');
        $this->assertFalse($this->tableColumn->isUnsigned());
    }

    /**
     * check that setting and getting the hasZerofill property works as expected
     *
     * @test
     */
    public function hasZerofill()
    {
        $this->assertFalse($this->tableColumn->hasZerofill());
        $this->tableColumn->setHasZerofill(true);
        $this->assertFalse($this->tableColumn->hasZerofill());
        $this->tableColumn->setType('smallint');
        $this->assertTrue($this->tableColumn->hasZerofill());
        $this->tableColumn->setType('mediumint');
        $this->assertTrue($this->tableColumn->hasZerofill());
        $this->tableColumn->setType('int');
        $this->assertTrue($this->tableColumn->hasZerofill());
        $this->tableColumn->setType('bigint');
        $this->assertTrue($this->tableColumn->hasZerofill());
        $this->tableColumn->setType('text');
        $this->assertFalse($this->tableColumn->hasZerofill());
    }

    /**
     * check that setting and getting the isNullable property works as expected
     *
     * @test
     */
    public function isNullable()
    {
        $this->assertTrue($this->tableColumn->isNullable());
        $this->tableColumn->setIsNullable(false);
        $this->assertFalse($this->tableColumn->isNullable());
        $this->tableColumn->setIsNullable(true);
        $this->assertTrue($this->tableColumn->isNullable());
        $this->tableColumn->setIsPrimaryKey(true);
        $this->assertFalse($this->tableColumn->isNullable());
        $this->tableColumn->setIsNullable(false);
        $this->assertFalse($this->tableColumn->isNullable());
    }

    /**
     * check that setting and getting the default value works as expected
     *
     * @test
     */
    public function defaultValue()
    {
        $this->assertNull($this->tableColumn->getDefaultValue());
        $this->tableColumn->setDefaultValue('foo');
        $this->assertEquals('foo', $this->tableColumn->getDefaultValue());
    }

    /**
     * check that setting and getting the isPrimaryKey property works as expected
     *
     * @test
     */
    public function isPrimaryKey()
    {
        $this->assertFalse($this->tableColumn->isPrimaryKey());
        $this->tableColumn->setIsPrimaryKey(true);
        $this->assertTrue($this->tableColumn->isPrimaryKey());
    }

    /**
     * check that setting and getting the isKey property works as expected
     *
     * @test
     */
    public function isKey()
    {
        $this->assertFalse($this->tableColumn->isKey());
        $this->tableColumn->setIsKey(true);
        $this->assertTrue($this->tableColumn->isKey());
        $this->tableColumn->setIsPrimaryKey(true);
        $this->assertFalse($this->tableColumn->isKey());
        $this->tableColumn->setIsKey(false);
        $this->assertFalse($this->tableColumn->isKey());
    }

    /**
     * check that setting and getting the isUnique property works as expected
     *
     * @test
     */
    public function isUnique()
    {
        $this->assertFalse($this->tableColumn->isUnique());
        $this->tableColumn->setIsUnique(true);
        $this->assertTrue($this->tableColumn->isUnique());
        $this->tableColumn->setIsPrimaryKey(true);
        $this->assertFalse($this->tableColumn->isUnique());
        $this->tableColumn->setIsUnique(false);
        $this->assertFalse($this->tableColumn->isUnique());
    }

    /**
     * check that setting and getting the setter method works as expected
     *
     * @test
     */
    public function setterMethod()
    {
        $this->assertNull($this->tableColumn->getSetterMethod());
        $this->assertFalse($this->tableColumn->hasSetterMethod());
        $this->tableColumn->setSetterMethod('setFoo');
        $this->assertEquals('setFoo', $this->tableColumn->getSetterMethod());
        $this->assertTrue($this->tableColumn->hasSetterMethod());
    }
}
?>