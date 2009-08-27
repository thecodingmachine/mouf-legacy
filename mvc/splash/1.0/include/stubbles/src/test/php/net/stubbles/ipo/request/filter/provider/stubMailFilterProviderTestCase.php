<?php
/**
 * Tests for net::stubbles::ipo::request::filter::provider::stubMailFilterProvider.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter_provider_test
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::provider::stubMailFilterProvider');
/**
 * Tests for net::stubbles::ipo::request::filter::provider::stubMailFilterProvider.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter_provider_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_filter
 */
class stubMailFilterProviderTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * a mock to be used for the minimum validator
     *
     * @var  stubMailFilterProvider
     */
    protected $mailFilterProvider;

    /**
     * create test environment
     */
    public function setUp()
    {
        $this->mailFilterProvider = new stubMailFilterProvider();
    }

    /**
     * assert that provider is only responsible for providing a mail filter
     *
     * @test
     */
    public function responsibility()
    {
        $this->assertTrue($this->mailFilterProvider->isResponsible('mail'));
        $this->assertFalse($this->mailFilterProvider->isResponsible('http'));
        $this->assertFalse($this->mailFilterProvider->isResponsible('string'));
    }

    /**
     * tries to create a filter instance without the required value error factory
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function getFilterWithoutValueErrorFactory()
    {
        $mailFilter = $this->mailFilterProvider->getFilter();
    }

    /**
     * tries to create a filter instance without the required value error factory
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function getFilterWithIllegalInstance()
    {
        $mailFilter = $this->mailFilterProvider->getFilter(array(new stdClass()));
    }

    /**
     * tries to create a filter instance without the required value error factory
     *
     * @test
     */
    public function getFilter()
    {
        $mockValueErrorFactory = $this->getMock('stubRequestValueErrorFactory');
        $mailFilter1 = $this->mailFilterProvider->getFilter(array($mockValueErrorFactory));
        $this->assertType('stubMailFilter', $mailFilter1);
        $mailFilter2 = $this->mailFilterProvider->getFilter(array($mockValueErrorFactory));
        $this->assertNotSame($mailFilter1, $mailFilter2);
        $this->assertSame($mailFilter1->getMailValidator(), $mailFilter2->getMailValidator());
    }
}
?>