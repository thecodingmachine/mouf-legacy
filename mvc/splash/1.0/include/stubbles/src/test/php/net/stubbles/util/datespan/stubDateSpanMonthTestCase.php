<?php
/**
 * Tests for net::stubbles::util::datespan::stubDateSpanMonth.
 *
 * @author      Frank Kleine <frank.kleine@1und1.de>
 * @package     stubbles
 * @subpackage  util_datespan_test
 */
stubClassLoader::load('net::stubbles::util::datespan::stubDateSpanMonth');
/**
 * Tests for net::stubbles::util::datespan::stubDateSpanMonth.
 *
 * @package     stubbles
 * @subpackage  util_datespan_test
 * @group       util_datespan
 */
class stubDateSpanMonthTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * test that toString returns a correct representation
     *
     * @test
     */
    public function display()
    {
        $dateSpanMonth = new stubDateSpanMonth(2007, 4);
        $this->assertEquals('01.04.2007 bis 30.04.2007', $dateSpanMonth->toString());
        $dateSpanMonth = new stubDateSpanMonth(null, 4);
        $this->assertEquals('01.04.' . date('Y') . ' bis 30.04.' . date('Y'), $dateSpanMonth->toString());
    }

    /**
     * test that the datespans are returnred correctly
     *
     * @test
     */
    public function getDateSpans()
    {
        $dateSpanMonth = new stubDateSpanMonth(2007, 4);
        $this->assertEquals(30, count($dateSpanMonth->getDateSpans()));
        $dateSpanMonth = new stubDateSpanMonth(2007, 3);
        $this->assertEquals(31, count($dateSpanMonth->getDateSpans()));
        $dateSpanMonth = new stubDateSpanMonth(2007, 2);
        $this->assertEquals(28, count($dateSpanMonth->getDateSpans()));
        $dateSpanMonth = new stubDateSpanMonth(2008, 2);
        $this->assertEquals(29, count($dateSpanMonth->getDateSpans()));
    }

    /**
     * test that the datespans detects correctly whether it starts in the future or not
     *
     * @test
     */
    public function isFuture()
    {
        $dateSpanMonth = new stubDateSpanMonth(date('Y') + 1, 7);
        $this->assertTrue($dateSpanMonth->isFuture());
        $dateSpanMonth = new stubDateSpanMonth(date('Y') - 1, 7);
        $this->assertFalse($dateSpanMonth->isFuture());
        $dateSpanMonth = new stubDateSpanMonth(date('Y'), date('m'));
        $this->assertFalse($dateSpanMonth->isFuture());
    }
}
?>