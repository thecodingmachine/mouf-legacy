<?php
/**
 * Test suite for all websites classes.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 */
/**
 * Test suite for all websites classes.
 *
 * @package     stubbles
 * @subpackage  test
 */
class WebsitesTestSuite extends PHPUnit_Framework_TestSuite
{
    /**
     * returns the test suite to be run
     *
     * @return  PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        $suite = new self();
        $dir   = dirname(__FILE__);
        $suite->addTestFile($dir . '/stubAbstractPageElementTestCase.php');
        $suite->addTestFile($dir . '/stubAbstractPageFactoryTestCase.php');
        $suite->addTestFile($dir . '/stubDefaultWebsiteInitializerTestCase.php');
        $suite->addTestFile($dir . '/stubFrontControllerTestCase.php');
        $suite->addTestFile($dir . '/stubPageTestCase.php');

        // cache
        $suite->addTestFile($dir . '/cache/stubAbstractWebsiteCacheFactoryTestCase.php');
        $suite->addTestFile($dir . '/cache/stubAbstractWebsiteCacheTestCase.php');
        $suite->addTestFile($dir . '/cache/stubCachingProcessorTestCase.php');
        $suite->addTestFile($dir . '/cache/stubDefaultWebsiteCacheFactoryTestCase.php');
        $suite->addTestFile($dir . '/cache/stubDefaultWebsiteCacheTestCase.php');
        $suite->addTestFile($dir . '/cache/stubGzipWebsiteCacheTestCase.php');

        // memphis
        $suite->addTestFile($dir . '/memphis/stubMemphisAbstractExtensionTestCase.php');
        $suite->addTestFile($dir . '/memphis/stubMemphisIncludeFilePageElementTestCase.php');
        $suite->addTestFile($dir . '/memphis/stubMemphisIncludeTemplatePageElementTestCase.php');
        $suite->addTestFile($dir . '/memphis/stubMemphisLoadExtensionPageElementTestCase.php');
        $suite->addTestFile($dir . '/memphis/stubMemphisPageElementTestCase.php');
        $suite->addTestFile($dir . '/memphis/stubMemphisProcessorCachableTestCase.php');
        $suite->addTestFile($dir . '/memphis/stubMemphisProcessorTestCase.php');

        // processors
        $suite->addTestFile($dir . '/processors/stubAbstractProcessorResolverTestCase.php');
        $suite->addTestFile($dir . '/processors/stubAbstractProcessorTestCase.php');
        $suite->addTestFile($dir . '/processors/stubDefaultProcessorResolverTestCase.php');
        $suite->addTestFile($dir . '/processors/stubProcessorResolverXJConfFactoryTestCase.php');
        $suite->addTestFile($dir . '/processors/stubSimpleProcessorResolverTestCase.php');
        
        // rasmus
        $suite->addTestFile($dir . '/rasmus/stubRasmusProcessorTestCase.php');

        // xml
        $suite->addTestFile($dir . '/xml/stubShowLastXMLInterceptorTestCase.php');
        $suite->addTestFile($dir . '/xml/stubXMLProcessorTestCase.php');

        // xml generator
        $suite->addTestFile($dir . '/xml/generator/stubModeXMLGeneratorTestCase.php');
        $suite->addTestFile($dir . '/xml/generator/stubPageXMLGeneratorTestCase.php');
        $suite->addTestFile($dir . '/xml/generator/stubRequestXMLGeneratorTestCase.php');
        $suite->addTestFile($dir . '/xml/generator/stubSessionXMLGeneratorTestCase.php');
        
        // xml page
        $suite->addTestFile($dir . '/xml/page/stubAbstractXMLPageElementTestCase.php');
        $suite->addTestFile($dir . '/xml/page/stubXMLPageElementCachingDecoratorTestCase.php');
        $suite->addTestFile($dir . '/xml/page/stubXMLPageElementDecoratorTestCase.php');
        $suite->addTestFile($dir . '/xml/page/stubXMLPassThruPageElementTestCase.php');

        // xml skin
        $suite->addTestFile($dir . '/xml/skin/stubCachingSkinGeneratorTestCase.php');
        $suite->addTestFile($dir . '/xml/skin/stubDefaultSkinGeneratorTestCase.php');
        $suite->addTestFile($dir . '/xml/skin/stubSkinGeneratorFactoryTestCase.php');
        return $suite;
    }
}
?>