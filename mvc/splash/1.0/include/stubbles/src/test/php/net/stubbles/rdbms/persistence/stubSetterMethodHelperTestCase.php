<?php
/**
 * Test for net::stubbles::rdbms::persistence::stubSetterMethodHelper.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_test
 * @version     $Id: stubSetterMethodHelperTestCase.php 1926 2008-11-10 23:50:23Z mikey $
 */
stubClassLoader::load('net::stubbles::rdbms::persistence::stubSetterMethodHelper',
                      'net::stubbles::rdbms::persistence::annotation::stubDBColumnAnnotation'
);
require_once dirname(__FILE__) . '/../persistence/MockSinglePrimaryKeyEntity.php';
/**
 * Test for net::stubbles::rdbms::persistence::stubSetterMethodHelper.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_test
 * @group       rdbms
 * @group       rdbms_persistence
 */
class stubSetterMethodHelperTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubSetterMethodHelper
     */
    protected $setterMethodHelper;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->setterMethodHelper = new stubSetterMethodHelper('MockSinglePrimaryKeyEntity');
    }

    /**
     * test that the setter methods are applied as expected
     *
     * @test
     */
    public function setterMethods()
    {
        $entity = new MockSinglePrimaryKeyEntity();
        $refMethod   = new stubReflectionMethod('MockSinglePrimaryKeyEntity', 'getId');
        $this->setterMethodHelper->addSetterMethod($refMethod->getAnnotation('DBColumn')->getTableColumn(), $refMethod->getName());
        $refMethod   = new stubReflectionMethod('MockSinglePrimaryKeyEntity', 'withAnnotation');
        $this->setterMethodHelper->addSetterMethod($refMethod->getAnnotation('DBColumn')->getTableColumn(), $refMethod->getName());
        $refMethod   = new stubReflectionMethod('MockSinglePrimaryKeyEntity', 'withDefaultValue');
        $this->setterMethodHelper->addSetterMethod($refMethod->getAnnotation('DBColumn')->getTableColumn(), $refMethod->getName());
        $refMethod   = new stubReflectionMethod('MockSinglePrimaryKeyEntity', 'withDate');
        $this->setterMethodHelper->addSetterMethod($refMethod->getAnnotation('DBColumn')->getTableColumn(), $refMethod->getName());
        $this->setterMethodHelper->applySetterMethods($entity, array('id' => 909, 'default' => 'foo', 'ignored' => 'ignored', 'date' => null));
        $this->assertEquals(909, $entity->getId());
        $this->assertEquals('this is bar', $entity->withAnnotation());
        $this->assertEquals('foo', $entity->withDefaultValue());
        $this->assertNull($entity->withDate());
    }

    /**
     * test that the setter methods are applied as expected
     *
     * @test
     */
    public function setterMethodsWithDate()
    {
        $entity = new MockSinglePrimaryKeyEntity();
        $refMethod   = new stubReflectionMethod('MockSinglePrimaryKeyEntity', 'getId');
        $this->setterMethodHelper->addSetterMethod($refMethod->getAnnotation('DBColumn')->getTableColumn(), $refMethod->getName());
        $refMethod   = new stubReflectionMethod('MockSinglePrimaryKeyEntity', 'withAnnotation');
        $this->setterMethodHelper->addSetterMethod($refMethod->getAnnotation('DBColumn')->getTableColumn(), $refMethod->getName());
        $refMethod   = new stubReflectionMethod('MockSinglePrimaryKeyEntity', 'withDefaultValue');
        $this->setterMethodHelper->addSetterMethod($refMethod->getAnnotation('DBColumn')->getTableColumn(), $refMethod->getName());
        $refMethod   = new stubReflectionMethod('MockSinglePrimaryKeyEntity', 'withDate');
        $this->setterMethodHelper->addSetterMethod($refMethod->getAnnotation('DBColumn')->getTableColumn(), $refMethod->getName());
        $this->setterMethodHelper->applySetterMethods($entity, array('id' => 909, 'default' => 'foo', 'ignored' => 'ignored', 'date' => '2008-10-23 19:18:09'));
        $this->assertEquals(909, $entity->getId());
        $this->assertEquals('this is bar', $entity->withAnnotation());
        $this->assertEquals('foo', $entity->withDefaultValue());
        $this->assertEquals('2008-10-23 19:18:09', $entity->withDate()->format('Y-m-d H:i:s'));
    }

    /**
     * ensure that wrong entity type throws exception
     *
     * @test
     * @expectedException  stubPersistenceException
     */
    public function applySetterMethodsWithWrongEntity()
    {
        $this->setterMethodHelper->applySetterMethods(new stdClass(), array('id' => 909, 'default' => 'foo'));
    }
}
?>