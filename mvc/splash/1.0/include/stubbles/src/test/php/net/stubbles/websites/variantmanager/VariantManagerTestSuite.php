<?php
/**
 * Test suite for all variant manager classes.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 */
/**
 * Test suite for all variant manager classes.
 *
 * @package     stubbles
 * @subpackage  test
 */
class VariantManagerTestSuite extends PHPUnit_Framework_TestSuite
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
        $suite->addTestFile($dir . '/stubAbstractVariantFactoryTestCase.php');
        $suite->addTestFile($dir . '/stubVariantsMapTestCase.php');
        $suite->addTestFile($dir . '/stubVariantsPreInterceptorCookieVariantTestCase.php');
        $suite->addTestFile($dir . '/stubVariantsPreInterceptorProcessTestCase.php');
        $suite->addTestFile($dir . '/stubVariantXJConfFactoryTestCase.php');
        
        // types
        $suite->addTestFile($dir . '/types/stubAbstractVariantTestCase.php');
        $suite->addTestFile($dir . '/types/stubDummyVariantTestCase.php');
        $suite->addTestFile($dir . '/types/stubLeadVariantTestCase.php');
        $suite->addTestFile($dir . '/types/stubRandomVariantTestCase.php');
        $suite->addTestFile($dir . '/types/stubRequestParamVariantTestCase.php');
        $suite->addTestFile($dir . '/types/stubRootVariantTestCase.php');
        return $suite;
    }
}
?>