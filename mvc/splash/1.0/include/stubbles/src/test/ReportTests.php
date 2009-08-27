<?php
/**
 * Class to organize report tests.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 * @version     $Id: ReportTests.php 1875 2008-09-30 15:46:04Z mikey $
 */
ini_set('memory_limit', -1);
if (defined('PHPUnit_MAIN_METHOD') === false) {
    define('PHPUnit_MAIN_METHOD', 'src_test_ReportTests::main');
}

if (defined('TEST_SRC_PATH') === false) {
    define('TEST_SRC_PATH', dirname(__FILE__));
}

require_once TEST_SRC_PATH . '/../../projects/dist/config/php/config.php';
require_once TEST_SRC_PATH . '/../main/php/net/stubbles/stubClassLoader.php';
require_once TEST_SRC_PATH . '/../../lib/starWriter.php';
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Util/Filter.php';
PHPUnit_Util_Filter::addDirectoryToWhitelist(TEST_SRC_PATH . '/../main/php');
PHPUnit_Util_Filter::removeDirectoryFromWhitelist(TEST_SRC_PATH . '/../main/php/net/stubbles/util/ext');
PHPUnit_Util_Filter::removeDirectoryFromWhitelist(TEST_SRC_PATH . '/../main/php/net/stubbles/util/xjconf');
PHPUnit_Util_Filter::removeDirectoryFromWhitelist(TEST_SRC_PATH . '/../main/php/org');
require_once TEST_SRC_PATH . '/AllTests.php';
require_once TEST_SRC_PATH . '/IntegrationTests.php';
/**
 * Class to organize report tests.
 *
 * @package     stubbles
 * @subpackage  test
 */
class src_test_ReportTests extends PHPUnit_Framework_TestSuite
{
    /**
     * runs this test suite
     */
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    /**
     * returns the test suite to be run
     *
     * @return  PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        $suite = new self();
        $suite->addTestSuite('src_test_AllTests');
        $suite->addTestSuite('src_test_IntegrationTests');
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD === 'src_test_ReportTests::main') {
    src_test_ReportTests::main();
}
?>