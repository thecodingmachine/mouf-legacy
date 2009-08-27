<?php
/**
 * Test suite for all ioc classes.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 */
/**
 * Test suite for all ioc classes.
 *
 * @package     stubbles
 * @subpackage  test
 */
class IOCTestSuite extends PHPUnit_Framework_TestSuite
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
        $suite->addTestFile($dir . '/annotations/stubImplementedByAnnotationTestCase.php');
        $suite->addTestFile($dir . '/annotations/stubInjectAnnotationTestCase.php');
        $suite->addTestFile($dir . '/annotations/stubNamedAnnotationTestCase.php');
        $suite->addTestFile($dir . '/annotations/stubSingletonAnnotationTestCase.php');
        $suite->addTestFile($dir . '/stubAbstractIOCPreInterceptorTestCase.php');
        $suite->addTestFile($dir . '/stubBinderRegistryTestCase.php');
        $suite->addTestFile($dir . '/stubBinderTestCase.php');
        $suite->addTestFile($dir . '/stubInjectorBasicTestCase.php');
        $suite->addTestFile($dir . '/stubInjectorConstantTestCase.php');
        $suite->addTestFile($dir . '/stubInjectorImplementedByTestCase.php');
        $suite->addTestFile($dir . '/stubInjectorNamedTestCase.php');
        $suite->addTestFile($dir . '/stubInjectorProviderTestCase.php');
        $suite->addTestFile($dir . '/stubInjectorSessionTestCase.php');
        $suite->addTestFile($dir . '/stubInjectorSingletonTestCase.php');
        $suite->addTestFile($dir . '/stubIOCPreInterceptorTestCase.php');
        return $suite;
    }
}
?>