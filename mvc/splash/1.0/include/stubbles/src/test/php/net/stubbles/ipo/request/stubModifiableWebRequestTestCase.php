<?php
/**
 * Tests for net::stubbles::ipo::request::stubModifiableWebRequest.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_test
 */
stubClassLoader::load('net::stubbles::ipo::request::stubModifiableWebRequest');
/**
 * Helper class for the tests.
 *
 * @package     stubbles
 * @subpackage  ipo_request_test
 */
class TeststubModifiableWebRequest extends stubModifiableWebRequest
{
    /**
     * direct access to unsecure params data
     *
     * @return  array<string,string>
     */
    public function getUnsecureParams()
    {
        return $this->unsecureParams;
    }

    /**
     * direct access to unsecure headers data
     *
     * @return  array<string,string>
     */
    public function getUnsecureHeaders()
    {
        return $this->unsecureHeaders;
    }

    /**
     * direct access to unsecure cookies data
     *
     * @return  array<string,string>
     */
    public function getUnsecureCookies()
    {
        return $this->unsecureCookies;
    }
}
/**
 * Tests for net::stubbles::ipo::request::stubModifiableWebRequest.
 *
 * @package     stubbles
 * @subpackage  ipo_request_test
 * @group       ipo
 * @group       ipo_request
 */
class stubModifiableWebRequestTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  TeststubModifiableWebRequest
     */
    protected $request;
    /**
     * backup copy of original data
     *
     * @var  array
     */
    protected $originalData = array();

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->originalData['server'] = $_SERVER;
        $this->originalData['cookie'] = $_COOKIE;
        $this->originalData['get']    = $_GET;
        $this->originalData['post']   = $_POST;
        $_GET    = array('foo' => 'bar', 'baz' => array('one', 'two\"'));
        $_POST   = array('foo' => 'baz', 'bar' => 'foo');
        $_SERVER = array('key' => 'value');
        $_COOKIE = array('name' => 'value');
        $this->request = new TeststubModifiableWebRequest();
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        $_SERVER = $this->originalData['server'];
        $_COOKIE = $this->originalData['cookie'];
        $_GET    = $this->originalData['get'];
        $_POST   = $this->originalData['post'];
    }

    /**
     * handling of get/post params
     *
     * @test
     */
    public function params()
    {
        $this->assertEquals(array('foo' => 'baz',
                                  'baz' => array('one', 'two"'),
                                  'bar' => 'foo'
                            ),
                            $this->request->getUnsecureParams()
        );
        $this->request->setParam('baz', 'bar');
        $this->assertEquals(array('foo' => 'baz',
                                  'baz' => 'bar',
                                  'bar' => 'foo'
                            ),
                            $this->request->getUnsecureParams()
        );
        $this->request->setParam('dummy', 'baz', 'otherSource');
        $this->assertEquals(array('foo'   => 'baz',
                                  'baz'   => 'bar',
                                  'bar'   => 'foo',
                                  'dummy' => 'baz'
                            ),
                            $this->request->getUnsecureParams()
        );
    }

    /**
     * handling of header params
     *
     * @test
     */
    public function header()
    {
        $this->assertEquals(array('key' => 'value'), $this->request->getUnsecureHeaders());
        $this->request->setParam('key', 'otherValue', stubRequest::SOURCE_HEADER);
        $this->assertEquals(array('key' => 'otherValue'), $this->request->getUnsecureHeaders());
        $this->request->setParam('otherkey', 'value', stubRequest::SOURCE_HEADER);
        $this->assertEquals(array('key'      => 'otherValue',
                                  'otherkey' => 'value'
                            ),
                            $this->request->getUnsecureHeaders()
        );
    }

    /**
     * handling of cookie params
     *
     * @test
     */
    public function cookie()
    {
        $this->assertEquals(array('name' => 'value'), $this->request->getUnsecureCookies());
        $this->request->setParam('name', 'otherValue', stubRequest::SOURCE_COOKIE);
        $this->assertEquals(array('name' => 'otherValue'), $this->request->getUnsecureCookies());
        $this->request->setParam('other', 'value', stubRequest::SOURCE_COOKIE);
        $this->assertEquals(array('name'  => 'otherValue',
                                  'other' => 'value'
                            ),
                            $this->request->getUnsecureCookies()
        );
    }
}
?>