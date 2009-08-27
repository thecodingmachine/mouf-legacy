<?php
/**
 * Test for net::stubbles::websites::variantmanager::types::stubDummyVariant.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager_test
 */
stubClassLoader::load('net::stubbles::websites::variantmanager::types::stubDummyVariant');
/**
 * Test for net::stubbles::websites::variantmanager::types::stubDummyVariant.
 *
 * @package     stubbles
 * @subpackage  websites_variantmanager_test
 * @group       websites
 * @group       websites_variantmanager
 */
class stubDummyVariantTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * test that a dummy variant is never valid and never enforcing
     *
     * @test
     */
    public function valid()
    {
        $dummyVariant = new stubDummyVariant();
        $session      = $this->getMock('stubSession');
        $request      = $this->getMock('stubRequest');
        $this->assertFalse($dummyVariant->isEnforcing($session, $request));
        $this->assertFalse($dummyVariant->isValid($session, $request));
    }
}
?>