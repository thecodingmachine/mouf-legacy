<?php
/**
 * Tests for net::stubbles::ipo::request::filter::stubFloatFilter.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter_test
 * @version     $Id: stubFloatFilterTestCase.php 1903 2008-10-24 21:26:17Z mikey $
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::stubFloatFilter');
/**
 * Tests for net::stubbles::ipo::request::filter::stubFloatFilter.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_filter
 */
class stubFloatFilterTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * set up test environment
     */
    public function setUp()
    {
        stubRegistry::setConfig(stubFloatFilter::DECIMALS_REGISTRY_KEY, 3);
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        stubRegistry::removeConfig(stubFloatFilter::DECIMALS_REGISTRY_KEY);
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function value()
    {
        $floatFilter = new stubFloatFilter();
        $this->assertEquals(8453, $floatFilter->execute('8.4533'));
        $this->assertEquals(8453, $floatFilter->execute('8.4538'));
        $this->assertEquals(8450, $floatFilter->execute('8.45'));
        $this->assertEquals(8000, $floatFilter->execute('8'));
        $this->assertEquals(8453, $floatFilter->execute(8.4533));
        $this->assertEquals(8453, $floatFilter->execute(8.4538));
        $this->assertEquals(8450, $floatFilter->execute(8.45));
        $this->assertEquals(8000, $floatFilter->execute(8));
    }

    /**
     * assure that 0 is returned when value not set or empty when no value
     * is required
     *
     * @test
     */
    public function unsetOrOtherValues()
    {
        $floatFilter = new stubFloatFilter();
        $this->assertNull($floatFilter->execute(null));
        $this->assertEquals(0, $floatFilter->execute(''));
        $this->assertEquals(1000, $floatFilter->execute(true));
        $this->assertEquals(0, $floatFilter->execute(false));
    }

    /**
     * assure that the correct value depending on $decimal_places is returned
     *
     * @test
     */
    public function float()
    {
        stubRegistry::setConfig(stubFloatFilter::DECIMALS_REGISTRY_KEY, 2);
        $floatFilter = new stubFloatFilter();
        $this->assertEquals(156, $floatFilter->execute('1.564'));
    }

    /**
     * assure that the correct value depending on $decimal_places is returned
     *
     * @test
     */
    public function decimals0()
    {
        stubRegistry::setConfig(stubFloatFilter::DECIMALS_REGISTRY_KEY, 0);
        $floatFilter = new stubFloatFilter();
        $this->assertEquals(1.564, $floatFilter->execute('1.564'));
    }

    /**
     * assure that the correct value depending on $decimal_places is returned
     *
     * @test
     */
    public function registryKeyMissing()
    {
        stubRegistry::removeConfig(stubFloatFilter::DECIMALS_REGISTRY_KEY);
        $floatFilter = new stubFloatFilter();
        $this->assertEquals(1.564, $floatFilter->execute('1.564'));
    }
}
?>