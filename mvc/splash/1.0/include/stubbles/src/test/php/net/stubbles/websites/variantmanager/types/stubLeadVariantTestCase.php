<?php
/**
 * Test for net::stubbles::websites::variantmanager::types::stubLeadVariant.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager_test
 */
stubClassLoader::load('net::stubbles::websites::variantmanager::types::stubLeadVariant');
/**
 * Test for net::stubbles::websites::variantmanager::types::stubLeadVariant.
 *
 * @package     stubbles
 * @subpackage  websites_variantmanager_test
 * @group       websites
 * @group       websites_variantmanager
 */
class stubLeadVariantTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * test that a lead variant is always valid but never enforcing
     *
     * @test
     */
    public function valid()
    {
        $leadVariant = new stubLeadVariant();
        $session     = $this->getMock('stubSession');
        $request     = $this->getMock('stubRequest');
        $this->assertFalse($leadVariant->isEnforcing($session, $request));
        $this->assertTrue($leadVariant->isValid($session, $request));
    }
}
?>