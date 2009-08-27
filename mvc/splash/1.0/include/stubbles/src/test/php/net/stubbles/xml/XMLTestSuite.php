<?php
/**
 * Test suite for all XML classes.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 * @version     $Id: XMLTestSuite.php 1904 2008-10-25 14:04:33Z mikey $
 */
/**
 * Test suite for all XML classes.
 *
 * @package     stubbles
 * @subpackage  test
 */
class XMLTestSuite extends PHPUnit_Framework_TestSuite
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
        $suite->addTestFile($dir . '/stubXMLStreamWriterFactoryTestCase.php');
        $suite->addTestFile($dir . '/stubDomXMLStreamWriterTestCase.php');
        $suite->addTestFile($dir . '/stubLibXmlXMLStreamWriterTestCase.php');

        // rss
        $suite->addTestFile($dir . '/rss/stubAbstractRSSFeedTestCase.php');
        $suite->addTestFile($dir . '/rss/stubRSSFeedGeneratorTestCase.php');
        $suite->addTestFile($dir . '/rss/stubRSSFeedItemAnnotationTestCase.php');
        $suite->addTestFile($dir . '/rss/stubRSSFeedItemTestCase.php');
        $suite->addTestFile($dir . '/rss/stubRSSProcessorTestCase.php');

        // serializer
        $suite->addTestFile($dir . '/serializer/stubXMLSerializerTestCase.php');
        $suite->addTestFile($dir . '/serializer/stubXMLSerializerStrategyTestCase.php');
        $suite->addTestFile($dir . '/serializer/annotations/stubXMLMatcherAnnotationTestCase.php');
        
        // unserializer
        $suite->addTestFile($dir . '/unserializer/stubXMLUnserializerTestCase.php');

        // xsl
        $suite->addTestFile($dir . '/xsl/stubXSLCallbackTestCase.php');
        $suite->addTestFile($dir . '/xsl/stubXSLProcessorFactoryTestCase.php');
        $suite->addTestFile($dir . '/xsl/stubXSLProcessorTestCase.php');
        $suite->addTestFile($dir . '/xsl/util/stubXSLDateFormatterTestCase.php');
        $suite->addTestFile($dir . '/xsl/util/stubXSLImageDimensionsTestCase.php');
        $suite->addTestFile($dir . '/xsl/util/stubXSLRequestParamsTestCase.php');
        return $suite;
    }
}
?>