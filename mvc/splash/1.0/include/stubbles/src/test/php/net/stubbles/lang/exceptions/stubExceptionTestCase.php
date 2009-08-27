<?php
/**
 * Tests for net::stubbles::lang::exceptions::stubException.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_exceptions_test
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubException');
/**
 * Helper class for equal() tests.
 *
 * @package     stubbles
 * @subpackage  lang_exceptions_test
 */
class stub1stubException extends stubException
{
    /**
     * needs to have a class name
     *
     * @return  string
     */
    public function getClassname()
    {
        return 'net::stubbles::lang::exceptions::test::stub1stubException';
    }
}
/**
 * Helper class for equal() tests.
 *
 * @package     stubbles
 * @subpackage  lang_exceptions_test
 */
class stub2stubException extends stubException
{
    /**
     * needs to have a class name
     *
     * @return  string
     */
    public function getClassname()
    {
        return 'net::stubbles::lang::exceptions::test::stub2stubException';
    }
}
/**
 * Tests for net::stubbles::lang::exceptions::stubException.
 *
 * @package     stubbles
 * @subpackage  lang_exceptions_test
 * @group       lang
 * @group       lang_exceptions
 */
class stubExceptionTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance 1 to be used for tests
     *
     * @var  stubException
     */
    protected $stubException1;
    /**
     * instance 2 to be used for tests
     *
     * @var  stubException
     */
    protected $stubException2;
    /**
     * instance 3 to be used for tests
     *
     * @var  stubException
     */
    protected $stubException3;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->stubException1 = new stub1stubException();
        $this->stubException2 = new stub2stubException();
        $this->stubException3 = new stubException('message');
    }

    /**
     * assure that class name mapping works as expected
     *
     * @test
     */
    public function classAndClassName()
    {
        $refObject = $this->stubException3->getClass();
        $this->assertType('stubReflectionObject', $refObject);
        $this->assertEquals('stubException', $refObject->getName());
        $this->assertEquals('net::stubbles::lang::exceptions::stubException', $this->stubException3->getClassName());
    }

    /**
     * correct package should be returned
     *
     * @test
     */
    public function packageAndPackageName()
    {
        $refPackage = $this->stubException3->getPackage();
        $this->assertType('stubReflectionPackage', $refPackage);
        $this->assertEquals('net::stubbles::lang::exceptions', $refPackage->getName());
        $this->assertEquals('net::stubbles::lang::exceptions', $this->stubException3->getPackageName());
    }

    /**
     * assure that the equal() method works correct
     *
     * @test
     */
    public function comparisonWithEquals()
    {
        $this->assertTrue($this->stubException1->equals($this->stubException1));
        $this->assertTrue($this->stubException2->equals($this->stubException2));
        $this->assertFalse($this->stubException1->equals($this->stubException2));
        $this->assertFalse($this->stubException1->equals('foo'));
        $this->assertFalse($this->stubException1->equals(6));
        $this->assertFalse($this->stubException1->equals(true));
        $this->assertFalse($this->stubException1->equals(false));
        $this->assertFalse($this->stubException1->equals(null));
        $this->assertFalse($this->stubException2->equals($this->stubException1));
        $this->assertFalse($this->stubException1->equals(new stub1stubException()));
        $this->assertFalse($this->stubException2->equals(new stub2stubException()));
    }

    /**
     * string representation should contain some useful informations
     *
     * @test
     */
    public function toStringResult()
    {
        $this->assertEquals("net::stubbles::lang::exceptions::stubException {\n    message(string): message\n    file(string): " . __FILE__ . "\n    line(integer): 82\n    code(integer): 0\n}\n", (string) $this->stubException3);
    }
}
?>