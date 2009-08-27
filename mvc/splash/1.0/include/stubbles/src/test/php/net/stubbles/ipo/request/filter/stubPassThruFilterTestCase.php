<?php
/**
 * Tests for net::stubbles::ipo::request::filter::stubPassThruFilter.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter_test
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::stubPassThruFilter');
/**
 * Tests for net::stubbles::ipo::request::filter::stubPassThruFilter.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_filter
 */
class stubPassThruFilterTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubPassThruFilter
     */
    protected $passthruFilter;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->passthruFilter = new stubPassThruFilter($this->mockRequestValueErrorFactory);
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function passThurpassesThru()
    {
        $this->assertEquals('', $this->passthruFilter->execute(''));
        $this->assertEquals('foo', $this->passthruFilter->execute('foo'));
        $this->assertNull($this->passthruFilter->execute(null));
    }
}
?>