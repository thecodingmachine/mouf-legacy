<?php
/**
 * Test for net::stubbles::util:log::stubMemoryLogAppender.
 *
 * @author      Richard Sternagel <richard.sternagel@1und1.de>
 * @package     stubbles
 * @subpackage  util_log_test
 */
stubClassLoader::load('net::stubbles::util::log::stubMemoryLogAppender');
/**
 * Test for net::stubbles::util:log::stubMemoryLogAppender.
 *
 * @package     stubbles
 * @subpackage  util_log_test
 * @group       util_log
 */
class stubMemoryLogAppenderTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubMemoryLogAppender
     */
    protected $memoryLogAppender;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->memoryLogAppender = new stubMemoryLogAppender();
    }

    /**
     * assure that appended data is stored in array (one record)
     *
     * @test
     */
    public function appendWithOneDataRecord()
    {
        $mockLogData  = $this->getMock('stubLogData');
        $mockLogData->expects($this->any())->method('getTarget')->will($this->returnValue('myTestTarget'));
        $this->memoryLogAppender->append($mockLogData);
        $logData = $this->memoryLogAppender->getLogData();
        $this->assertEquals(1, count($logData));
        $this->assertTrue(isset($logData['myTestTarget']));
        $this->assertEquals(1, count($logData['myTestTarget']));
        $this->assertSame($mockLogData, $logData['myTestTarget'][0]);
    }

    /**
     * assure that appended data is stored in array (more than one records)
     *
     * @test
     */
    public function appendWithMoreThanOneDataRecord()
    {
        $mockLogData = $this->getMock('stubLogData');
        $mockLogData->expects($this->any())->method('getTarget')->will($this->returnValue('myTestTarget'));
        $this->memoryLogAppender->append($mockLogData);
        $this->memoryLogAppender->append($mockLogData);
        $logData = $this->memoryLogAppender->getLogData();
        $this->assertEquals(1, count($logData));
        $this->assertTrue(isset($logData['myTestTarget']));
        $this->assertEquals(2, count($logData['myTestTarget']));
        $this->assertSame($mockLogData, $logData['myTestTarget'][0]);
        $this->assertSame($mockLogData, $logData['myTestTarget'][1]);
    }

    /**
     * assure that appended data is stored in array (more than one records)
     *
     * @test
     */
    public function appendWithMoreThanOneTargets()
    {
        $mockLogData1 = $this->getMock('stubLogData');
        $mockLogData1->expects($this->any())->method('getTarget')->will($this->returnValue('myTestTarget1'));
        $this->memoryLogAppender->append($mockLogData1);
        $mockLogData2 = $this->getMock('stubLogData');
        $mockLogData2->expects($this->any())->method('getTarget')->will($this->returnValue('myTestTarget2'));
        $this->memoryLogAppender->append($mockLogData2);
        $logData = $this->memoryLogAppender->getLogData();
        $this->assertEquals(2, count($logData));
        $this->assertTrue(isset($logData['myTestTarget1']));
        $this->assertEquals(1, count($logData['myTestTarget1']));
        $this->assertSame($mockLogData1, $logData['myTestTarget1'][0]);
        $this->assertTrue(isset($logData['myTestTarget2']));
        $this->assertEquals(1, count($logData['myTestTarget2']));
        $this->assertSame($mockLogData2, $logData['myTestTarget2'][0]);
    }
}
?>