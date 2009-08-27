<?php
/**
 * Tests for net::stubbles::websites::variantmanager::stubAbstractVariantFactory.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager_test
 */
stubClassLoader::load('net::stubbles::websites::variantmanager::stubAbstractVariantFactory',
                      'net::stubbles::websites::variantmanager::types::stubLeadVariant'
);
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  websites_variantmanager_test
 */
class TeststubAbstractVariantFactory extends stubAbstractVariantFactory
{
    /**
     * sets the variants map to be used
     *
     * @param  stubVariantsMap  $variantsMap
     */
    public function setVariantsMap(stubVariantsMap $variantsMap)
    {
        $this->variantsMap = $variantsMap;
    }
}
/**
 * Tests for net::stubbles::websites::variantmanager::stubAbstractVariantFactory.
 *
 * @package     stubbles
 * @subpackage  websites_variantmanager_test
 * @group       websites
 * @group       websites_variantmanager
 */
class stubAbstractVariantFactoryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  TeststubAbstractVariantFactory
     */
    protected $abstractVariantFactory;
    /**
     * root variant
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockVariantsMap;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->abstractVariantFactory = new TeststubAbstractVariantFactory();
        $this->mockVariantsMap        = $this->getMock('stubVariantsMap');
        $this->abstractVariantFactory->setVariantsMap($this->mockVariantsMap);
    }

    /**
     * call should just be redirected to variants map
     *
     * @test
     */
    public function getVariantNames()
    {
        $this->mockVariantsMap->expects($this->once())
                              ->method('getVariantNames')
                              ->will($this->returnValue(array('foo', 'foo:bar', 'foo:baz')));
        $this->assertEquals(array('foo', 'foo:bar', 'foo:baz'), $this->abstractVariantFactory->getVariantNames());
    }

    /**
     * call should just be redirected to variants map
     *
     * @test
     */
    public function getVariantByName()
    {
        $mockVariant = $this->getMock('stubVariant');
        $this->mockVariantsMap->expects($this->once())
                              ->method('getVariantByName')
                              ->with($this->equalTo('foo'))
                              ->will($this->returnValue($mockVariant));
        $this->assertSame($mockVariant, $this->abstractVariantFactory->getVariantByName('foo'));
    }

    /**
     * variant map should be returned as is
     *
     * @test
     */
    public function getVariantsMap()
    {
        $this->assertSame($this->mockVariantsMap, $this->abstractVariantFactory->getVariantsMap());
    }
}
?>