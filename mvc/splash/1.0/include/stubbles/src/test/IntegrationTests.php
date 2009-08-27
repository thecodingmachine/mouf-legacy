<?php
/**
 * Class to organize integration tests.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 * @version     $Id: IntegrationTests.php 1875 2008-09-30 15:46:04Z mikey $
 */
ini_set('memory_limit', -1);
if (defined('PHPUnit_MAIN_METHOD') === false) {
    define('PHPUnit_MAIN_METHOD', 'src_test_IntegrationTests::main');
}

if (defined('TEST_SRC_PATH') === false) {
    define('TEST_SRC_PATH', dirname(__FILE__));
}

require_once TEST_SRC_PATH . '/../../projects/dist/config/php/config.php';
require_once TEST_SRC_PATH . '/../main/php/net/stubbles/stubClassLoader.php';
require_once TEST_SRC_PATH . '/../../lib/starWriter.php';
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
/**
 * Class to organize integration tests.
 *
 * @package     stubbles
 * @subpackage  test
 */
class src_test_IntegrationTests extends PHPUnit_Framework_TestSuite
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
        $dir   = dirname(__FILE__) . '/php/net/stubbles/integration';
        $suite->addTestFile($dir . '/AnnotationTestCase.php');
        $suite->addTestFile($dir . '/CacheTestCase.php');
        $suite->addTestFile($dir . '/DatabaseTestCase.php');
        $suite->addTestFile($dir . '/EncoderXJConfTestCase.php');
        $suite->addTestFile($dir . '/InterceptorTestCase.php');
        $suite->addTestFile($dir . '/LoggerTestCase.php');
        $suite->addTestFile($dir . '/MemphisConfigTestCase.php');
        $suite->addTestFile($dir . '/ProcessorTestCase.php');
        $suite->addTestFile($dir . '/RegistryTestCase.php');
        $suite->addTestFile($dir . '/StreamsXJConfTestCase.php');
        $suite->addTestFile($dir . '/stubPageXJConfFactoryTestCase.php');
        $suite->addTestFile($dir . '/stubRequestValueErrorXJConfFactoryTestCase.php');
        $suite->addTestFile($dir . '/ValidatorsXJConfTestCase.php');
        $suite->addTestFile($dir . '/VariantManagerTestCase.php');
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD === 'src_test_IntegrationTests::main') {
    src_test_IntegrationTests::main();
}
?>