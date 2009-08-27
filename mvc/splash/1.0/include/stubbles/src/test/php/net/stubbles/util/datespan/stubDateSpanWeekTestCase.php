<?php
/**
 * Tests for net::stubbles::util::datespan::stubDateSpanWeek.
 *
 * @author      Frank Kleine <frank.kleine@1und1.de>
 * @package     stubbles
 * @subpackage  util_datespan_test
 */
stubClassLoader::load('net::stubbles::util::datespan::stubDateSpanWeek');
/**
 * Tests for net::stubbles::util::datespan::stubDateSpanWeek.
 *
 * @package     stubbles
 * @subpackage  util_datespan_test
 * @group       util_datespan
 */
class stubDateSpanWeekTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * test that toString returns a correct representation
     *
     * @test
     */
    public function display()
    {
        $dateSpanWeek = new stubDateSpanWeek('2007-04-02');
        $this->assertEquals('02.04.2007 bis 08.04.2007', $dateSpanWeek->toString());
    }

    /**
     * test that the datespans are returnred correctly
     *
     * @test
     */
    public function getDateSpans()
    {
        $dateSpanWeek = new stubDateSpanWeek('2007-05-14');
        $this->assertEquals(7, count($dateSpanWeek->getDateSpans()));
    }

    /**
     * test that the datespans detects correctly whether it starts in the future or not
     *
     * @test
     */
    public function isFuture()
    {
        $dateSpanWeek = new stubDateSpanWeek('tomorrow');
        $this->assertTrue($dateSpanWeek->isFuture());
        $dateSpanWeek = new stubDateSpanWeek('yesterday');
        $this->assertFalse($dateSpanWeek->isFuture());
        $dateSpanWeek = new stubDateSpanWeek('now');
        $this->assertFalse($dateSpanWeek->isFuture());
    }
}
?>