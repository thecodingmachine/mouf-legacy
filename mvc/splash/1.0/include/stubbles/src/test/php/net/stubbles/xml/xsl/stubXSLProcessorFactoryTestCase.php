<?php
/**
 * Test for net::stubbles::xml::xsl::stubXSLProcessorFactory.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_xsl_test
 * @version     $Id: stubXSLProcessorFactoryTestCase.php 1904 2008-10-25 14:04:33Z mikey $
 */
stubClassLoader::load('net::stubbles::xml::xsl::stubXSLProcessorFactory');
/**
 * Test for net::stubbles::xml::xsl::stubXSLProcessorFactory.
 *
 * @package     stubbles
 * @subpackage  xml_xsl_test
 * @group       xml
 * @group       xml_xsl
 */
class stubXSLProcessorFactoryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * set up test environment
     */
    public function setUp()
    {
        if (extension_loaded('xsl') === false) {
            $this->markTestSkipped('net::stubbles::xml::xsl::stubXSLProcessorFactory requires PHP-extension "xsl".');
        }
        
        $binder = new stubBinder();
        $binder->bind('stubRequest')->toInstance($this->getMock('stubRequest'));
        stubRegistry::set(stubBinder::REGISTRY_KEY, $binder);
    }

    /**
     * clear test environment
     */
    public function tearDown()
    {
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
    }

    /**
     * test creation of XSL processor
     *
     * @test
     */
    public function createReturnsXSLProcessor()
    {
        $xslProcessor = stubXSLProcessorFactory::create();
        $this->assertType('stubXSLProcessor', $xslProcessor);
        $this->assertNotSame($xslProcessor, stubXSLProcessorFactory::create());
    }

    /**
     * ensure callback configuration is correct
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function callbacksConfigurationFileMissing()
    {
        stubXSLProcessorFactory::createWithCallbacks(dirname(__FILE__) . '/doesNotExist.ini');
    }

    /**
     * assure that missing binder triggers an exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function createWithCallbacksWithoutBinderInRegistry()
    {
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
        stubXSLProcessorFactory::createWithCallbacks();
    }

    /**
     * test creation of XSL processor using callbacks
     *
     * @test
     */
    public function createWithCallbacksReturnsXSLProcessor()
    {
        $xslProcessor = stubXSLProcessorFactory::createWithCallbacks();
        $this->assertType('stubXSLProcessor', $xslProcessor);
        $this->assertNotSame($xslProcessor, stubXSLProcessorFactory::createWithCallbacks());
    }

    /**
     * ensure callback configuration is correct
     *
     * @test
     */
    public function createWithCallbacksReturnsXSLProcessorWithCallbacks()
    {
        $callbacks = stubXSLProcessorFactory::createWithCallbacks()->getCallbacks();
        $this->assertTrue(isset($callbacks['image']));
        $this->assertEquals('net::stubbles::xml::xsl::util::stubXSLImageDimensions', $callbacks['image']->getClassName());
        $this->assertTrue(isset($callbacks['request']));
        $this->assertEquals('net::stubbles::xml::xsl::util::stubXSLRequestParams', $callbacks['request']->getClassName());
        $this->assertTrue(isset($callbacks['date']));
        $this->assertEquals('net::stubbles::xml::xsl::util::stubXSLDateFormatter', $callbacks['date']->getClassName());
    }

}
?>