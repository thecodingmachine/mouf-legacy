<?php
/**
 * Test for net::stubbles::console::stubConsoleInputStream.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  console_test
 */
stubClassLoader::load('net::stubbles::console::stubConsoleInputStream');
/**
 * Test for net::stubbles::console::stubConsoleInputStream.
 *
 * @package     stubbles
 * @subpackage  console_test
 * @group       console
 */
class stubConsoleInputStreamTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * console input stream is always the same instance
     *
     * @test
     */
    public function sameInstance()
    {
        $in1 = stubConsoleInputStream::forIn();
        $in2 = stubConsoleInputStream::forIn();
        $this->assertSame($in1, $in2);
    }
}
?>