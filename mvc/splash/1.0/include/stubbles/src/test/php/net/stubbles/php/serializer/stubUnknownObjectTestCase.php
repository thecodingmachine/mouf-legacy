<?php
/**
 * Tests for net::stubbles::php::serializer::stubUnknownObject.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  php_serializer_test
 */
stubClassLoader::load('net::stubbles::php::serializer::stubUnknownObject');
/**
 * Tests for net::stubbles::php::serializer::stubUnknownObject.
 *
 * @package     stubbles
 * @subpackage  php_serializer_test
 * @group       php
 * @group       php_serializer
 */
class stubUnknownObjectTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubUnknownObject
     */
    protected $unknownObject;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->unknownObject = new stubUnknownObject('foo', array('bar' => 'baz'));
    }

    /**
     * assure that class name mapping works as expected
     *
     * @test
     */
    public function getNameReturnsMappedName()
    {
        $this->assertEquals('foo', $this->unknownObject->getName());
    }

    /**
     * assure that class property mapping works as expected
     *
     * @test
     */
    public function getProperties()
    {
        $this->assertEquals(array('bar' => 'baz'), $this->unknownObject->getProperties());
    }

    /**
     * assure that the __set() method throws exception
     *
     * @test
     * @expectedException  stubIllegalAccessException
     */
    public function magicSet()
    {
        $this->unknownObject->foo = 'bar';
    }

    /**
     * assure that the __get() method throws exception
     *
     * @test
     * @expectedException  stubIllegalAccessException
     */
    public function magicGet()
    {
        $x = $this->unknownObject->foo;
    }

    /**
     * assure that the __call() method throws exception
     *
     * @test
     * @expectedException  stubIllegalAccessException
     */
    public function magicCall()
    {
        $this->unknownObject->doSomething();
    }

    /**
     * assure that the cloning the object throws exception
     *
     * @test
     * @expectedException  stubIllegalAccessException
     */
    public function cloneInstance()
    {
        $foo = clone $this->unknownObject;
    }
}
?>