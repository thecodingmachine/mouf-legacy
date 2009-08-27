<?php
/**
 * Test suite for all streams classes.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 * @version     $Id: StreamsTestSuite.php 1851 2008-09-26 16:50:49Z mikey $
 */
/**
 * Test suite for all streams classes.
 *
 * @package     stubbles
 * @subpackage  test
 */
class StreamsTestSuite extends PHPUnit_Framework_TestSuite
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
        $suite->addTestFile($dir . '/stubDecodingInputStreamTestCase.php');
        $suite->addTestFile($dir . '/stubEncodingOutputStreamTestCase.php');
        $suite->addTestFile($dir . '/stubResourceInputStreamTestCase.php');
        $suite->addTestFile($dir . '/stubResourceOutputStreamTestCase.php');
        
        // file
        $suite->addTestFile($dir . '/file/stubFileInputStreamTestCase.php');
        $suite->addTestFile($dir . '/file/stubFileOutputStreamTestCase.php');
                
        // memory
        $suite->addTestFile($dir . '/memory/stubMemoryInputStreamTestCase.php');
        $suite->addTestFile($dir . '/memory/stubMemoryOutputStreamTestCase.php');
        $suite->addTestFile($dir . '/memory/stubMemoryStreamWrapperTestCase.php');
        return $suite;
    }
}
?>