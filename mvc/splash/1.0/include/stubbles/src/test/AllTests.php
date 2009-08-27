<?php
/**
 * Class to organize all tests.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 * @version     $Id: AllTests.php 1873 2008-09-30 15:35:06Z mikey $
 */
ini_set('memory_limit', -1);
if (defined('PHPUnit_MAIN_METHOD') === false) {
    define('PHPUnit_MAIN_METHOD', 'src_test_AllTests::main');
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
require_once TEST_SRC_PATH . '/php/net/stubbles/stubTestSuite.php';
require_once TEST_SRC_PATH . '/php/net/stubbles/console/ConsoleTestSuite.php';
require_once TEST_SRC_PATH . '/php/net/stubbles/ioc/IOCTestSuite.php';
require_once TEST_SRC_PATH . '/php/net/stubbles/ipo/IPOTestSuite.php';
require_once TEST_SRC_PATH . '/php/net/stubbles/peer/PeerTestSuite.php';
require_once TEST_SRC_PATH . '/php/net/stubbles/php/PHPTestSuite.php';
require_once TEST_SRC_PATH . '/php/net/stubbles/rdbms/RDBMSTestSuite.php';
require_once TEST_SRC_PATH . '/php/net/stubbles/reflection/ReflectionTestSuite.php';
require_once TEST_SRC_PATH . '/php/net/stubbles/util/UtilTestSuite.php';
require_once TEST_SRC_PATH . '/php/net/stubbles/service/ServiceTestSuite.php';
require_once TEST_SRC_PATH . '/php/net/stubbles/streams/StreamsTestSuite.php';
require_once TEST_SRC_PATH . '/php/net/stubbles/websites/WebsitesTestSuite.php';
require_once TEST_SRC_PATH . '/php/net/stubbles/websites/variantmanager/VariantManagerTestSuite.php';
require_once TEST_SRC_PATH . '/php/net/stubbles/xml/XMLTestSuite.php';
/**
 * Class to organize all tests.
 *
 * @package     stubbles
 * @subpackage  test
 */
class src_test_AllTests extends PHPUnit_Framework_TestSuite
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
        $suite->addTestSuite('stubTestSuite');
        $suite->addTestSuite('ConsoleTestSuite');
        $suite->addTestSuite('IOCTestSuite');
        $suite->addTestSuite('IPOTestSuite');
        $suite->addTestSuite('PeerTestSuite');
        $suite->addTestSuite('PHPTestSuite');
        $suite->addTestSuite('RDBMSTestSuite');
        $suite->addTestSuite('ReflectionTestSuite');
        $suite->addTestSuite('ServiceTestSuite');
        $suite->addTestSuite('StreamsTestSuite');
        $suite->addTestSuite('UtilTestSuite');
        $suite->addTestSuite('WebsitesTestSuite');
        $suite->addTestSuite('VariantManagerTestSuite');
        $suite->addTestSuite('XMLTestSuite');
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD === 'src_test_AllTests::main') {
    src_test_AllTests::main();
}
?>