<?php
/**
 * Test suite for all service classes.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_test
 */
/**
 * Test suite for all service classes.
 *
 * @package     stubbles
 * @subpackage  service_test
 */
class ServiceTestSuite extends PHPUnit_Framework_TestSuite
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

        // debug
        $suite->addTestFile($dir . '/debug/stubFirebugLoggerTestCase.php');

        // json-rpc
        $suite->addTestFile($dir . '/jsonrpc/stubJsonRpcProcessorTestCase.php');
        $suite->addTestFile($dir . '/jsonrpc/stubJsonRpcWriterTestCase.php');
        $suite->addTestFile($dir . '/jsonrpc/subprocessors/stubJsonRpcAbstractGenerateSubProcessorTestCase.php');
        $suite->addTestFile($dir . '/jsonrpc/subprocessors/stubJsonRpcGenerateProxiesSubProcessorTestCase.php');
        $suite->addTestFile($dir . '/jsonrpc/subprocessors/stubJsonRpcGenerateSmdSubProcessorTestCase.php');
        $suite->addTestFile($dir . '/jsonrpc/subprocessors/stubJsonRpcGetSubProcessorTestCase.php');
        $suite->addTestFile($dir . '/jsonrpc/subprocessors/stubJsonRpcPostSubProcessorTestCase.php');
        $suite->addTestFile($dir . '/jsonrpc/util/stubFirebugEncoderTestCase.php');
        $suite->addTestFile($dir . '/jsonrpc/util/stubJsonRpcProxyGeneratorTestCase.php');
        $suite->addTestFile($dir . '/jsonrpc/util/stubSmdGeneratorTestCase.php');

        // soap
        $suite->addTestFile($dir . '/soap/stubSoapClientConfigurationTestCase.php');
        $suite->addTestFile($dir . '/soap/stubSoapClientGeneratorTestCase.php');
        $suite->addTestFile($dir . '/soap/stubSoapExceptionTestCase.php');
        $suite->addTestFile($dir . '/soap/stubSoapFaultTestCase.php');
        $suite->addTestFile($dir . '/soap/native/stubNativeSoapClientTestCase.php');
        return $suite;
    }
}
?>