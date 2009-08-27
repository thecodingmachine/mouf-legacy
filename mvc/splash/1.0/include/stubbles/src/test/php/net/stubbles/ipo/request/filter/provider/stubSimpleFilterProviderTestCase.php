<?php
/**
 * Tests for net::stubbles::ipo::request::filter::provider::stubSimpleFilterProvider.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter_provider_test
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::provider::stubSimpleFilterProvider');
/**
 * Tests for net::stubbles::ipo::request::filter::provider::stubSimpleFilterProvider.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter_provider_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_filter
 */
class stubSimpleFilterProviderTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * a mock to be used for the minimum validator
     *
     * @var  stubSimpleFilterProvider
     */
    protected $simpleFilterProvider;

    /**
     * create test environment
     */
    public function setUp()
    {
        $this->simpleFilterProvider = new stubSimpleFilterProvider(array('test', 'dummy'), 'org::stubbles::test::FilterWithConstArgs');
    }

    /**
     * assert that provider is only responsible for providing a mail filter
     *
     * @test
     */
    public function responsibility()
    {
        $this->assertTrue($this->simpleFilterProvider->isResponsible('test'));
        $this->assertTrue($this->simpleFilterProvider->isResponsible('dummy'));
        $this->assertFalse($this->simpleFilterProvider->isResponsible('string'));
    }

    /**
     * tries to create a filter instance with the required value error factory
     *
     * @test
     */
    public function getFilterWithConstArgs()
    {
        $mockValueErrorFactory = $this->getMock('stubRequestValueErrorFactory');
        $filter = $this->simpleFilterProvider->getFilter(array($mockValueErrorFactory));
        $this->assertType('FilterWithConstArgs', $filter);
        $this->assertSame($mockValueErrorFactory, $filter->getRveFactory());
    }

    /**
     * tries to create a filter instance
     *
     * @test
     */
    public function getFilterWithoutConstArgs()
    {
        $simpleFilterProvider = new stubSimpleFilterProvider(array('dummy'), 'org::stubbles::test::FilterWithoutConstArgs');
        $filter = $simpleFilterProvider->getFilter();
        $this->assertType('FilterWithoutConstArgs', $filter);
    }
}
?>