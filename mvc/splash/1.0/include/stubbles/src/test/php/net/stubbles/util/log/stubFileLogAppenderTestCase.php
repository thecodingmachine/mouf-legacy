<?php
/**
 * Test for net::stubbles::util::log::stubFileLogAppender.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log_test
 */
stubClassLoader::load('net::stubbles::util::log::stubFileLogAppender');
/**
 * Test for net::stubbles::util::log::stubFileLogAppender.
 *
 * @package     stubbles
 * @subpackage  util_log_test
 * @group       util_log
 */
class stubFileLogAppenderTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * the logfile
     *
     * @var  string
     */
    protected $logFile;
    /**
     * instance to test
     *
     * @var  stubFileLogAppender
     */
    protected $fileLogAppender;
    
    /**
     * set up the test environment
     */
    public function setUp()
    {
        $this->logFile = stubConfig::getLogPath() . '/test/' . date('Y') . '/' . date('m') . '/foo-' . date('Y-m-d') . '.log';
        if (file_exists($this->logFile) == true) {
            unlink($this->logFile);
        }
        
        $this->fileLogAppender = new stubFileLogAppender(stubConfig::getLogPath() . '/test/{Y}/{M}');
    }
    
    /**
     * clean up test environment
     */
    public function tearDown()
    {
        if (file_exists($this->logFile) == true) {
            unlink($this->logFile);
        }
        
        if (file_exists(stubConfig::getLogPath() . '/test/' . date('Y') . '/' . date('m')) == true) {
            rmdir(stubConfig::getLogPath() . '/test/' . date('Y') . '/' . date('m'));
        }
        
        if (file_exists(stubConfig::getLogPath() . '/test/' . date('Y')) == true) {
            rmdir(stubConfig::getLogPath() . '/test/' . date('Y'));
        }
        
        if (file_exists(stubConfig::getLogPath() . '/test') == true) {
            rmdir(stubConfig::getLogPath() . '/test');
        }
    }
    
    /**
     * assure that data will be written into logfile
     *
     * @test
     */
    public function append()
    {
        $this->assertFalse(file_exists($this->logFile));
        $mockLogData = $this->getMock('stubLogData');
        $mockLogData->expects($this->any())->method('getTarget')->will($this->returnValue('foo'));
        $mockLogData->expects($this->any())->method('get')->will($this->returnValue('bar|baz'));
        $this->fileLogAppender->append($mockLogData);
        $this->fileLogAppender->append($mockLogData);
        $this->assertTrue(file_exists($this->logFile));
        $this->assertEquals("bar|baz\nbar|baz\n", @file_get_contents($this->logFile));
    }
}
?>