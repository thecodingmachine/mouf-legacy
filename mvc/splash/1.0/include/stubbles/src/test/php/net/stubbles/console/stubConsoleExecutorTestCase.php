<?php
/**
 * Test for net::stubbles::console::stubConsoleExecutor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  console_test
 */
stubClassLoader::load('net::stubbles::console::stubConsoleExecutor');
/**
 * Test for net::stubbles::console::stubConsoleExecutor.
 *
 * @package     stubbles
 * @subpackage  console_test
 * @group       console
 */
class stubConsoleExecutorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubConsoleExecutor
     */
    protected $executor;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->executor = new stubConsoleExecutor();
    }

    /**
     * redirectTo() should return itself
     *
     * @test
     */
    public function redirectToReturnsItself()
    {
        $this->assertSame($this->executor, $this->executor->redirectTo('2>&1'));
    }

    /**
     * execute() without output stream set
     *
     * @test
     */
    public function executeWithoutOutputStream()
    {
        $this->assertNull($this->executor->getOutputStream());
        $this->assertSame($this->executor, $this->executor->execute('echo foo'));
    }

    /**
     * execute() with former output stream set
     *
     * @test
     */
    public function executeWithOutputStream()
    {
        $mockOutputStream = $this->getMock('stubOutputStream');
        $mockOutputStream->expects($this->once())
                         ->method('writeLine')
                         ->with($this->equalTo('foo'));
        $this->assertSame($this->executor, $this->executor->streamOutputTo($mockOutputStream));
        $this->assertSame($mockOutputStream, $this->executor->getOutputStream());
        $this->assertSame($this->executor, $this->executor->execute('echo foo'));
    }

    /**
     * execute() fails and throws an exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function executeFails()
    {
        $this->executor->execute('php -r "throw new Exception();"');
    }

}
?>