<?php
/**
 * Test suite for all console classes.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 */
/**
 * Test suite for all console classes.
 *
 * @package     stubbles
 * @subpackage  test
 */
class ConsoleTestSuite extends PHPUnit_Framework_TestSuite
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
        $suite->addTestFile($dir . '/stubConsoleExecutorTestCase.php');
        $suite->addTestFile($dir . '/stubConsoleInputStreamTestCase.php');
        $suite->addTestFile($dir . '/stubConsoleOutputStreamTestCase.php');
        return $suite;
    }
}
?>