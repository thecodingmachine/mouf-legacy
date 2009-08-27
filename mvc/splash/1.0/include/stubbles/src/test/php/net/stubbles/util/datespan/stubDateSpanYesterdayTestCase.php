<?php
/**
 * Tests for net::stubbles::util::datespan::stubDateSpanYesterday.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_datespan_test
 */
stubClassLoader::load('net::stubbles::util::datespan::stubDateSpanYesterday');
/**
 * Tests for net::stubbles::util::datespan::stubDateSpanYesterday.
 *
 * @package     stubbles
 * @subpackage  util_datespan_test
 * @group       util_datespan
 */
class stubDateSpanYesterdayTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * test that the datespans detects correctly whether it starts in the future or not
     *
     * @test
     */
    public function isFuture()
    {
        $dateSpanYesterday = new stubDateSpanYesterday();
        $this->assertFalse($dateSpanYesterday->isFuture());
    }
}
?>