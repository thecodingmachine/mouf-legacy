<?php
/**
 * Test for net::stubbles::console::stubConsoleOutputStream.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  console_test
 */
stubClassLoader::load('net::stubbles::console::stubConsoleOutputStream');
/**
 * Test for net::stubbles::console::stubConsoleOutputStream.
 *
 * @package     stubbles
 * @subpackage  console_test
 * @group       console
 */
class stubConsoleOutputStreamTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * console output stream is always the same instance
     *
     * @test
     */
    public function sameOutInstance()
    {
        $out1 = stubConsoleOutputStream::forOut();
        $out2 = stubConsoleOutputStream::forOut();
        $this->assertSame($out1, $out2);
    }

    /**
     * console error stream is always the same instance
     *
     * @test
     */
    public function sameErrInstance()
    {
        $err1 = stubConsoleOutputStream::forError();
        $err2 = stubConsoleOutputStream::forError();
        $this->assertSame($err1, $err2);
    }
}
?>