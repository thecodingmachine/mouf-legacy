<?php
/**
 * Integration test for variant manager.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test_integration
 */
stubClassLoader::load('net::stubbles::websites::variantmanager::stubVariantXJConfFactory');
/**
 * Integration test for variant manager.
 *
 * @package     stubbles
 * @subpackage  test_integration
 * @group       integration
 */
class VariantManagerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * assure that creating the variant map works correct
     *
     * @test
     */
    public function xjConfVariantFactory()
    {
        $variantFactory = new stubVariantXJConfFactory();
        $variantsMap    = $variantFactory->getVariantsMap();
        $this->assertType('stubVariantsMap', $variantsMap);
        $this->assertEquals(array('request', 'random1', 'lead', 'random2'), $variantsMap->getVariantNames());
        $children = $variantsMap->getRootVariant()->getChildren();
        $this->assertEquals(3, count($children));
        $this->assertType('stubRequestParamVariant', $children['request']);
        $this->assertEquals(0, count($children['request']->getChildren()));
        $this->assertType('stubRandomVariant', $children['random1']);
        $childs = $children['random1']->getChildren();
        $this->assertEquals(1, count($childs));
        $this->assertType('stubLeadVariant', $childs['lead']);
        $this->assertEquals(0, count($childs['lead']->getChildren()));
        $this->assertType('stubRandomVariant', $children['random2']);
        $this->assertEquals(0, count($children['random2']->getChildren()));
        
        // cached
        $variantFactory = new stubVariantXJConfFactory();
        $variantsMap    = $variantFactory->getVariantsMap();
        $this->assertType('stubVariantsMap', $variantsMap);
        $this->assertEquals(array('request', 'random1', 'lead', 'random2'), $variantsMap->getVariantNames());
        $children = $variantsMap->getRootVariant()->getChildren();
        $this->assertEquals(3, count($children));
        $this->assertType('stubRequestParamVariant', $children['request']);
        $this->assertEquals(0, count($children['request']->getChildren()));
        $this->assertType('stubRandomVariant', $children['random1']);
        $childs = $children['random1']->getChildren();
        $this->assertEquals(1, count($childs));
        $this->assertType('stubLeadVariant', $childs['lead']);
        $this->assertEquals(0, count($childs['lead']->getChildren()));
        $this->assertType('stubRandomVariant', $children['random2']);
        $this->assertEquals(0, count($children['random2']->getChildren()));
    }
}
?>