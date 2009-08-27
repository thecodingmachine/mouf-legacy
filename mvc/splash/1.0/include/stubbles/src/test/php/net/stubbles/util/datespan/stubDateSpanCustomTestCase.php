<?php
/**
 * Tests for net::stubbles::util::datespan::stubDateSpanCustom.
 *
 * @author      Frank Kleine <frank.kleine@1und1.de>
 * @package     stubbles
 * @subpackage  util_datespan_test
 */
stubClassLoader::load('net::stubbles::util::datespan::stubDateSpanCustom');
/**
 * Tests for net::stubbles::util::datespan::stubDateSpanCustom.
 *
 * @package     stubbles
 * @subpackage  util_datespan_test
 * @group       util_datespan
 */
class stubDateSpanCustomTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * test that toString returns a correct representation
     *
     * @test
     */
    public function display()
    {
        $dateSpanCustom = new stubDateSpanCustom('2006-04-04', '2006-04-20');
        $this->assertEquals('04.04.2006 bis 20.04.2006', $dateSpanCustom->toString());
    }

    /**
     * test that the datespans are returnred correctly
     *
     * @test
     */
    public function getDateSpans()
    {
        $dateSpanCustom = new stubDateSpanCustom('2007-05-14', '2007-05-27');
        $this->assertEquals(14, count($dateSpanCustom->getDateSpans()));
        $dateSpanCustom = new stubDateSpanCustom('2007-05-14', '2007-05-27', stubDateSpan::INTERVAL_WEEK);
        $this->assertEquals(2, count($dateSpanCustom->getDateSpans()));
    }

    /**
     * test that the datespans detects correctly whether it starts in the future or not
     *
     * @test
     */
    public function isFuture()
    {
        $dateSpanCustom = new stubDateSpanCustom('tomorrow', '+3 days');
        $this->assertTrue($dateSpanCustom->isFuture());
        $dateSpanCustom = new stubDateSpanCustom('yesterday', '+3 days');
        $this->assertFalse($dateSpanCustom->isFuture());
        $dateSpanCustom = new stubDateSpanCustom('-3 days', 'yesterday');
        $this->assertFalse($dateSpanCustom->isFuture());
    }

    /**
     * test that serializing and unserializing a datespan works as expected
     *
     * @test
     */
    public function serializing()
    {
        $dateSpanCustom = new stubDateSpanCustom('2007-05-14', '2007-05-27');
        $serialized = serialize($dateSpanCustom);
        $unserialized = unserialize($serialized);
        $this->assertEquals($dateSpanCustom->getStartDate()->format('Y-m-d'), $unserialized->getStartDate()->format('Y-m-d'));
        $this->assertEquals($dateSpanCustom->getEndDate()->format('Y-m-d'), $unserialized->getEndDate()->format('Y-m-d'));
    }
}
?>