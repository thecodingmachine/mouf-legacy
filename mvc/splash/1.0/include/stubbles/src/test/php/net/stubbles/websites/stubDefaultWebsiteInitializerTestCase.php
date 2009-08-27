<?php
/**
 * Tests for net::stubbles::websites::stubDefaultWebsiteInitializer.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_test
 */
stubClassLoader::load('net::stubbles::websites::stubDefaultWebsiteInitializer');
/**
 * Tests for net::stubbles::websites::stubDefaultWebsiteInitializer
 *
 * @package     stubbles
 * @subpackage  websites_test
 * @group       websites
 */
class stubDefaultWebsiteInitializerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * calling the init() method will set the current mode to stubMode::$PROD
     *
     * @test
     */
    public function initShouldSetCurrentModeToProd()
    {
        $websiteInitializer = new stubDefaultWebsiteInitializer();
        $websiteInitializer->init();
        $this->assertSame(stubMode::$PROD, stubMode::$CURRENT);
        restore_error_handler();
        restore_exception_handler();
    }

    /**
     * calling the init() method will set the current mode to stubMode::$PROD
     *
     * @test
     */
    public function initShouldSetCurrentModeToGivenDefaultMode()
    {
        $websiteInitializer = new stubDefaultWebsiteInitializer(null, stubMode::$DEV);
        $websiteInitializer->init();
        $this->assertSame(stubMode::$DEV, stubMode::$CURRENT);
        restore_error_handler();
        restore_exception_handler();
    }

    /**
     * correct initializer instances should be returned
     *
     * @test
     */
    public function initializersReturned()
    {
        $websiteInitializer = new stubDefaultWebsiteInitializer();
        $this->assertType('stubRegistryInitializer', $websiteInitializer->getRegistryInitializer());
        $this->assertFalse($websiteInitializer->hasGeneralInitializer());
        $this->assertNull($websiteInitializer->getGeneralInitializer());
        $this->assertType('stubInterceptorInitializer', $websiteInitializer->getInterceptorInitializer());
        $this->assertType('stubProcessorResolverFactory', $websiteInitializer->getProcessorResolverFactory());
    }

    /**
     * correct initializer instances should be returned
     *
     * @test
     */
    public function withGeneralInitializer()
    {
        $generalInitializer = new stubGeneralInitializer();
        $websiteInitializer = new stubDefaultWebsiteInitializer($generalInitializer);
        $this->assertType('stubRegistryInitializer', $websiteInitializer->getRegistryInitializer());
        $this->assertTrue($websiteInitializer->hasGeneralInitializer());
        $this->assertSame($generalInitializer, $websiteInitializer->getGeneralInitializer());
        $this->assertType('stubInterceptorInitializer', $websiteInitializer->getInterceptorInitializer());
        $this->assertType('stubProcessorResolverFactory', $websiteInitializer->getProcessorResolverFactory());
    }
}
?>