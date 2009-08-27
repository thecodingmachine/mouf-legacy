<?php
/**
 * Test for net::stubbles::util::log::stubExceptionLog.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log_test
 * @version     $Id: stubExceptionLogTestCase.php 1753 2008-07-30 15:56:54Z mikey $
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubChainedException',
                      'net::stubbles::util::log::stubExceptionLog',
                      'net::stubbles::util::log::stubMemoryLogAppender'
);
/**
 * Chained exception for test purposes.
 *
 * @package     stubbles
 * @subpackage  util_log_test
 */
class TestExceptionLogException extends stubChainedException
{
    /**
     * returns class name
     *
     * @return  string
     */
    public function getClassName()
    {
        return 'net::stubbles::util::log::test::TestExceptionLogException';
    }
}
/**
 * Test for net::stubbles::util::log::stubExceptionLog.
 *
 * @package     stubbles
 * @subpackage  util_log_test
 * @group       util_log
 */
class stubExceptionLogTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubExceptionLog
     */
    protected $exceptionLog;
    /**
     * log appender to collect logged data
     *
     * @var  stubMemoryLogAppender
     */
    protected $memoryLogAppender;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->exceptionLog = new stubExceptionLog();
        stubRegistry::setConfig(stubLogData::CLASS_REGISTRY_KEY, 'net::stubbles::util::log::stubBaseLogData');
        $binder = new stubBinder();
        stubRegistry::set(stubBinder::REGISTRY_KEY, $binder);
        $logger      = stubLogger::getInstance(__CLASS__);
        $this->logAppender = new stubMemoryLogAppender();
        $logger->addLogAppender($this->logAppender);
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
        
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
        stubRegistry::removeConfig(stubLogData::CLASS_REGISTRY_KEY);
    }

    /**
     * assure that the exception is logged
     *
     * @test
     */
    public function logException()
    {
        $this->exceptionLog->log(new Exception('exception message'));
        $line = __LINE__ - 1;
        $logData = $this->logAppender->getLogData();
        $this->assertEquals(1, count($logData));
        $this->assertEquals(1, count($logData['exceptions']));
        $this->assertEquals('exceptions', $logData['exceptions'][0]->getTarget());
        $this->assertEquals(stubLogger::LEVEL_ERROR, $logData['exceptions'][0]->getLevel());
        $logDataContents = explode(stubLogData::SEPERATOR, $logData['exceptions'][0]->get());
        $this->assertEquals('Exception', $logDataContents[1]);
        $this->assertEquals('exception message', $logDataContents[2]);
        $this->assertEquals(__FILE__, $logDataContents[3]);
        $this->assertEquals($line, $logDataContents[4]);
        $this->assertEquals('', $logDataContents[5]);
        $this->assertEquals('', $logDataContents[6]);
        $this->assertEquals('', $logDataContents[7]);
        $this->assertEquals('', $logDataContents[8]);
    }

    /**
     * assure that the exception is logged
     *
     * @test
     */
    public function logChainedException()
    {
        $exception = new TestExceptionLogException('chained exception', new Exception('exception message'));
        $line = __LINE__ - 1;
        $this->exceptionLog->setLogTarget('foo');
        $this->exceptionLog->setLogLevel(stubLogger::LEVEL_DEBUG);
        $this->exceptionLog->log($exception);
        $logData = $this->logAppender->getLogData();
        $this->assertEquals(1, count($logData));
        $this->assertEquals(1, count($logData['foo']));
        $this->assertEquals('foo', $logData['foo'][0]->getTarget());
        $this->assertEquals(stubLogger::LEVEL_DEBUG, $logData['foo'][0]->getLevel());
        $logDataContents = explode(stubLogData::SEPERATOR, $logData['foo'][0]->get());
        $this->assertEquals('net::stubbles::util::log::test::TestExceptionLogException', $logDataContents[1]);
        $this->assertEquals('chained exception', $logDataContents[2]);
        $this->assertEquals(__FILE__, $logDataContents[3]);
        $this->assertEquals($line, $logDataContents[4]);
        $this->assertEquals('Exception', $logDataContents[5]);
        $this->assertEquals('exception message', $logDataContents[6]);
        $this->assertEquals(__FILE__, $logDataContents[7]);
        $this->assertEquals($line, $logDataContents[8]);
    }

    /**
     * assure that the exception is logged
     *
     * @test
     */
    public function logChainedExceptionWithoutChainedException()
    {
        $exception = new TestExceptionLogException('chained exception');
        $line = __LINE__ - 1;
        $this->exceptionLog->setLogTarget('foo');
        $this->exceptionLog->setLogLevel(stubLogger::LEVEL_DEBUG);
        $this->exceptionLog->log($exception);
        $logData = $this->logAppender->getLogData();
        $this->assertEquals(1, count($logData));
        $this->assertEquals(1, count($logData['foo']));
        $this->assertEquals('foo', $logData['foo'][0]->getTarget());
        $this->assertEquals(stubLogger::LEVEL_DEBUG, $logData['foo'][0]->getLevel());
        $logDataContents = explode(stubLogData::SEPERATOR, $logData['foo'][0]->get());
        $this->assertEquals('net::stubbles::util::log::test::TestExceptionLogException', $logDataContents[1]);
        $this->assertEquals('chained exception', $logDataContents[2]);
        $this->assertEquals(__FILE__, $logDataContents[3]);
        $this->assertEquals($line, $logDataContents[4]);
        $this->assertEquals('', $logDataContents[5]);
        $this->assertEquals('', $logDataContents[6]);
        $this->assertEquals('', $logDataContents[7]);
        $this->assertEquals('', $logDataContents[8]);
    }
}
?>