<?php
/**
 * Test suite for all util classes.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 */
/**
 * Test suite for all util classes.
 *
 * @package     stubbles
 * @subpackage  test
 */
class UtilTestSuite extends PHPUnit_Framework_TestSuite
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
        $suite->addTestFile($dir . '/BinfordTestCase.php');

        // cache
        $suite->addTestFile($dir . '/cache/stubCacheTestCase.php');
        $suite->addTestFile($dir . '/cache/stubDefaultCacheStrategyTestCase.php');
        $suite->addTestFile($dir . '/cache/stubAbstractCacheContainerTestCase.php');
        $suite->addTestFile($dir . '/cache/stubFileCacheContainerTestCase.php');

        // datespan
        $suite->addTestFile($dir . '/datespan/stubDateSpanCustomTestCase.php');
        $suite->addTestFile($dir . '/datespan/stubDateSpanDayTestCase.php');
        $suite->addTestFile($dir . '/datespan/stubDateSpanMonthTestCase.php');
        $suite->addTestFile($dir . '/datespan/stubDateSpanWeekTestCase.php');
        $suite->addTestFile($dir . '/datespan/stubDateSpanYesterdayTestCase.php');

        // logging api
        $suite->addTestFile($dir . '/log/stubBaseLogDataTestCase.php');
        $suite->addTestFile($dir . '/log/stubExceptionLogTestCase.php');
        $suite->addTestFile($dir . '/log/stubFileLogAppenderTestCase.php');
        $suite->addTestFile($dir . '/log/stubLogDataFactoryTestCase.php');
        $suite->addTestFile($dir . '/log/stubLoggerTestCase.php');
        $suite->addTestFile($dir . '/log/stubLoggerXJConfInitializerTestCase.php');
        $suite->addTestFile($dir . '/log/stubMemoryLogAppenderTestCase.php');

        // xjconf
        $suite->addTestFile($dir . '/xjconf/stubXJConfProxyTestCase.php');
        return $suite;
    }
}
?>