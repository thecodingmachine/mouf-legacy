<?php
/**
 * Tests for net::stubbles::lang::errorhandler::stubAbstractExceptionHandler.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_errorhandler_test
 */
stubClassLoader::load('net::stubbles::lang::errorhandler::stubAbstractExceptionHandler',
                      'net::stubbles::lang::exceptions::stubChainedException',
                      'net::stubbles::util::log::stubMemoryLogAppender'
);
/**
 * Chained exception for test purposes.
 *
 * @package     stubbles
 * @subpackage  lang_errorhandler_test
 */
class TestAbstractExceptionHandlerException extends stubChainedException
{
    /**
     * returns class name
     *
     * @return  string
     */
    public function getClassName()
    {
        return 'net::stubbles::lang::errorhandler::test::TestAbstractExceptionHandlerException';
    }
}
/**
 * Tests for net::stubbles::lang::errorhandler::stubAbstractExceptionHandler.
 *
 * @package     stubbles
 * @subpackage  lang_errorhandler_test
 * @group       lang
 * @group       lang_errorhandler
 */
class stubAbstractExceptionHandlerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubAbstractExceptionHandler
     */
    protected $abstractExceptionHandler;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->abstractExceptionHandler = $this->getMock('stubAbstractExceptionHandler', array('fillResponse'));
        stubRegistry::setConfig(stubLogData::CLASS_REGISTRY_KEY, 'net::stubbles::util::log::stubBaseLogData');
        $binder = new stubBinder();
        stubRegistry::set(stubBinder::REGISTRY_KEY, $binder);
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
     * assure that disabling logging works correct
     *
     * @test
     */
    public function loggingDisabled()
    {
        $abstractExceptionHandler = $this->getMock('stubAbstractExceptionHandler', array('fillResponse', 'log'));
        $abstractExceptionHandler->expects($this->never())->method('log');
        $abstractExceptionHandler->setLogging(false);
        $abstractExceptionHandler->handleException(new Exception());
    }

    /**
     * assure that the exception is logged
     *
     * @test
     */
    public function handleException()
    {
        $logger      = stubLogger::getInstance(__CLASS__);
        $logAppender = new stubMemoryLogAppender();
        $logger->addLogAppender($logAppender);
        $this->abstractExceptionHandler->handleException(new Exception('exception message'));
        $line = __LINE__ - 1;
        $logData = $logAppender->getLogData();
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
        stubLogger::destroyInstance(__CLASS__);
    }

    /**
     * assure that the exception is logged
     *
     * @test
     */
    public function handleChainedException()
    {
        $logger      = stubLogger::getInstance(__CLASS__);
        $logAppender = new stubMemoryLogAppender();
        $logger->addLogAppender($logAppender);
        $exception = new TestAbstractExceptionHandlerException('chained exception', new Exception('exception message'));
        $line = __LINE__ - 1;
        $this->abstractExceptionHandler->setLogTarget('foo');
        $this->abstractExceptionHandler->setLogLevel(stubLogger::LEVEL_DEBUG);
        $this->abstractExceptionHandler->handleException($exception);
        $logData = $logAppender->getLogData();
        $this->assertEquals(1, count($logData));
        $this->assertEquals(1, count($logData['foo']));
        $this->assertEquals('foo', $logData['foo'][0]->getTarget());
        $this->assertEquals(stubLogger::LEVEL_DEBUG, $logData['foo'][0]->getLevel());
        $logDataContents = explode(stubLogData::SEPERATOR, $logData['foo'][0]->get());
        $this->assertEquals('net::stubbles::lang::errorhandler::test::TestAbstractExceptionHandlerException', $logDataContents[1]);
        $this->assertEquals('chained exception', $logDataContents[2]);
        $this->assertEquals(__FILE__, $logDataContents[3]);
        $this->assertEquals($line, $logDataContents[4]);
        $this->assertEquals('Exception', $logDataContents[5]);
        $this->assertEquals('exception message', $logDataContents[6]);
        $this->assertEquals(__FILE__, $logDataContents[7]);
        $this->assertEquals($line, $logDataContents[8]);
        stubLogger::destroyInstance(__CLASS__);
    }

    /**
     * assure that the exception is logged
     *
     * @test
     */
    public function handleChainedExceptionWithoutChainedException()
    {
        $logger      = stubLogger::getInstance(__CLASS__);
        $logAppender = new stubMemoryLogAppender();
        $logger->addLogAppender($logAppender);
        $exception = new TestAbstractExceptionHandlerException('chained exception');
        $line = __LINE__ - 1;
        $this->abstractExceptionHandler->setLogTarget('foo');
        $this->abstractExceptionHandler->setLogLevel(stubLogger::LEVEL_DEBUG);
        $this->abstractExceptionHandler->handleException($exception);
        $logData = $logAppender->getLogData();
        $this->assertEquals(1, count($logData));
        $this->assertEquals(1, count($logData['foo']));
        $this->assertEquals('foo', $logData['foo'][0]->getTarget());
        $this->assertEquals(stubLogger::LEVEL_DEBUG, $logData['foo'][0]->getLevel());
        $logDataContents = explode(stubLogData::SEPERATOR, $logData['foo'][0]->get());
        $this->assertEquals('net::stubbles::lang::errorhandler::test::TestAbstractExceptionHandlerException', $logDataContents[1]);
        $this->assertEquals('chained exception', $logDataContents[2]);
        $this->assertEquals(__FILE__, $logDataContents[3]);
        $this->assertEquals($line, $logDataContents[4]);
        $this->assertEquals('', $logDataContents[5]);
        $this->assertEquals('', $logDataContents[6]);
        $this->assertEquals('', $logDataContents[7]);
        $this->assertEquals('', $logDataContents[8]);
        stubLogger::destroyInstance(__CLASS__);
    }
}
?>