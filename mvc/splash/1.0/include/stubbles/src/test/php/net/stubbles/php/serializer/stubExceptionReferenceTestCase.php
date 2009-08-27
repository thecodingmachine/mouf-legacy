<?php
/**
 * Tests for net::stubbles::php::serializer::stubExceptionReference.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  php_serializer_test
 */
stubClassLoader::load('net::stubbles::php::serializer::stubExceptionReference');
/**
 * Tests for net::stubbles::php::serializer::stubExceptionReference.
 *
 * @package     stubbles
 * @subpackage  php_serializer_test
 * @group       php
 * @group       php_serializer
 */
class stubExceptionReferenceTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubExceptionReference
     */
    protected $exceptionReference;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->exceptionReference = new stubExceptionReference('error message');
    }

    /**
     * assure that exceptionName property can be get and set
     *
     * @test
     */
    public function exceptionNameProperty()
    {
        $this->assertNull($this->exceptionReference->getReferencedExceptionName());
        $this->exceptionReference->setReferencedExceptionName('foo::bar::BazException');
        $this->assertEquals('foo::bar::BazException', $this->exceptionReference->getReferencedExceptionName());
    }
    /**
     * assure that stack trace property can be get and set
     *
     * @test
     */
    public function stackTraceProperty()
    {
        $this->assertEquals(array(), $this->exceptionReference->getReferencedStackTrace());
        $this->exceptionReference->setReferencedStackTrace(array('foo' => 'bar'));
        $this->assertEquals(array('foo' => 'bar'), $this->exceptionReference->getReferencedStackTrace());
    }
}
?>