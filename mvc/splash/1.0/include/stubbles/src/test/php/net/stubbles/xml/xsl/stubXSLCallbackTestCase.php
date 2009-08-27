<?php
/**
 * Test for net::stubbles::xml::xsl::stubXSLCallback.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_xsl_test
 */
stubClassLoader::load('net::stubbles::xml::xsl::stubXSLCallback');
class TestXSLCallback extends stubBaseObject
{
    /**
     * example method
     *
     * @return  string
     * @XSLMethod
     */
    public function hello($world)
    {
        return 'hello ' . $world;
    }
    
    /**
     * example method
     *
     * @return  string
     */
    public function youCanNotCallMe()
    {
        return 'bye world!';
    }
    
    /**
     * example method
     *
     * @return  string
     * @XSLMethod
     */
    protected function doNotCallMe()
    {
        return 'A protected method was called!';
    }
    
    /**
     * example method
     *
     * @return  string
     * @XSLMethod
     */
    private function doNotCallMeToo()
    {
        return 'A private method was called.';
    }
    
    /**
     * example method
     *
     * @return  string
     * @XSLMethod
     */
    public static function youCanDoThis()
    {
        return 'A static method was called.';
    }
}
/**
 * Test for net::stubbles::xml::xsl::stubXSLCallback.
 *
 * @package     stubbles
 * @subpackage  xml_xsl_test
 * @group       xml
 * @group       xml_xsl
 */
class stubXSLCallbackTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * callback class used for tests
     *
     * @var  TestXSLCallback
     */
    protected $callback;
    /**
     * instance to test
     *
     * @var  stubXSLCallback
     */
    protected $xslCallback;
    
    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->callback    = new TestXSLCallback();
        $this->xslCallback = stubXSLCallback::getInstance();
        $this->xslCallback->clearCallbacks();
        $this->xslCallback->setCallback('test', $this->callback);
    }
    
    /**
     * clean up test environment
     */
    public function tearDown()
    {
        $this->xslCallback->clearCallbacks();
    }
    
    /**
     * test setting and removing callback instances
     *
     * @test
     */
    public function getHasRemove()
    {
        $this->assertTrue($this->xslCallback->hasCallback('test'));
        $this->assertEquals($this->callback, $this->xslCallback->getCallback('test'));
        $this->xslCallback->removeCallback('test');
        $this->assertFalse($this->xslCallback->hasCallback('test'));
        $this->assertNull($this->xslCallback->getCallback('test'));
    }
    
    /**
     * test that clear removes all registered callbacks
     *
     * @test
     */
    public function cear()
    {
        $this->xslCallback->clearCallbacks();
        $this->assertFalse($this->xslCallback->hasCallback('test'));
        $this->assertNull($this->xslCallback->getCallback('test'));
    }
    
    /**
     * test that the expected value is returned
     *
     * @test
     */
    public function allOk()
    {
        $this->assertEquals('hello world!', stubXSLCallback::invoke('test', 'hello', 'world!'));
    }
    
    /**
     * test that a stubXSLCallbackException is thrown if call to invoke has too less parameters
     *
     * @test
     * @expectedException  stubXSLCallbackException
     */
    public function tooLessParams()
    {
        stubXSLCallback::invoke();
    }
    
    /**
     * test that a stubXSLCallbackException is thrown if callback does not exist
     *
     * @test
     * @expectedException  stubXSLCallbackException
     */
    public function callbackDoesNotExist()
    {
        stubXSLCallback::invoke('foo', 'hello');
    }
    
    /**
     * test that a stubXSLCallbackException is thrown if callback method is not correctly annotated
     *
     * @test
     * @expectedException  stubXSLCallbackException
     */
    public function callbackMethodNotAnnotated()
    {
        stubXSLCallback::invoke('test', 'youCanNotCallMe');
    }
    
    /**
     * test that a stubXSLCallbackException is thrown if callback method is protected
     *
     * @test
     * @expectedException  stubXSLCallbackException
     */
    public function protectedCallbackMethod()
    {
        stubXSLCallback::invoke('test', 'doNotCallMe');
    }
    
    /**
     * test that a stubXSLCallbackException is thrown if callback method is private
     *
     * @test
     * @expectedException  stubXSLCallbackException
     */
    public function privateCallbackMethod()
    {
        stubXSLCallback::invoke('test', 'doNotCallMeToo');
    }
    
    /**
     * test that a static callback method
     *
     * @test
     */
    public function staticCallbackMethod()
    {
        $this->assertEquals('A static method was called.', stubXSLCallback::invoke('test', 'youCanDoThis'));
    }
}
?>