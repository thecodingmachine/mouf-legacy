<?php
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubHTTPURLFilterAnnotation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::annotations::stubHTTPURLFilterAnnotation');
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubHTTPURLFilterAnnotation.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_broker
 */
class stubHTTPURLFilterAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubHTTPURLFilterAnnotation
     */
    protected $httpURLFilterAnnotation;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->httpURLFilterAnnotation = new stubHTTPURLFilterAnnotation();
        $this->httpURLFilterAnnotation->setRequired(false);
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function instance()
    {
        $httpURLFilter = $this->httpURLFilterAnnotation->getFilter();
        $this->assertType('stubHTTPURLFilter', $httpURLFilter);
        $this->assertFalse($httpURLFilter->isDNSCheckEnabled());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function withDNSCheckEnabled()
    {
        $this->httpURLFilterAnnotation->setCheckDNS(true);
        $httpURLFilter = $this->httpURLFilterAnnotation->getFilter();
        $this->assertType('stubHTTPURLFilter', $httpURLFilter);
        $this->assertTrue($httpURLFilter->isDNSCheckEnabled());
    }
}
?>