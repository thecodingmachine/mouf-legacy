<?php
/**
 * Test suite for all peer classes.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 */
/**
 * Test suite for all peer classes.
 *
 * @package     stubbles
 * @subpackage  test
 */
class PeerTestSuite extends PHPUnit_Framework_TestSuite
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
 
        // peer classes
        $suite->addTestFile($dir . '/stubBSDSocketTestCase.php');
        $suite->addTestFile($dir . '/stubHeaderListTestCase.php');
        $suite->addTestFile($dir . '/stubSocketTestCase.php');
        $suite->addTestFile($dir . '/stubURLTestCase.php');

        // http classes
        $suite->addTestFile($dir . '/http/stubHTTPConnectionTestCase.php');
        $suite->addTestFile($dir . '/http/stubHTTPRequestTestCase.php');
        $suite->addTestFile($dir . '/http/stubHTTPResponseTestCase.php');
        $suite->addTestFile($dir . '/http/stubHTTPURLTestCase.php');

        // ldap classes
        $suite->addTestFile($dir . '/ldap/stubLDAPURLTestCase.php');
        $suite->addTestFile($dir . '/ldap/stubLDAPConnectionTestCase.php');
        $suite->addTestFile($dir . '/ldap/stubLDAPSearchResultTestCase.php');
        $suite->addTestFile($dir . '/ldap/stubLDAPEntryTestCase.php');

        return $suite;
    }
}
?>