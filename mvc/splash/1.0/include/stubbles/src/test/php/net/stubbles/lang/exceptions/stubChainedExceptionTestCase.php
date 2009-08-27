<?php
/**
 * Tests for net::stubbles::lang::exceptions::stubChainedException.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_exceptions_test
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubChainedException');
class stub1ChainedException extends stubChainedException
{
    public function getClassname()
    {
        return 'test::stub1ChainedException';
    }
}
class stub2ChainedException extends stubChainedException
{
    public function getClassname()
    {
        return 'test::stub2ChainedException';
    }
}
/**
 * Tests for net::stubbles::lang::exceptions::stubChainedException.
 *
 * @package     stubbles
 * @subpackage  lang_exceptions_test
 * @group       lang
 * @group       lang_exceptions
 */
class stubChainedExceptionTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance 1 to be used for tests
     *
     * @var  stubChainedException
     */
    protected $stubChainedException1;
    /**
     * instance 2 to be used for tests
     *
     * @var  stubChainedException
     */
    protected $stubChainedException2;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->stubChainedException1 = new stub1ChainedException('This is a chained exception.');
        $this->stubChainedException2 = new stub2ChainedException('This is an exception.', $this->stubChainedException1);
        $this->stubChainedException3 = new stub2ChainedException('foobar', new stub1ChainedException('This is a chained exception.', new Exception('baz')));
    }

    /**
     * no cause means false, a cause true
     *
     * @test
     */
    public function hasCause()
    {
        $this->assertFalse($this->stubChainedException1->hasCause());
        $this->assertTrue($this->stubChainedException2->hasCause());
        $this->assertTrue($this->stubChainedException3->hasCause());
    }

    /**
     * cause should be the same as set
     *
     * @test
     */
    public function getCause()
    {
        $this->assertNull($this->stubChainedException1->getCause());
        $this->assertSame($this->stubChainedException1, $this->stubChainedException2->getCause());
    }

    /**
     * the final message should be found
     *
     * @test
     */
    public function getFinalMessage()
    {
        $this->assertEquals('This is a chained exception.', $this->stubChainedException1->getFinalMessage());
        $this->assertEquals('This is a chained exception.', $this->stubChainedException2->getFinalMessage());
        $this->assertEquals('baz', $this->stubChainedException3->getFinalMessage());
    }

    /**
     * string representation should contain some useful informations
     *
     * @test
     */
    public function toStringResult()
    {
        $this->assertEquals("test::stub1ChainedException {\n    message(string): This is a chained exception.\n    file(string): " . __FILE__ . "\n    line(integer): 52\n    code(integer): 0\n}\n", (string) $this->stubChainedException1);
        $this->assertEquals("test::stub2ChainedException {\n    message(string): This is an exception.\n    file(string): " . __FILE__ . "\n    line(integer): 53\n    code(integer): 0\n} caused by test::stub1ChainedException {\n    message(string): This is a chained exception.\n    file(string): " . __FILE__ . "\n    line(integer): 52\n    code(integer): 0\n}\n", (string) $this->stubChainedException2);
        $this->assertEquals("test::stub1ChainedException {\n    message(string): This is a chained exception.\n    file(string): " . __FILE__ . "\n    line(integer): 54\n    code(integer): 0\n} caused by Exception {\n    message(string): baz\n    file(string): " . __FILE__ . "\n    line(integer): 54\n    code(integer): 0\n}\n", (string) $this->stubChainedException3->getCause());
        $exception = new stub1ChainedException('message', new stubException('otherMessage'));
        $this->assertEquals("test::stub1ChainedException {\n    message(string): message\n    file(string): " . __FILE__ . "\n    line(integer): 102\n    code(integer): 0\n} caused by net::stubbles::lang::exceptions::stubException {\n    message(string): otherMessage\n    file(string): " . __FILE__ . "\n    line(integer): 102\n    code(integer): 0\n}\n", (string) $exception);
    }
}
?>