<?php
/**
 * Tests for net::stubbles::websites::variantmanager::stubVariantXJConfFactory
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager_test
 */
stubClassLoader::load('net::stubbles::websites::variantmanager::stubVariantXJConfFactory',
                      'net::stubbles::websites::variantmanager::types::stubLeadVariant'
);
class TeststubVariantXJConfFactory extends stubVariantXJConfFactory
{
    public function __construct()
    {
        // nothing to do, just overwrite the parent constructor
    }
    
    public function setVariantsMap(stubVariantsMap $variantsMap)
    {
        $this->variantsMap = $variantsMap;
    }
}
/**
 * Tests for net::stubbles::websites::variantmanager::stubVariantXJConfFactory
 *
 * @package     stubbles
 * @subpackage  websites_variantmanager_test
 * @group       websites
 * @group       websites_variantmanager
 */
class stubVariantXJConfFactoryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  TeststubVariantXJConfFactory
     */
    protected $variantXJConfFactory;
    /**
     * root variant
     *
     * @var  stubRootVariant
     */
    protected $rootVariant;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->variantXJConfFactory = new TeststubVariantXJConfFactory();
        $v1 = new stubLeadVariant();
        $v1->setName('v1');
        $v2 = new stubLeadVariant();
        $v2->setName('v2');
        $v3 = new stubLeadVariant();
        $v3->setName('v3');
        $this->rootVariant = new stubRootVariant();
        $v2->addChild($v3);
        $v1->addChild($v2);
        $this->rootVariant->addChild($v1);
    }

    /**
     * test descriptor
     *
     * @test
     */
    public function descriptor()
    {
        $this->assertEquals('variantmanager', $this->variantXJConfFactory->getDescriptor(stubXJConfInitializer::DESCRIPTOR_CONFIG));
        $this->assertEquals('variantmanager', $this->variantXJConfFactory->getDescriptor(stubXJConfInitializer::DESCRIPTOR_DEFINITION));
    }

    /**
     * check that the cache data is correct
     *
     * @test
     */
    public function getCacheData()
    {
        $variantsMap = new stubVariantsMap($this->rootVariant);
        $variantsMap->setName('foo');
        $variantsMap->setUsePersistence(true);
        $this->variantXJConfFactory->setVariantsMap($variantsMap);
        $cacheData = $this->variantXJConfFactory->getCacheData();
        $this->assertEquals('foo', $cacheData['name']);
        $this->assertTrue($cacheData['usePersistence']);
        $this->assertType('stubSerializedObject', $cacheData['rootVariant']);
    }

    /**
     * check that cache data is used correct
     *
     * @test
     */
    public function setCacheData()
    {
        $cacheData = array('name'           => 'foo',
                           'usePersistence' => true,
                           'rootVariant'    => $this->rootVariant->getSerialized()
                     );
        $this->variantXJConfFactory->setCacheData($cacheData);
        $variantsMap = $this->variantXJConfFactory->getVariantsMap();
        $this->assertEquals('foo', $variantsMap->getName());
        $this->assertTrue($variantsMap->shouldUsePersistence());
        $this->assertEquals(array('v1', 'v2', 'v3'), $variantsMap->getVariantNames());
    }
}
?>