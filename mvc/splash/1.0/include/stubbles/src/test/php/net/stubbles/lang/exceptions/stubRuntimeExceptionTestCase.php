<?php
/**
 * Tests for net::stubbles::lang::exceptions::stubRuntimeException.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_exceptions_test
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubRuntimeException');
/**
 * Helper class for equal() tests.
 *
 * @package     stubbles
 * @subpackage  lang_exceptions_test
 */
class stub1stubRuntimeException extends stubRuntimeException
{
    /**
     * needs to have a class name
     *
     * @return  string
     */
    public function getClassname()
    {
        return 'net::stubbles::lang::exceptions::test::stub1stubRuntimeException';
    }
}
/**
 * Helper class for equal() tests.
 *
 * @package     stubbles
 * @subpackage  lang_exceptions_test
 */
class stub2stubRuntimeException extends stubRuntimeException
{
    /**
     * needs to have a class name
     *
     * @return  string
     */
    public function getClassname()
    {
        return 'net::stubbles::lang::exceptions::test::stub2stubRuntimeException';
    }
}
/**
 * Tests for net::stubbles::lang::exceptions::stubRuntimeException.
 *
 * @package     stubbles
 * @subpackage  lang_exceptions_test
 * @group       lang
 * @group       lang_exceptions
 */
class stubRuntimeExceptionTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance 1 to be used for tests
     *
     * @var  stubRuntimeException
     */
    protected $runtimeException1;
    /**
     * instance 2 to be used for tests
     *
     * @var  stubRuntimeException
     */
    protected $runtimeException2;
    /**
     * instance 3 to be used for tests
     *
     * @var  stubRuntimeException
     */
    protected $runtimeException3;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->runtimeException1 = new stub1stubRuntimeException();
        $this->runtimeException2 = new stub2stubRuntimeException();
        $this->runtimeException3 = new stubRuntimeException('message');
    }

    /**
     * assure that class name mapping works as expected
     *
     * @test
     */
    public function classAndClassName()
    {
        $refObject = $this->runtimeException3->getClass();
        $this->assertType('stubReflectionObject', $refObject);
        $this->assertEquals('stubRuntimeException', $refObject->getName());
        $this->assertEquals('net::stubbles::lang::exceptions::stubRuntimeException', $this->runtimeException3->getClassName());
    }

    /**
     * correct package should be returned
     *
     * @test
     */
    public function packageAndPackageName()
    {
        $refPackage = $this->runtimeException3->getPackage();
        $this->assertType('stubReflectionPackage', $refPackage);
        $this->assertEquals('net::stubbles::lang::exceptions', $refPackage->getName());
        $this->assertEquals('net::stubbles::lang::exceptions', $this->runtimeException3->getPackageName());
    }

    /**
     * assure that the equal() method works correct
     *
     * @test
     */
    public function comparisonWithEquals()
    {
        $this->assertTrue($this->runtimeException1->equals($this->runtimeException1));
        $this->assertTrue($this->runtimeException2->equals($this->runtimeException2));
        $this->assertFalse($this->runtimeException1->equals($this->runtimeException2));
        $this->assertFalse($this->runtimeException1->equals('foo'));
        $this->assertFalse($this->runtimeException1->equals(6));
        $this->assertFalse($this->runtimeException1->equals(true));
        $this->assertFalse($this->runtimeException1->equals(false));
        $this->assertFalse($this->runtimeException1->equals(null));
        $this->assertFalse($this->runtimeException2->equals($this->runtimeException1));
        $this->assertFalse($this->runtimeException1->equals(new stub1stubRuntimeException()));
        $this->assertFalse($this->runtimeException2->equals(new stub2stubRuntimeException()));
    }

    /**
     * string representation should contain some useful informations
     *
     * @test
     */
    public function toStringResult()
    {
        $strings = explode("#0", (string) $this->runtimeException3);
        $this->assertEquals("net::stubbles::lang::exceptions::stubRuntimeException {\n    message(string): message\n    file(string): " . __FILE__ . "\n    line(integer): 82\n    code(integer): 0\n}\n", $strings[0]);
    }
}
?>