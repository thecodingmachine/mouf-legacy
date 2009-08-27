<?php
/**
 * Test suite for all rdbms classes.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 */
/**
 * Test suite for all rdbms classes.
 *
 * @package     stubbles
 * @subpackage  test
 */
class PHPTestSuite extends PHPUnit_Framework_TestSuite
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
        
        // serializer
        $suite->addTestFile($dir . '/serializer/stubExceptionReferenceTestCase.php');
        $suite->addTestFile($dir . '/serializer/stubPHPSerializerObjectMappingTestCase.php');
        $suite->addTestFile($dir . '/serializer/stubPHPSerializerSPLSerializableMappingTestCase.php');
        $suite->addTestFile($dir . '/serializer/stubPHPSerializerTestCase.php');
        $suite->addTestFile($dir . '/serializer/stubUnknownObjectTestCase.php');
        
        // string operations
        $suite->addTestFile($dir . '/string/stubAbstractDecoratedStringEncoderTestCase.php');
        $suite->addTestFile($dir . '/string/stubAbstractStringEncoderTestCase.php');
        $suite->addTestFile($dir . '/string/stubBase64EncoderTestCase.php');
        $suite->addTestFile($dir . '/string/stubHTMLSpecialCharsEncoderTestCase.php');
        $suite->addTestFile($dir . '/string/stubLocalizedStringTestCase.php');
        $suite->addTestFile($dir . '/string/stubMd5EncoderTestCase.php');
        $suite->addTestFile($dir . '/string/stubRecursiveStringEncoderTestCase.php');
        $suite->addTestFile($dir . '/string/stubURLEncoderTestCase.php');
        $suite->addTestFile($dir . '/string/stubUTF8EncoderTestCase.php');
        return $suite;
    }
}
?>