<?php
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubMailFilterAnnotation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::annotations::stubMailFilterAnnotation');
/**
 * Tests for net::stubbles::ipo::request::broker::annotations::stubMailFilterAnnotation.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_broker
 */
class stubMailFilterAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * test that the correct filter is created
     *
     * @test
     */
    public function instance()
    {
        $mailFilterAnnotation = new stubMailFilterAnnotation();
        $mailFilterAnnotation->setRequired(false);
        $mailFilter           = $mailFilterAnnotation->getFilter();
        $this->assertType('stubMailFilter', $mailFilter);
    }
}
?>