<?php
/**
 * Test suite for all ipo classes.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 * @version     $Id: IPOTestSuite.php 1929 2008-11-13 21:49:05Z mikey $
 */
/**
 * Test suite for all ipo classes.
 *
 * @package     stubbles
 * @subpackage  test
 */
class IPOTestSuite extends PHPUnit_Framework_TestSuite
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
        $suite->addTestFile($dir . '/interceptors/stubInterceptorXJConfInitializerTestCase.php');
        $suite->addTestFile($dir . '/interceptors/stubRequestPreInterceptorTestCase.php');
        $suite->addTestFile($dir . '/interceptors/stubETagPostInterceptorTestCase.php');

        $suite->addTestFile($dir . '/request/stubAbstractRequestTestCase.php');
        $suite->addTestFile($dir . '/request/stubModifiableWebRequestTestCase.php');
        $suite->addTestFile($dir . '/request/stubRedirectRequestTestCase.php');
        $suite->addTestFile($dir . '/request/stubRequestPrefixDecoratorTestCase.php');
        $suite->addTestFile($dir . '/request/stubRequestValueErrorTestCase.php');
        $suite->addTestFile($dir . '/request/stubWebRequestTestCase.php');

        $suite->addTestFile($dir . '/request/broker/stubRequestBrokerTestCase.php');
        $suite->addTestFile($dir . '/request/broker/annotations/stubAbstractFilterAnnotationTestCase.php');
        $suite->addTestFile($dir . '/request/broker/annotations/stubAbstractStringFilterAnnotationTestCase.php');
        $suite->addTestFile($dir . '/request/broker/annotations/stubDateFilterAnnotationTestCase.php');
        $suite->addTestFile($dir . '/request/broker/annotations/stubFloatFilterAnnotationTestCase.php');
        $suite->addTestFile($dir . '/request/broker/annotations/stubHTTPURLFilterAnnotationTestCase.php');
        $suite->addTestFile($dir . '/request/broker/annotations/stubIntegerFilterAnnotationTestCase.php');
        $suite->addTestFile($dir . '/request/broker/annotations/stubMailFilterAnnotationTestCase.php');
        $suite->addTestFile($dir . '/request/broker/annotations/stubPasswordFilterAnnotationTestCase.php');
        $suite->addTestFile($dir . '/request/broker/annotations/stubPreselectFilterAnnotationTestCase.php');
        $suite->addTestFile($dir . '/request/broker/annotations/stubStringFilterAnnotationTestCase.php');
        $suite->addTestFile($dir . '/request/broker/annotations/stubTextFilterAnnotationTestCase.php');

        $suite->addTestFile($dir . '/request/filter/stubFilterFactoryTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubAbstractFilterDecoratorTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubDateFilterTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubDefaultValueFilterDecoratorTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubEncodingFilterDecoratorTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubFloatFilterTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubHTTPURLFilterTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubIntegerFilterTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubLengthFilterDecoratorTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubMailFilterTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubPassThruFilterTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubPasswordFilterTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubPeriodFilterDecoratorTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubRangeFilterDecoratorTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubRegexFilterDecoratorTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubRequiredFilterDecoratorTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubStrategyFilterDecoratorTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubStringFilterTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubTextFilterTestCase.php');
        $suite->addTestFile($dir . '/request/filter/stubValidatorFilterDecoratorTestCase.php');

        $suite->addTestFile($dir . '/request/filter/provider/stubMailFilterProviderTestCase.php');
        $suite->addTestFile($dir . '/request/filter/provider/stubSimpleFilterProviderTestCase.php');

        // validator
        $suite->addTestFile($dir . '/request/validator/stubAndValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubContainsValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubDenyValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubEqualValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubExtFilterValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubHTTPURLValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubIpValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubMailValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubMaxLengthValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubMaxNumberValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubMinLengthValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubMinNumberValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubOrValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubPassThruValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubPreSelectValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubRegexValidatorTestCase.php');
        $suite->addTestFile($dir . '/request/validator/stubXorValidatorTestCase.php');

        $suite->addTestFile($dir . '/response/stubBaseResponseTestCase.php');
        $suite->addTestFile($dir . '/response/stubCookieTestCase.php');

        $suite->addTestFile($dir . '/session/stubAbstractSessionTestCase.php');
        $suite->addTestFile($dir . '/session/stubFallbackSessionTestCase.php');
        $suite->addTestFile($dir . '/session/stubNoneDurableSessionTestCase.php');
        $suite->addTestFile($dir . '/session/stubPHPSessionTestCase.php');
        return $suite;
    }
}
?>