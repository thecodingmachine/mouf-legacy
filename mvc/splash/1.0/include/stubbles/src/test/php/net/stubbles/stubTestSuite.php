<?php
/**
 * Test suite for all base classes.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 */
/**
 * Test suite for all base classes.
 *
 * @package     stubbles
 * @subpackage  test
 */
class stubTestSuite extends PHPUnit_Framework_TestSuite
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
        $suite->addTestFile($dir . '/stubClassLoaderTestCase.php');

        // lang
        $suite->addTestFile($dir . '/lang/stubArrayAccessorTestCase.php');
        $suite->addTestFile($dir . '/lang/stubBaseObjectTestCase.php');
        $suite->addTestFile($dir . '/lang/stubEnumTestCase.php');
        $suite->addTestFile($dir . '/lang/stubModeTestCase.php');
        $suite->addTestFile($dir . '/lang/stubRegistryTestCase.php');

        // error handler
        $suite->addTestFile($dir . '/lang/errorhandler/stubAbstractExceptionHandlerTestCase.php');
        $suite->addTestFile($dir . '/lang/errorhandler/stubCompositeErrorHandlerTestCase.php');
        $suite->addTestFile($dir . '/lang/errorhandler/stubDisplayExceptionHandlerTestCase.php');
        $suite->addTestFile($dir . '/lang/errorhandler/stubIllegalArgumentErrorHandlerTestCase.php');
        $suite->addTestFile($dir . '/lang/errorhandler/stubLogErrorHandlerTestCase.php');
        $suite->addTestFile($dir . '/lang/errorhandler/stubProdModeExceptionHandlerTestCase.php');

        // exceptions
        $suite->addTestFile($dir . '/lang/exceptions/stubExceptionTestCase.php');
        $suite->addTestFile($dir . '/lang/exceptions/stubChainedExceptionTestCase.php');
        $suite->addTestFile($dir . '/lang/exceptions/stubRuntimeExceptionTestCase.php');

        // initializer
        $suite->addTestFile($dir . '/lang/initializer/stubGeneralInitializerTestCase.php');
        $suite->addTestFile($dir . '/lang/initializer/stubRegistryXJConfInitializerTestCase.php');

        // serialize
        $suite->addTestFile($dir . '/lang/serialize/stubSerializableObjectTestCase.php');
        $suite->addTestFile($dir . '/lang/serialize/stubSerializedObjectTestCase.php');

        // types
        $suite->addTestFile($dir . '/lang/types/stubDateTestCase.php');
        $suite->addTestFile($dir . '/lang/types/stubTimeZoneTestCase.php');

        return $suite;
    }
}
?>