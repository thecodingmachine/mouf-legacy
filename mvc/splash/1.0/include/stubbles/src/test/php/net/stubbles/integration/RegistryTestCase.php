<?php
/**
 * Integration test for registry.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test_integration
 */
stubClassLoader::load('net::stubbles::lang::initializer::stubRegistryXJConfInitializer',
                      'net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::ipo::session::stubSession',
                      'net::stubbles::util::log::stubLogData'
);
/**
 * Integration test for registry.
 *
 * @package     stubbles
 * @subpackage  test_integration
 * @group       integrations
 */
class RegistryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * helper method
     */
    protected function initRegistry()
    {
        $registryInitializer = new stubRegistryXJConfInitializer();
        $registryInitializer->init();
    }

    /**
     * assure that loading the registry gives correct results
     *
     * @test
     */
    public function initializing()
    {
        $this->initRegistry();
        $this->assertEquals('en_EN', stubRegistry::getConfig('net.stubbles.language'));
        $this->assertEquals(4, stubRegistry::getConfig('net.stubbles.number.decimals'));
        $this->assertEquals('net::stubbles::util::log::stubBaseLogData', stubRegistry::getConfig(stubLogData::CLASS_REGISTRY_KEY));
        $this->assertEquals('net::stubbles::ipo::request::stubWebRequest', stubRegistry::getConfig(stubRequest::CLASS_REGISTRY_KEY));
        $this->assertEquals('net::stubbles::ipo::session::stubPHPSession', stubRegistry::getConfig(stubSession::CLASS_REGISTRY_KEY));
        
        foreach (stubRegistry::getConfigKeys() as $configKey) {
            stubRegistry::removeConfig($configKey);
        }
        
        // cached
        $this->initRegistry();
        $this->assertEquals('en_EN', stubRegistry::getConfig('net.stubbles.language'));
        $this->assertEquals(4, stubRegistry::getConfig('net.stubbles.number.decimals'));
        $this->assertEquals('net::stubbles::util::log::stubBaseLogData', stubRegistry::getConfig(stubLogData::CLASS_REGISTRY_KEY));
        $this->assertEquals('net::stubbles::ipo::request::stubWebRequest', stubRegistry::getConfig(stubRequest::CLASS_REGISTRY_KEY));
        $this->assertEquals('net::stubbles::ipo::session::stubPHPSession', stubRegistry::getConfig(stubSession::CLASS_REGISTRY_KEY));
    }
}
?>