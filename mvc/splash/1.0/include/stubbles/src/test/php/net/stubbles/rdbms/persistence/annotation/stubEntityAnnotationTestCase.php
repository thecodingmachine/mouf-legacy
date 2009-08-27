<?php
/**
 * Test for net::stubbles::rdbms::persistence::annotation::stubEntityAnnotation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_annotation_test
 */
stubClassLoader::load('net::stubbles::rdbms::persistence::annotation::stubEntityAnnotation');
/**
 * Test for net::stubbles::rdbms::persistence::annotation::stubEntityAnnotation.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_annotation_test
 * @group       rdbms
 * @group       rdbms_persistence
 */
class stubEntityAnnotationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubEntityAnnotation
     */
    protected $entityAnnotation;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->entityAnnotation = new stubEntityAnnotation();
    }

    /**
     * name property handling
     *
     * @test
     */
    public function nameProperty()
    {
        $this->assertNull($this->entityAnnotation->getName());
        $this->entityAnnotation->setName('foo');
        $this->assertEquals('foo', $this->entityAnnotation->getName());
    }

    /**
     * defaultOrder property handling
     *
     * @test
     */
    public function defaultOrderProperty()
    {
        $this->assertFalse($this->entityAnnotation->hasDefaultOrder());
        $this->assertNull($this->entityAnnotation->getDefaultOrder());
        $this->entityAnnotation->setDefaultOrder('name ASC');
        $this->assertTrue($this->entityAnnotation->hasDefaultOrder());
        $this->assertEquals('name ASC', $this->entityAnnotation->getDefaultOrder());
    }

    /**
     * annotation target should be classes only
     *
     * @test
     */
    public function annotationTarget()
    {
        $this->assertEquals(stubAnnotation::TARGET_CLASS, $this->entityAnnotation->getAnnotationTarget());
    }
}
?>