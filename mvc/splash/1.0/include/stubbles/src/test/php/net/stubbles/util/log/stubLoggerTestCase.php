<?php
/**
 * Test for net::stubbles::util::log::stubLogger.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log_test
 */
stubClassLoader::load('net::stubbles::util::log::stubLogger');
/**
 * Test for net::stubbles::util::log::stubLogger.
 *
 * @package     stubbles
 * @subpackage  util_log_test
 * @group       util_log
 */
class stubLoggerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubLogger
     */
    protected $stubLogger;

    /**
     * set up the test environment
     */
    public function setUp()
    {
        $this->stubLogger = stubLogger::getInstance();
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        $loggerIds = stubLogger::getInstanceList();
        foreach ($loggerIds as $loggerId) {
            stubLogger::destroyInstance($loggerId);
        }
    }

    /**
     * assure that creation of loggers works as expected
     *
     * @test
     */
    public function creation()
    {
        $this->assertEquals(stubLogger::DEFAULT_ID, $this->stubLogger->getId());
        $this->assertEquals(stubLogger::LEVEL_ALL, $this->stubLogger->getLevel());
        $stubLogger = stubLogger::getInstance();
        $this->assertSame($this->stubLogger, $stubLogger);
    }

    /**
     * assert that level handling is correct
     *
     * @test
     */
    public function isApplicableDefault()
    {
        $this->assertFalse($this->stubLogger->isApplicable(stubLogger::LEVEL_NONE));
        $this->assertTrue($this->stubLogger->isApplicable(stubLogger::LEVEL_ALL));
        $this->assertTrue($this->stubLogger->isApplicable(stubLogger::LEVEL_DEBUG));
        $this->assertTrue($this->stubLogger->isApplicable(stubLogger::LEVEL_INFO));
        $this->assertTrue($this->stubLogger->isApplicable(stubLogger::LEVEL_WARN));
        $this->assertTrue($this->stubLogger->isApplicable(stubLogger::LEVEL_ERROR));
    }

    /**
     * assert that level handling is correct
     *
     * @test
     */
    public function isApplicableDebug()
    {
        $stubLogger = stubLogger::getInstance('debug', stubLogger::LEVEL_DEBUG);
        $this->assertFalse($this->stubLogger->isApplicable(stubLogger::LEVEL_NONE));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ALL));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_DEBUG));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_INFO));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_WARN));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ERROR));
        $this->assertEquals(stubLogger::LEVEL_DEBUG, $stubLogger->getLevel());
        stubLogger::destroyInstance('debug');
    }

    /**
     * assert that level handling is correct
     *
     * @test
     */
    public function isApplicableInfo()
    {
        $stubLogger = stubLogger::getInstance('info', stubLogger::LEVEL_INFO);
        $this->assertFalse($this->stubLogger->isApplicable(stubLogger::LEVEL_NONE));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ALL));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_DEBUG));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_INFO));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_WARN));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ERROR));
        $this->assertEquals(stubLogger::LEVEL_INFO, $stubLogger->getLevel());
        stubLogger::destroyInstance('info');
    }

    /**
     * assert that level handling is correct
     *
     * @test
     */
    public function isApplicableWarn()
    {
        $stubLogger = stubLogger::getInstance('warn', stubLogger::LEVEL_WARN);
        $this->assertFalse($this->stubLogger->isApplicable(stubLogger::LEVEL_NONE));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ALL));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_DEBUG));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_INFO));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_WARN));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ERROR));
        $this->assertEquals(stubLogger::LEVEL_WARN, $stubLogger->getLevel());
        stubLogger::destroyInstance('warn');
    }

    /**
     * assert that level handling is correct
     *
     * @test
     */
    public function isApplicableError()
    {
        $stubLogger = stubLogger::getInstance('error', stubLogger::LEVEL_ERROR);
        $this->assertFalse($this->stubLogger->isApplicable(stubLogger::LEVEL_NONE));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ALL));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_DEBUG));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_INFO));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_WARN));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_ERROR));
        $this->assertEquals(stubLogger::LEVEL_ERROR, $stubLogger->getLevel());
        stubLogger::destroyInstance('error');
    }

    /**
     * assert that level handling is correct
     *
     * @test
     */
    public function isApplicableDebugInfo()
    {
        $stubLogger = stubLogger::getInstance('debuginfo', (stubLogger::LEVEL_DEBUG + stubLogger::LEVEL_INFO));
        $this->assertFalse($this->stubLogger->isApplicable(stubLogger::LEVEL_NONE));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ALL));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_DEBUG));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_INFO));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_WARN));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ERROR));
        $this->assertEquals((stubLogger::LEVEL_DEBUG + stubLogger::LEVEL_INFO), $stubLogger->getLevel());
        stubLogger::destroyInstance('debuginfo');
    }

    /**
     * assert that level handling is correct
     *
     * @test
     */
    public function isApplicableDebugWarn()
    {
        $stubLogger = stubLogger::getInstance('debugwarn', (stubLogger::LEVEL_DEBUG + stubLogger::LEVEL_WARN));
        $this->assertFalse($this->stubLogger->isApplicable(stubLogger::LEVEL_NONE));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ALL));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_DEBUG));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_INFO));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_WARN));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ERROR));
        $this->assertEquals((stubLogger::LEVEL_DEBUG + stubLogger::LEVEL_WARN), $stubLogger->getLevel());
        stubLogger::destroyInstance('debugwarn');
    }

    /**
     * assert that level handling is correct
     *
     * @test
     */
    public function isApplicableDebugError()
    {
        $stubLogger = stubLogger::getInstance('debugerror', (stubLogger::LEVEL_DEBUG + stubLogger::LEVEL_ERROR));
        $this->assertFalse($this->stubLogger->isApplicable(stubLogger::LEVEL_NONE));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ALL));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_DEBUG));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_INFO));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_WARN));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_ERROR));
        $this->assertEquals((stubLogger::LEVEL_DEBUG + stubLogger::LEVEL_ERROR), $stubLogger->getLevel());
        stubLogger::destroyInstance('debugerror');
    }

    /**
     * assert that level handling is correct
     *
     * @test
     */
    public function isApplicableInfoWarn()
    {
        $stubLogger = stubLogger::getInstance('infowarn', (stubLogger::LEVEL_INFO + stubLogger::LEVEL_WARN));
        $this->assertFalse($this->stubLogger->isApplicable(stubLogger::LEVEL_NONE));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ALL));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_DEBUG));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_INFO));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_WARN));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ERROR));
        $this->assertEquals((stubLogger::LEVEL_INFO + stubLogger::LEVEL_WARN), $stubLogger->getLevel());
        stubLogger::destroyInstance('infowarn');
    }

    /**
     * assert that level handling is correct
     *
     * @test
     */
    public function isApplicableInfoError()
    {
        $stubLogger = stubLogger::getInstance('infoerror', (stubLogger::LEVEL_INFO + stubLogger::LEVEL_ERROR));
        $this->assertFalse($this->stubLogger->isApplicable(stubLogger::LEVEL_NONE));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ALL));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_DEBUG));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_INFO));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_WARN));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_ERROR));
        $this->assertEquals((stubLogger::LEVEL_INFO + stubLogger::LEVEL_ERROR), $stubLogger->getLevel());
        stubLogger::destroyInstance('infoerror');
    }

    /**
     * assert that level handling is correct
     *
     * @test
     */
    public function isApplicableWarnError()
    {
        $stubLogger = stubLogger::getInstance('warnerror', (stubLogger::LEVEL_WARN + stubLogger::LEVEL_ERROR));
        $this->assertFalse($this->stubLogger->isApplicable(stubLogger::LEVEL_NONE));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_ALL));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_DEBUG));
        $this->assertFalse($stubLogger->isApplicable(stubLogger::LEVEL_INFO));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_WARN));
        $this->assertTrue($stubLogger->isApplicable(stubLogger::LEVEL_ERROR));
        $this->assertEquals((stubLogger::LEVEL_WARN + stubLogger::LEVEL_ERROR), $stubLogger->getLevel());
        stubLogger::destroyInstance('warnerror');
    }

    /**
     * test the instance logging method
     *
     * @test
     */
    public function log()
    {
        $mockLogData      = $this->getMock('stubLogData');
        $mockLogAppender1 = $this->getMock('stubLogAppender');
        $mockLogAppender1->expects($this->once())->method('append')->with($this->equalTo($mockLogData));
        $mockLogAppender2 = $this->getMock('stubLogAppender');
        $mockLogAppender2->expects($this->once())->method('append')->with($this->equalTo($mockLogData));
        $this->stubLogger->addLogAppender($mockLogAppender1);
        $this->stubLogger->addLogAppender($mockLogAppender2);
        $this->stubLogger->log($mockLogData);
    }

    /**
     * test the static logging method
     *
     * @test
     */
    public function logToAll()
    {
        $mockLogData      = $this->getMock('stubLogData');
        $mockLogData->expects($this->any())->method('getLevel')->will($this->returnValue(stubLogger::LEVEL_DEBUG));
        $stubLoggerDebug = stubLogger::getInstance('debug', stubLogger::LEVEL_DEBUG);
        $stubLoggerNone = stubLogger::getInstance('none', stubLogger::LEVEL_NONE);
        $stubLoggerWarn = stubLogger::getInstance('warn', stubLogger::LEVEL_WARN);
        $mockLogAppender1 = $this->getMock('stubLogAppender');
        $mockLogAppender1->expects($this->once())->method('append')->with($this->equalTo($mockLogData));
        $this->stubLogger->addLogAppender($mockLogAppender1);
        $mockLogAppender2 = $this->getMock('stubLogAppender');
        $mockLogAppender2->expects($this->once())->method('append')->with($this->equalTo($mockLogData));
        $stubLoggerDebug->addLogAppender($mockLogAppender2);
        $mockLogAppender3 = $this->getMock('stubLogAppender');
        $mockLogAppender3->expects($this->never())->method('append');
        $stubLoggerNone->addLogAppender($mockLogAppender2);
        $mockLogAppender4 = $this->getMock('stubLogAppender');
        $mockLogAppender4->expects($this->never())->method('append');
        $stubLoggerWarn->addLogAppender($mockLogAppender2);
        stubLogger::logToAll($mockLogData);
        stubLogger::destroyInstance('debug');
        stubLogger::destroyInstance('none');
        stubLogger::destroyInstance('warn');
    }

    /**
     * assure that instances are really destroyed
     *
     * @test
     */
    public function destroy()
    {
        $loggerTest = stubLogger::getInstance('test');
        stubLogger::destroyInstance('test');
        $this->assertFalse(in_array('test', stubLogger::getInstanceList()));
    }
}
?>