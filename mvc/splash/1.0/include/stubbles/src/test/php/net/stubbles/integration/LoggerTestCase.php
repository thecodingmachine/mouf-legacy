<?php
/**
 * Integration test for logger.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test_integration
 */
stubClassLoader::load('net::stubbles::util::log::stubLoggerXJConfInitializer');
/**
 * Integration test for logger.
 *
 * @package     stubbles
 * @subpackage  test_integration
 * @group       integration
 */
class LoggerTestCase extends PHPUnit_Framework_TestCase
{
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
     * helper method
     */
    protected function initFactory()
    {
        $loggerXJConfFactory = new stubLoggerXJConfInitializer();
        $loggerXJConfFactory->init();
    }

    /**
     * assure that creating the logger instances works correct
     *
     * @test
     */
    public function loggerXJConfFactory()
    {
        $this->initFactory();
        $this->assertEquals(array(stubLogger::DEFAULT_ID), stubLogger::getInstanceList());
        $logAppenders = stubLogger::getInstance()->getLogAppenders();
        $this->assertEquals(2, count($logAppenders));
        $this->assertType('stubFileLogAppender', $logAppenders[0]);
        $this->assertEquals(stubConfig::getLogPath() . '/{Y}/{M}', $logAppenders[0]->getLogDir());
        $this->assertEquals(0777, $logAppenders[0]->getMode());
        $this->assertType('stubMemoryLogAppender', $logAppenders[1]);
        
        $this->tearDown();
        // cached
        $this->initFactory();
        $this->assertEquals(array(stubLogger::DEFAULT_ID), stubLogger::getInstanceList());
        $logAppenders = stubLogger::getInstance()->getLogAppenders();
        $this->assertEquals(2, count($logAppenders));
        $this->assertType('stubFileLogAppender', $logAppenders[0]);
        $this->assertEquals(stubConfig::getLogPath() . '/{Y}/{M}', $logAppenders[0]->getLogDir());
        $this->assertEquals(0777, $logAppenders[0]->getMode());
        $this->assertType('stubMemoryLogAppender', $logAppenders[1]);
    }
}
?>