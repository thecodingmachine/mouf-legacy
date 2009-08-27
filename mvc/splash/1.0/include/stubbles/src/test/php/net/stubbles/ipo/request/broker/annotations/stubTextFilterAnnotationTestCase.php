<?php
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubTextFilterAnnotation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::annotations::stubTextFilterAnnotation');
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubTextFilterAnnotation.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_broker
 */
class stubTextFilterAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubTextFilterAnnotation
     */
    protected $textFilterAnnotation;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->textFilterAnnotation = new stubTextFilterAnnotation();
        $this->textFilterAnnotation->setRequired(false);
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function instance()
    {
        $textFilter = $this->textFilterAnnotation->getFilter();
        $this->assertType('stubTextFilter', $textFilter);
        $this->assertEquals(array(), $textFilter->getAllowedTags());
    }

    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function allowedTags()
    {
        $this->textFilterAnnotation->setAllowedTags('b, i, a');
        $textFilter = $this->textFilterAnnotation->getFilter();
        $this->assertType('stubTextFilter', $textFilter);
        $this->assertEquals(array('b', 'i', 'a'), $textFilter->getAllowedTags());
    }
}
?>