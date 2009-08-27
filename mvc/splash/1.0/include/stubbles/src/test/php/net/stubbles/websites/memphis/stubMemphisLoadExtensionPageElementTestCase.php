<?php
/**
 * Tests for net::stubbles::websites::memphis::stubMemphisLoadExtensionPageElement.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis_test
 */
stubClassLoader::load('net::stubbles::websites::memphis::stubMemphisLoadExtensionPageElement');
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  websites_memphis_test
 * @group       websites
 * @group       websites_memphis
 */
class TeststubMemphisLoadExtensionPageElement extends stubMemphisLoadExtensionPageElement
{
    /**
     * sets extension instance
     *
     * @param  stubMemphisExtension  $extension
     */
    public function setExtensionInstance(stubMemphisExtension $extension)
    {
        $this->extension = $extension;
    }

    /**
     * returns extension instance
     *
     * @return  stubMemphisExtension
     */
    public function getExtensionInstance()
    {
        return $this->extension;
    }
}
/**
 * Tests for net::stubbles::websites::memphis::stubMemphisLoadExtensionPageElement.
 *
 * @package     stubbles
 * @subpackage  websites_memphis_test
 */
class stubMemphisLoadExtensionPageElementTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  TeststubMemphisLoadExtensionPageElement
     */
    protected $loadExtensionPageElement;
    /**
     * mocked request instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;
    /**
     * mocked session instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSession;
    /**
     * mocked response instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockResponse;
    /**
     * context data
     *
     * @var  array<string,mixed>
     */
    protected $context                  = array('foo' => 'bar');
    /**
     * mocked injector instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockInjector;
    /**
     * mocked extension instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockMemphisExtension;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->loadExtensionPageElement = new TeststubMemphisLoadExtensionPageElement();
        $this->mockRequest              = $this->getMock('stubRequest');
        $this->mockSession              = $this->getMock('stubSession');
        $this->mockResponse             = $this->getMock('stubResponse');
        $this->mockInjector             = $this->getMock('stubInjector');
        stubRegistry::set(stubBinder::REGISTRY_KEY, new stubBinder($this->mockInjector));
        $this->mockMemphisExtension = $this->getMock('stubMemphisExtension');
    }

    /**
     * assure that setting and getting the name of the element works as expected
     *
     * @test
     */
    public function name()
    {
        $this->assertEquals('', $this->loadExtensionPageElement->getName());
        $this->loadExtensionPageElement->setName('foo');
        $this->assertEquals('foo', $this->loadExtensionPageElement->getName());
    }

    /**
     * a missing binder in the registry triggers a runtime exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function missingBinderThrowsRuntimeException()
    {
        stubRegistry::remove(stubBinder::REGISTRY_KEY);
        $this->loadExtensionPageElement->init($this->mockRequest, $this->mockSession, $this->mockResponse, $this->context);
    }

    /**
     * a wrong extension instance triggers a runtime exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function wrongExtensionClassThrowsRuntimeException()
    {
        $this->mockInjector->expects($this->once())
                           ->method('getInstance')
                           ->with($this->equalTo('extension.class'))
                           ->will($this->returnValue(new stdClass()));
        $this->loadExtensionPageElement->setExtension('extension.class');
        $this->loadExtensionPageElement->init($this->mockRequest, $this->mockSession, $this->mockResponse, $this->context);
    }

    /**
     * extension should be created correct
     *
     * @test
     */
    public function extensionIsCreated()
    {
        $this->mockInjector->expects($this->once())
                           ->method('getInstance')
                           ->with($this->equalTo('extension.class'))
                           ->will($this->returnValue($this->mockMemphisExtension));
        $this->loadExtensionPageElement->setExtension('extension.class');
        $this->assertEquals('extension.class', $this->loadExtensionPageElement->getExtension());
        $this->loadExtensionPageElement->init($this->mockRequest, $this->mockSession, $this->mockResponse, $this->context);
        $this->assertSame($this->mockMemphisExtension, $this->loadExtensionPageElement->getExtensionInstance());
    }

    /**
     * extension should be created correct
     *
     * @test
     */
    public function extensionIsCreatedOnceOnly()
    {
        $this->mockInjector->expects($this->never())
                           ->method('getInstance');
        $this->loadExtensionPageElement->setExtensionInstance($this->mockMemphisExtension);
        $this->mockMemphisExtension->expects($this->once())
                                   ->method('setContext')
                                   ->with($this->equalTo($this->context));
        $this->loadExtensionPageElement->init($this->mockRequest, $this->mockSession, $this->mockResponse, $this->context);
        $this->assertSame($this->mockMemphisExtension, $this->loadExtensionPageElement->getExtensionInstance());
    }

    /**
     * assure that extension will be called for caching issues
     *
     * @test
     */
    public function extensionCalledForCachability()
    {
        $this->loadExtensionPageElement->setExtensionInstance($this->mockMemphisExtension);
        $this->mockMemphisExtension->expects($this->once())
                                   ->method('isCachable')
                                   ->will($this->returnValue(true));
        $this->assertTrue($this->loadExtensionPageElement->isCachable());
    }

    /**
     * assure that extension will be called for caching issues
     *
     * @test
     */
    public function extensionCalledForCacheVars()
    {
        $this->loadExtensionPageElement->setExtensionInstance($this->mockMemphisExtension);
        $this->mockMemphisExtension->expects($this->once())
                                   ->method('getCacheVars')
                                   ->will($this->returnValue(array('bar' => 'baz')));
        $this->assertEquals(array('bar' => 'baz'), $this->loadExtensionPageElement->getCacheVars());
    }

    /**
     * assure that extension will be called for caching issues
     *
     * @test
     */
    public function extensionCalledForUsedFiles()
    {
        $this->loadExtensionPageElement->setExtensionInstance($this->mockMemphisExtension);
        $this->mockMemphisExtension->expects($this->once())
                                   ->method('getUsedFiles')
                                   ->will($this->returnValue(array('baz')));
        $this->assertEquals(array('baz'), $this->loadExtensionPageElement->getUsedFiles());
    }

    /**
     * assure that extension will be called for processing the request
     *
     * @test
     */
    public function extensionCalledForProcess()
    {
        $this->loadExtensionPageElement->setExtensionInstance($this->mockMemphisExtension);
        $this->mockMemphisExtension->expects($this->once())
                                   ->method('process')
                                   ->will($this->returnValue('content'));
        $this->assertEquals('content', $this->loadExtensionPageElement->process());
    }
}
?>