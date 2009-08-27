<?php
/**
 * Tests for net::stubbles::util::log::stubLoggerXJConfInitializer.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log_test
 */
stubClassLoader::load('net::stubbles::util::log::stubLoggerXJConfInitializer');
PHPUnit_Framework_MockObject_Mock::generate('stubLogAppender', array(), 'MockstubLogAppender1');
PHPUnit_Framework_MockObject_Mock::generate('stubLogAppender', array(), 'MockstubLogAppender2');
PHPUnit_Framework_MockObject_Mock::generate('stubLogAppender', array(), 'MockstubLogAppender3');
class MockstubLogAppender4 extends MockstubLogAppender3
{
    public $config;
    
    public function setConfig(array $config)
    {
        $this->config = $config;
    }
}
/**
 * Tests for net::stubbles::util::log::stubLoggerXJConfInitializer.
 *
 * @package     stubbles
 * @subpackage  util_log_test
 * @group       util_log
 */
class stubLoggerXJConfInitializerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubLoggerXJConfInitializer
     */
    protected $loggerXJConfInitializer;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->loggerXJConfInitializer = new stubLoggerXJConfInitializer();
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
     * test descriptor
     *
     * @test
     */
    public function descriptor()
    {
        $this->assertEquals('logging', $this->loggerXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_CONFIG));
        $this->assertEquals('logging', $this->loggerXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_DEFINITION));
        $this->assertEquals('logging', $this->loggerXJConfInitializer->getDescriptor('foo'));
        $this->loggerXJConfInitializer->setDescriptor('test');
        $this->assertEquals('test', $this->loggerXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_CONFIG));
        $this->assertEquals('logging', $this->loggerXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_DEFINITION));
        $this->assertEquals('logging', $this->loggerXJConfInitializer->getDescriptor('foo'));
    }

    /**
     * check that the cache data is correct
     *
     * @test
     */
    public function getCacheData()
    {
        $logger1 = stubLogger::getInstance('default', stubLogger::LEVEL_INFO);
        $mockLogAppender1 = new MockstubLogAppender1();
        $mockLogAppender1->expects($this->any())->method('getClassName')->will($this->returnValue('MockstubLogAppender1'));
        $mockLogAppender1->expects($this->any())->method('getConfig')->will($this->returnValue(array('foo' => 'bar')));
        $logger1->addLogAppender($mockLogAppender1);
        $logger2 = stubLogger::getInstance('other', stubLogger::LEVEL_ALL);
        $mockLogAppender2 = new MockstubLogAppender2();
        $mockLogAppender2->expects($this->any())->method('getClassName')->will($this->returnValue('MockstubLogAppender2'));
        $mockLogAppender2->expects($this->any())->method('getConfig')->will($this->returnValue(array('bar' => 'baz')));
        $logger2->addLogAppender($mockLogAppender2);
        $mockLogAppender3 = new MockstubLogAppender3();
        $mockLogAppender3->expects($this->any())->method('getClassName')->will($this->returnValue('MockstubLogAppender3'));
        $mockLogAppender3->expects($this->any())->method('getConfig')->will($this->returnValue(array('foo' => 'baz')));
        $logger2->addLogAppender($mockLogAppender3);
        $cacheData = $this->loggerXJConfInitializer->getCacheData();
        $this->assertEquals(array('default' => array('level'    => stubLogger::LEVEL_INFO,
                                                     'appender' => array('MockstubLogAppender1' => array('foo' => 'bar')),
                                               ),
                                  'other'   => array('level'    => stubLogger::LEVEL_ALL,
                                                     'appender' => array('MockstubLogAppender2' => array('bar' => 'baz'),
                                                                         'MockstubLogAppender3' => array('foo' => 'baz')
                                                                   )
                                               )
                            ),
                            $cacheData
        );
    }

    /**
     * check that cache data is used correct
     *
     * @test
     */
    public function setCacheData()
    {
        $cacheData = array('default' => array('level'    => stubLogger::LEVEL_INFO,
                                              'appender' => array('MockstubLogAppender1' => array('foo' => 'bar')),
                                        ),
                           'other'   => array('level'    => stubLogger::LEVEL_ALL,
                                              'appender' => array('MockstubLogAppender2' => array('bar' => 'baz'),
                                                                  'MockstubLogAppender4' => array('foo' => 'baz')
                                                            )
                                        )
                     );
        $this->loggerXJConfInitializer->setCacheData($cacheData);
        $this->assertEquals(stubLogger::getInstanceList(), array('default', 'other'));
        $logger1 = stubLogger::getInstance('default');
        $this->assertEquals($logger1->getLevel(), stubLogger::LEVEL_INFO);
        $logAppenders = $logger1->getLogAppenders();
        $this->assertType('MockstubLogAppender1', $logAppenders[0]);
        $logger2 = stubLogger::getInstance('other');
        $this->assertEquals($logger2->getLevel(), stubLogger::LEVEL_ALL);
        $logAppenders = $logger2->getLogAppenders();
        $this->assertType('MockstubLogAppender2', $logAppenders[0]);
        $this->assertType('MockstubLogAppender4', $logAppenders[1]);
        $this->assertEquals(array('foo' => 'baz'), $logAppenders[1]->config);
    }
}
?>