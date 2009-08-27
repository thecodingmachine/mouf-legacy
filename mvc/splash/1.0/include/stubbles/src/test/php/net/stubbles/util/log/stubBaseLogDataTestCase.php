<?php
/**
 * Test for net::stubbles::util::log::stubBaseLogData.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log_test
 * @version     $Id: stubBaseLogDataTestCase.php 1928 2008-11-13 21:21:01Z mikey $
 */
stubClassLoader::load('net::stubbles::util::log::stubBaseLogData');
/**
 * Test for net::stubbles::util::log::stubBaseLogData.
 *
 * @package     stubbles
 * @subpackage  util_log_test
 * @group       util_log
 */
class stubBaseLogDataTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * assure that data is handles as expected
     *
     * @test
     */
    public function withoutSession()
    {
        $baseLogData = new stubBaseLogData('bar', stubLogger::LEVEL_INFO);
        $this->assertEquals(stubLogger::LEVEL_INFO, $baseLogData->getLevel());
        $this->assertEquals('bar', $baseLogData->getTarget());
        $this->assertSame($baseLogData, $baseLogData->addData("ba\r\nz" . stubLogData::SEPERATOR . 'vvv'));
        $data = explode(stubLogData::SEPERATOR, $baseLogData->get());
        $this->assertEquals('ba<nl>zvvv', $data[1]);
    }

    /**
     * assure that data is handles as expected
     *
     * @test
     */
    public function withSession()
    {
        $mockSession = $this->getMock('stubSession');
        $mockSession->expects($this->any())->method('getId')->will($this->returnValue('foo'));
        $baseLogData = new stubBaseLogData('bar');
        $baseLogData->setSession($mockSession);
        $this->assertEquals(stubLogger::LEVEL_INFO, $baseLogData->getLevel());
        $this->assertEquals('bar', $baseLogData->getTarget());
        $this->assertSame($baseLogData, $baseLogData->addData("ba\r\nz" . stubLogData::SEPERATOR . 'vvv'));
        $data = explode(stubLogData::SEPERATOR, $baseLogData->get());
        $this->assertEquals('foo', $data[1]);
        $this->assertEquals('ba<nl>zvvv', $data[2]);
    }
}
?>