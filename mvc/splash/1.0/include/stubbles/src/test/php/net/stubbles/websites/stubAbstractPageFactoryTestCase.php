<?php
/**
 * Tests for net::stubbles::websites::stubAbstractPageFactory.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_test
 */
stubClassLoader::load('net::stubbles::websites::stubAbstractPageFactory');

/**
 * Tests for net::stubbles::websites::stubAbstractPageFactory
 *
 * @package     stubbles
 * @subpackage  websites_test
 * @group       websites
 */
class stubAbstractPageFactoryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  stubAbstractPageFactory
     */
    protected $abstractPageFactory;
    /**
     * mocked request instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->abstractPageFactory = $this->getMock('stubAbstractPageFactory',
                                                    array('hasPage',
                                                          'doGetPage'
                                                    )
                                     );
        $this->mockRequest         = $this->getMock('stubRequest');
    }

    /**
     * request has no page, load page index then
     *
     * @test
     */
    public function requestHasNoPage()
    {
        $this->mockRequest->expects($this->exactly(2))
                          ->method('hasValue')
                          ->with($this->equalTo('page'))
                          ->will($this->returnValue(false));
        $this->abstractPageFactory->expects($this->never())
                                  ->method('hasPage');
        $this->assertEquals('index', $this->abstractPageFactory->getPageName($this->mockRequest));
        $this->abstractPageFactory->setIndexPageName('home');
        $this->assertEquals('home', $this->abstractPageFactory->getPageName($this->mockRequest));
    }

    /**
     * page name from request is invalid, load page index then
     *
     * @test
     */
    public function requestHasInvalidPageName()
    {
        $this->mockRequest->expects($this->exactly(2))
                          ->method('hasValue')
                          ->with($this->equalTo('page'))
                          ->will($this->returnValue(true));
        $this->mockRequest->expects($this->exactly(2))
                          ->method('getValidatedValue')
                          ->with($this->anything(), $this->equalTo('page'))
                          ->will($this->returnValue(null));
        $this->abstractPageFactory->expects($this->never())
                                  ->method('hasPage');
        $this->assertEquals('index', $this->abstractPageFactory->getPageName($this->mockRequest));
        $this->abstractPageFactory->setIndexPageName('home');
        $this->assertEquals('home', $this->abstractPageFactory->getPageName($this->mockRequest));
    }

    /**
     * page name from request is invalid, load page index then
     *
     * @test
     */
    public function requestHasInvalidPageNameDifferentRequestParam()
    {
        $this->abstractPageFactory->setRequestParamName('foo');
        $this->mockRequest->expects($this->exactly(2))
                          ->method('hasValue')
                          ->with($this->equalTo('foo'))
                          ->will($this->returnValue(true));
        $this->mockRequest->expects($this->exactly(2))
                          ->method('getValidatedValue')
                          ->with($this->anything(), $this->equalTo('foo'))
                          ->will($this->returnValue(null));
        $this->abstractPageFactory->expects($this->never())
                                  ->method('hasPage');
        $this->assertEquals('index', $this->abstractPageFactory->getPageName($this->mockRequest));
        $this->abstractPageFactory->setIndexPageName('home');
        $this->assertEquals('home', $this->abstractPageFactory->getPageName($this->mockRequest));
    }

    /**
     * page name from request is valid, but page with this name does not exist, load page index then
     *
     * @test
     */
    public function requestHasValidNonExistingPageName()
    {
        $this->mockRequest->expects($this->exactly(2))
                          ->method('hasValue')
                          ->will($this->returnValue(true));
        $this->mockRequest->expects($this->exactly(2))
                          ->method('getValidatedValue')
                          ->will($this->returnValue('foo'));
        $this->abstractPageFactory->expects($this->exactly(2))
                                  ->method('hasPage')
                                  ->with($this->equalTo('foo'))
                                  ->will($this->returnValue(false));
        $this->assertEquals('index', $this->abstractPageFactory->getPageName($this->mockRequest));
        $this->abstractPageFactory->setIndexPageName('home');
        $this->assertEquals('home', $this->abstractPageFactory->getPageName($this->mockRequest));
    }

    /**
     * page name from request is valid and page exists, load this page then
     *
     * @test
     */
    public function requestHasValidExistingPageName()
    {
        $this->mockRequest->expects($this->exactly(2))
                          ->method('hasValue')
                          ->will($this->returnValue(true));
        $this->mockRequest->expects($this->exactly(2))
                          ->method('getValidatedValue')
                          ->will($this->returnValue('foo'));
        $this->abstractPageFactory->expects($this->exactly(2))
                                  ->method('hasPage')
                                  ->with($this->equalTo('foo'))
                                  ->will($this->returnValue(true));
        $this->assertEquals('foo', $this->abstractPageFactory->getPageName($this->mockRequest));
        $this->abstractPageFactory->setIndexPageName('home');
        $this->assertEquals('foo', $this->abstractPageFactory->getPageName($this->mockRequest));
    }

    /**
     * make sure the properties are set
     *
     * @test
     */
    public function getPageWithoutPagePrefix()
    {
        $page = new stubPage();
        $this->abstractPageFactory->expects($this->any())
                                  ->method('doGetPage')
                                  ->will($this->returnValue($page));
        $this->assertSame($page, $this->abstractPageFactory->getPage('index'));
        $this->assertEquals('index', $page->getProperty('name'));
        $this->assertEquals('index', $page->getProperty('fqname'));
    }

    /**
     * make sure the properties are set
     *
     * @test
     */
    public function getPageWithPagePrefix()
    {
        $this->abstractPageFactory->setPagePrefix('conf/');
        $page = new stubPage();
        $this->abstractPageFactory->expects($this->any())
                                  ->method('doGetPage')
                                  ->will($this->returnValue($page));
        $this->assertSame($page, $this->abstractPageFactory->getPage('index'));
        $this->assertEquals('index', $page->getProperty('name'));
        $this->assertEquals('conf/index', $page->getProperty('fqname'));
    }
}
?>