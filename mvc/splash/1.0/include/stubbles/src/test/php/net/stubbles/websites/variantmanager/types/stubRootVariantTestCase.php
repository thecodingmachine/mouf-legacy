<?php
/**
 * Test for net::stubbles::websites::variantmanager::types::stubRootVariant.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager_test
 */
stubClassLoader::load('net::stubbles::websites::variantmanager::types::stubRootVariant');
/**
 * Test for net::stubbles::websites::variantmanager::types::stubRootVariant.
 *
 * @package     stubbles
 * @subpackage  websites_variantmanager_test
 * @group       websites
 * @group       websites_variantmanager
 */
class stubRootVariantTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubRootVariant
     */
    protected $rootVariant;
    
    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->rootVariant = new stubRootVariant();
    }
    
    /**
     * assure that the name of the root variant can not be changed
     *
     * @test
     */
    public function name()
    {
        $this->assertEquals('root', $this->rootVariant->getName());
        $this->rootVariant->setName('foo');
        $this->assertEquals('root', $this->rootVariant->getName());
    }
    
    /**
     * test that a root variant is always valid and always enforcing
     *
     * @test
     */
    public function valid()
    {
        $session = $this->getMock('stubSession');
        $request = $this->getMock('stubRequest');
        $this->assertTrue($this->rootVariant->isEnforcing($session, $request));
        $this->assertTrue($this->rootVariant->isValid($session, $request));
    }
}
?>